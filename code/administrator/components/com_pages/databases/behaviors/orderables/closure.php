<?php
class ComPagesDatabaseBehaviorOrderableClosure extends ComPagesDatabaseBehaviorOrderableAbstract/* implements ComPagesDatabaseBehaviorOrderableInterface*/
{
    protected $_table;
    
    protected $_columns = array();
    
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        if($config->table) {
            $this->_table = $config->table;
        }
        
        if($config->columns) {
            $this->_columns = KConfig::unbox($config->columns);
        }
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'   => KCommand::PRIORITY_LOWEST,
            'auto_mixin' => true,
            'table'      => null,
            'columns'    => array()
        ));

        parent::_initialize($config);
    }
    
    public function getOrderingTable()
    {
        if(!$this->_table instanceof KDatabaseTableAbstract)
        {
            $table = $this->getMixer() instanceof KDatabaseTableAbstract ? $this : $this->getTable();
            $this->_table = $this->getService($this->_table, array('identity_column' => $table->getIdentityColumn()));
        }
        
        return $this->_table;
    }
    
    protected function _buildQuerySelect(KCommandContext $context)
    {
        $data      = $context->data;
        $table     = $context->getSubject();
        $id_column = $table->getIdentityColumn();
        
        $query = $this->getService('koowa:database.query.select')
            ->table(array('tbl' => $table->getName()))
            ->join(array('crumbs' => $table->getClosureTable()), 'crumbs.descendant_id = tbl.'.$id_column, 'INNER')
            ->join(array('closures' => $table->getClosureTable()), 'closures.descendant_id = tbl.'.$id_column, 'INNER')
            
            ->group('tbl.'.$id_column)
            ->having('COUNT(`crumbs`.`ancestor_id`) = :level')
            ->bind(array('id' => $data->id, 'level' => $data->level));
        
        if($data->level > 1) {
            $query->where('closures.ancestor_id = :ancestor_id')->bind(array('ancestor_id' => $data->getParentId()));
        }
        
        // Custom
        $query->where('tbl.pages_menu_id = :pages_menu_id')->bind(array('pages_menu_id' => $data->pages_menu_id));
        
        return $query;
    }
    
    protected function _buildQueryUpdate(KCommandContext $context)
    {
        $query = $this->getService('koowa:database.query.update');
    }
    
    protected function _beforeTableSelect(KCommandContext $context)
    {
        if(($query = $context->query) && $context->getSubject()->isClosurable())
        {
            $state          = $context->options->state;
            $table          = $context->getSubject();
            $id_column      = $table->getIdentityColumn();
            $ordering_table = $table->getOrderingTable()->getName();

            if(!$query->isCountQuery() && $state && !$state->isUnique() && in_array($state->sort, $this->_columns))
            {
                $query->columns(array('ordering_path' => 'GROUP_CONCAT(ordering_crumbs.'.$state->sort.' ORDER BY crumbs.level DESC  SEPARATOR \'/\')'))
                    ->join(array('ordering_crumbs' => $ordering_table), 'crumbs.ancestor_id = ordering_crumbs.'.$id_column, 'INNER');
                
                // Replace sort column with ordering path.
                foreach($query->order as &$order)
                {
                    if($order['column'] == $state->sort)
                    {
                        $order['column'] = 'ordering_path';
                        break;
                    }
                }
            }
            
            $query->columns(array('ordering' => 'orderings.custom'))
                ->join(array('orderings' => $ordering_table), 'tbl.'.$id_column.' = orderings.'.$id_column);
        }
    }
    
    protected function _afterTableInsert(KCommandContext $context)
    {
        $data = $context->data;
        if($data->getStatus() != KDatabase::STATUS_FAILED)
        {
            $table     = $context->getSubject();
            $id_column = $table->getIdentityColumn();
            
            // Insert empty row into ordering table.
            $row = $table->getOrderingTable()->getRow();
            $row->id = $data->id;
            $table->getOrderingTable()->insert($row);
            
            // Iterate through the columns and update values.
            foreach($this->_columns as $column)
            {
                if($column == 'custom')
                {
                    $query = $this->_buildQuerySelect($context)
                        ->columns('orderings.custom')
                        ->join(array('orderings' => $table->getOrderingTable()->getName()), 'tbl.'.$id_column.' = orderings.'.$id_column, 'INNER')
                        ->order('orderings.custom', 'DESC')
                        ->limit(1);
                    
                    $max = (int) $table->getDatabase()->select($query, KDatabase::FETCH_FIELD);
                    
                    $row = $table->getOrderingTable()->select($data->id, KDatabase::FETCH_ROW);
                    $row->custom = $max + 1;
                    $row->save();
                }
                else $this->_reorder($context, $column);
            }
        }
    }
    
    protected function _afterTableUpdate(KCommandContext $context)
    {
        $data = $context->data;
        if($data->getStatus() != KDatabase::STATUS_FAILED)
        {
            foreach($this->_columns as $column)
            {
                if($column == 'custom' && $data->order)
                {
                    $table          = $context->getSubject();
                    $id_column      = $table->getIdentityColumn();
                    $ordering_table = $table->getOrderingTable()->getBase();
                    
                    $old = (int) $data->ordering;
        			$new = $data->ordering + $data->order;
        			$new = $new <= 0 ? 1 : $new;
        			
        			$select = $this->_buildQuerySelect($context)
        			    ->columns('orderings.custom')
        			    ->columns('tbl.'.$id_column)
        			    ->join(array('orderings' => $ordering_table), 'tbl.'.$id_column.' = orderings.'.$id_column, 'INNER')
        			    ->order('index', 'ASC');
        			
        			if($data->order < 0)
        			{
        			    $select->columns(array('index' => 'IF(orderings.custom >= :new AND orderings.custom < :old, orderings.custom + 1, '.
                            'IF(orderings.'.$id_column.' = :id, :new, orderings.custom))'));
        			}
        			else
        			{
        			    $select->columns(array('index' => 'IF(orderings.custom > :old AND orderings.custom <= :new, orderings.custom - 1, '.
                            'IF(orderings.'.$id_column.' = :id, :new, orderings.custom))'));
        			}
        			
        			$select->bind(array('new' => $new, 'old' => $old, 'id' => $data->id));
        			
        			$update = $this->getService('koowa:database.query.update')
                        ->table(array('tbl' => $ordering_table))
                        ->join(array('ordering' => $select), 'tbl.'.$id_column.' = ordering.'.$id_column)
                        ->values('tbl.'.$column.' = ordering.index')
                        ->where('tbl.'.$id_column.' = ordering.'.$id_column);
                    
                    $table->getDatabase()->update($update);
                }
                else $this->_reorder($context, $column);
            }
        }
    }
    
    protected function _afterTableDelete(KCommandContext $context)
    {       
        if($context->data->getStatus() != KDatabase::STATUS_FAILED)
        {
            foreach($this->_columns as $column)
            {
                if($column == 'custom')
                {
                    $table          = $context->getSubject();
                    $id_column      = $table->getIdentityColumn();
                    $ordering_table = $table->getOrderingTable()->getBase();
                    
                    $table->getDatabase()->execute('SET @index := 0');
                    
                    $select = $this->_buildQuerySelect($context)
                        ->columns(array('index' => '@index := @index + 1')) 
        			    ->columns('orderings.custom')
        			    ->columns('tbl.'.$id_column)
        			    ->join(array('orderings' => $ordering_table), 'tbl.'.$id_column.' = orderings.'.$id_column, 'INNER')
        			    ->order('index', 'ASC');
                    
                    $update = $this->getService('koowa:database.query.update')
                        ->table(array('tbl' => $ordering_table))
                        ->join(array('ordering' => $select), 'tbl.'.$id_column.' = ordering.'.$id_column)
                        ->values('tbl.'.$column.' = ordering.index')
                        ->where('tbl.'.$id_column.' = ordering.'.$id_column);
                    
                    $table->getDatabase()->update($update);   			
                }
                else $this->_reorder($context, $column);
            }
        }
    }
    
    protected function _reorder(KCommandContext $context, $column)
    {
        $table     = $context->getSubject();
        $id_column = $table->getIdentityColumn();
        
        // Create a select query which returns an ordered list of rows.
        $table->getDatabase()->execute('SET @index := 0');
        
        $sub_select = $this->_buildQuerySelect($context)
            ->columns('tbl.'.$column)
            ->columns('tbl.'.$id_column)
            ->order('tbl.'.$column, 'ASC');
        
        $select = $this->getService('koowa:database.query.select')
            ->columns(array('index' => '@index := @index + 1'))
            ->columns('tbl.*')
            ->table(array('tbl' => $sub_select));
        
        // Create a multi-table update query which uses the select query as join table.
        $update = $this->getService('koowa:database.query.update')
            ->table(array('tbl' => $table->getOrderingTable()->getBase()))
            ->join(array('ordering' => $select), 'tbl.'.$id_column.' = ordering.'.$id_column)
            ->values('tbl.'.$column.' = ordering.index')
            ->where('tbl.'.$id_column.' = ordering.'.$id_column);
        
        $table->getDatabase()->update($update);
    }
}