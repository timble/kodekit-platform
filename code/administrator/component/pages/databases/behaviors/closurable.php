<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Closurable Database Behavior Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesDatabaseBehaviorClosurable extends KDatabaseBehaviorAbstract
{
    /**
     * The closure table name
     * 
     * @var string
     */
    protected $_table;
    
    /**
     * Constructor
     *
     * @param  object   A KConfig object with configuration options.
     * @return void
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        if($config->table) {
            $this->_table = $config->table;
        }
    }
    
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  object   A KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'   => KCommand::PRIORITY_HIGH,
            'auto_mixin' => true,
            'table'      => null
        ));

        parent::_initialize($config);
    }
    
    /**
     * Get parent id
     * 
     * @return int|null The parent id if row has a parent, null otherwise.
     */
    public function getParentId()
    {
        $id = $this->level > 1 ? end(array_values($this->getParentIds())) : null;
        
        return $id; 
    }
    
    /**
     * Get parent ids
     * 
     * @return array The parent ids.
     */
    public function getParentIds()
    {
        $ids = array_map('intval', explode('/', $this->path));
        array_pop($ids);
        
        return $ids;
    }
    
    /**
     * Get closure table object
     * 
     * @return KDatabaseTableAbstract
     */
    public function getClosureTable()
    {
        if(!$this->_table instanceof KDatabaseTableInterface) {
            $this->_table = $this->getService($this->_table);
        }

        return $this->_table;
    }
    
    /**
     * Get the siblings of the row
     *
     * @return KDatabaseRowsetAbstract
     */
    public function getSiblings()
    {
        
        $table = $this->getTable();
        $query = $this->getService('koowa:database.query.select')
            ->columns('tbl.*')
            ->join(array('closures' => $this->getClosureTable()->getName()), 'closures.descendant_id = tbl.'.$table->getIdentityColumn(), 'INNER')
            ->where('tbl.'.$table->getIdentityColumn().' <> :id')
            ->having('COUNT(`crumbs`.`ancestor_id`) = :level')
            ->bind(array('id' => $this->id, 'level' => $this->level));

        if($this->level > 1) {
            $query->where('closures.ancestor_id = :ancestor_id')->bind(array('ancestor_id' => $this->getParentId()));
        }
        
        $result = $table->select($query);

        return $result;
    }
    
    /**
     * Get the first ancestor of the row
     *
     * @return KDatabaseRowAbstract Parent row or empty if there is no parent.
     */
    public function getParent()
    {
        $table = $this->getTable();
        
        if($this->level > 1) {
            $result = $table->select(end(array_values($this->getParentIds())), KDatabase::FETCH_ROW);
        } else {
            $result = $table->getRow();
        }
        
        return $result;
    }
    
    /**
     * Get ancestors of the row
     *
     * @return KDatabaseRowsetAbstract A rowset containing all ancestors.
     */
    public function getAncestors()
    {
        $table = $this->getTable();
        
        if($this->level > 1)
        {
            $query = $this->getService('koowa:database.query.select')
                ->columns('tbl.*')
                ->join(array('closures' => $this->getClosureTable()->getName()), 'closures.ancestor_id = tbl.'.$table->getIdentityColumn(), 'INNER')
                ->where('closures.descendant_id = :id')
                ->where('closures.ancestor_id <> :id')
                ->bind(array('id' => $this->id));

            $result = $table->select($query);
        }
        else $result = $table->getRowset();
        
        return $result;
    }
    
    /**
     * Get ancestors of the row
     *
     * @return KDatabaseRowsetAbstract A rowset containing all ancestors.
     */
    public function getDescendants()
    {
        $table = $this->getTable();
        $query = $this->getService('koowa:database.query.select')
            ->columns('tbl.*')
            ->join(array('closures' => $this->getClosureTable()->getName()), 'closures.descendant_id = tbl.'.$table->getIdentityColumn(), 'INNER')
            ->where('closures.ancestor_id = :id')
            ->where('tbl.'.$table->getIdentityColumn().' <> :id')
            ->bind(array('id' => $this->id));
        
        $result = $table->select($query);
        
        return $result;
    }
    
    /**
     * Checks if the current row is a descendant of the given one
     *
     * @param  KDatabaseRowAbstract $row
     * @return boolean
     */
    public function isDescendantOf(KDatabaseRowAbstract $row)
    {
        return in_array($row->id, $this->getParentIds());
    }
    
    /**
     * Checks if the current row is an ancestor of the given one
     *
     * @param  KDatabaseRowAbstract $row
     * @return boolean
     */
    public function isAncestorOf(KDatabaseRowAbstract $row)
    {
        return in_array($this->id, $row->getParentIds());
    }
    
    /**
     * Add level and path columns to the query
     * 
     * @param  KCommandContext $context A command context object.
     * @return boolean True on success, false on failure.
     */
    protected function _beforeTableSelect(KCommandContext $context)
    {
        if($query = $context->query)
        {
            $state         = $context->options->state;
            $id_column     = $context->getSubject()->getIdentityColumn();
            $closure_table = $context->getSubject()->getClosureTable();

            $query->columns(array('level' => 'COUNT(crumbs.ancestor_id)'))
                ->columns(array('path' => 'GROUP_CONCAT(crumbs.ancestor_id ORDER BY crumbs.level DESC SEPARATOR \'/\')'))
                ->join(array('crumbs' => $closure_table->getName()), 'crumbs.descendant_id = tbl.'.$id_column, 'INNER')
                ->group('tbl.'.$id_column);
            
            if($state)
            {
                if($state->parent)
                {
                    $query->join(array('closures' => $closure_table->getName()), 'closures.descendant_id = tbl.'.$id_column)
                        ->where('closures.ancestor_id = :parent')
                        ->where('tbl.'.$id_column.' <> :parent')
                        ->bind(array('parent' => $state->parent));
                }

                if($state->level) {
                    $query->having('level = :level')->bind(array('level' => $state->level));
                }
            }
        }
    }
    
    /**
     * Insert relations into the relation table
     * 
     * @param  KCommandContext $context A command context object.
     * @return boolean True on success, false on failure.
     */
    protected function _afterTableInsert(KCommandContext $context)
    {
        if($context->affected !== false)
        {
            $data  = $context->data;
            $table = $context->getSubject();
            
            // Insert the self relation.
            $query = $this->getService('koowa:database.query.insert')
                ->table($this->getClosureTable()->getBase())
                ->columns(array('ancestor_id', 'descendant_id', 'level'))
                ->values(array($data->id, $data->id, 0));
            
            $table->getAdapter()->insert($query);

            // Set path and level for the current row.
            if($data->parent_id)
            {
                $parent = $table->select($data->parent_id, KDatabase::FETCH_ROW);
                $data->setData(array('level' => $parent->level + 1, 'path' => $parent->path.'/'.$data->id), false);

                // Insert child relations.
                $select = $this->getService('koowa:database.query.select')
                    ->columns(array('ancestor_id', $data->id, 'level + 1'))
                    ->table($this->getClosureTable()->getName())
                    ->where('descendant_id = :descendant_id')
                    ->bind(array('descendant_id' => $parent->id));
                
                $query = $this->getService('koowa:database.query.insert')
                    ->table($this->getClosureTable()->getBase())
                    ->columns(array('ancestor_id', 'descendant_id', 'level'))
                    ->values($select);
                
                $table->getAdapter()->insert($query);
            }
            else $data->setData(array('level' => 1, 'path' => $data->id), false);
        }
    }
    
    /**
     * Update relations if parent has changed
     * 
     * @link http://www.mysqlperformanceblog.com/2011/02/14/moving-subtrees-in-closure-table/
     * 
     * @param  KCommandContext $context A command context object.
     * @return boolean True on success, false on failure. 
     */
    protected function _afterTableUpdate(KCommandContext $context)
    {
        if($context->affected !== false)
        {
            $row = $context->data;
            if((int) $row->parent_id != (int) $row->getParentId())
            {
                $table = $row->getTable();
                
                if($row->parent_id)
                {
                    $parent = $table->select((int) $row->parent_id, KDatabase::FETCH_ROW);
                    if($parent->isDescendantOf($row))
                    {
                        $this->setStatusMessage(JText::_('You cannot move a node under one of its descendants'));
                        $this->setStatus(KDatabase::STATUS_FAILED);
                        return false;
                    }
                }
                
                // Delete the outdated paths for the old location.
                $query = $this->getService('koowa:database.query.delete')
                    ->table(array('a' => $this->getClosureTable()->getBase()))
                    ->join(array('d' => $this->getClosureTable()->getBase()), 'a.descendant_id = d.descendant_id', 'INNER')
                    ->join(array('x' => $this->getClosureTable()->getBase()), 'x.ancestor_id = d.ancestor_id AND x.descendant_id = a.ancestor_id')
                    ->where('d.ancestor_id = :ancestor_id')
                    ->where('x.ancestor_id IS NULL')
                    ->bind(array('ancestor_id' => $row->id));

                $table->getAdapter()->delete($query);

                // Insert the subtree under its new location.
                $select = $this->getService('koowa:database.query.select')
                    ->columns(array('supertree.ancestor_id', 'subtree.descendant_id', 'supertree.level + subtree.level + 1'))
                    ->table(array('supertree' => $this->getClosureTable()->getName()))
                    ->join(array('subtree' => $this->getClosureTable()->getName()), null, 'INNER')
                    ->where('subtree.ancestor_id = :ancestor_id')
                    ->where('supertree.descendant_id = :descendant_id')
                    ->bind(array('ancestor_id' => $row->id, 'descendant_id' => (int) $row->parent_id));

                $query = $this->getService('koowa:database.query.insert')
                    ->table($this->getClosureTable()->getBase())
                    ->columns(array('ancestor_id', 'descendant_id', 'level'))
                    ->values($select);

                $table->getAdapter()->insert($query);
                
                $row->path = ($row->parent_id ? $parent->path.'/' : '').$row->id;
            }
        }
        
        return true;
    }
    
    /**
     * Delete the row and its children
     *
     * @param  KCommandContext $context A command context object.
     * @return boolean True on success, false on failure. 
     */
    protected function _beforeTableDelete(KCommandContext $context)
    {
        $table         = $context->getSubject();
        $id_column     = $table->getIdentityColumn();

        $select = $this->getService('koowa:database.query.select')
            ->columns('descendant_id')
            ->table($table->getClosureTable()->getName())
            ->where('ancestor_id = :id')
            ->where('descendant_id <> :id')
            ->bind(array('id' => $context->data->id));
        
        $query = $this->getService('koowa:database.query.delete')
            ->table($table->getBase())
            ->where($id_column.' IN :id')
            ->bind(array('id' => $select));
        
        $table->getAdapter()->delete($query);
        
        return true;
    }
}
