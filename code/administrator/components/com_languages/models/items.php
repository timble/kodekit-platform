<?php
class ComLanguagesModelItems extends KModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->getState()
            ->insert('table', 'cmd')
            ->insert('iso_code', 'com://admin/languages.filter.iso')
            ->insert('status', 'int')
            ->insert('deleted', 'boolean', false);
    }
    
 	/**
     * Generic counter for nodes by parameters
     *
     * @param array		Conditions
     */
	public function count($conds)
    {
        $nooku   = KFactory::get('admin::com.nooku.model.nooku');
        $primary = $nooku->getPrimaryLanguage();
        
        $query = $this->_db->getQuery()
        	->count()
        	->from('nooku_nodes AS n');
        
       foreach($conds as $cond) {
        	$query->where($cond[0], $cond[1], $cond[2]);
        }
      
        $this->_db->select($query);
        return $this->_db->loadResult();
    }
	
	public function getFilters()
    {
        $filters                = parent::getFilters();
        $filters['table_name']  = $this->getState('filter_table_name');
        $filters['iso_code']    = $this->getState('filter_iso_code');
        $filters['status']      = $this->getState('filter_status');
        $filters['translator']  = $this->getState('filter_translator');
        return $filters;
    }
    
    public function getDefaultState()
    {
        $app 	= KFactory::get('lib.joomla.application');
    	$ns		= $this->getClassName('prefix').'.'.$this->getClassName('suffix');

    	$state = parent::getDefaultState();
      	$state['filter_table_name'] = $app->getUserStateFromRequest($ns.'table_name', 'filter_table_name', '', 'cmd');
        $state['filter_iso_code']   = $app->getUserStateFromRequest($ns.'iso_code',   'filter_iso_code',   '', 'cmd');
        $state['filter_status']     = $app->getUserStateFromRequest($ns.'status',     'filter_status',     '', 'int');
        $state['filter_translator'] = $app->getUserStateFromRequest($ns.'translator', 'filter_translator', 0,  'int');
        
        return $state;
    }

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
    	$query->columns(array(
    		 'modified_by_name' => 'modifiers.name',
    	     'created_by_name' => 'creators.name'
    	));
    }
    
 	protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        $query->join(array('modifiers' => 'users'), 'modifiers.id = tbl.modified_by');
        $query->join(array('creators' => 'users'), 'creators.id = tbl.created_by');
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();
        
        if($state->search) {
            $query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        }
        
        if($state->table) {
            $query->where('tbl.table = :table')->bind(array('table' => $state->table));
        }
        
        if($state->iso_code) {
            $query->where('tbl.iso_code = :iso_code')->bind(array('iso_code' => $state->iso_code));
        }
        
        if(is_int($state->status)) {
            $query->where('tbl.status = :status')->bind(array('status' => $state->status));
        }
       	
        if(is_bool($state->deleted)) {
            $query->where('tbl.deleted = :deleted')->bind(array('deleted' => (int) $state->deleted));
        }
    }
    
    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
        if($this->sort == 'table')
        {
            $direction = strtoupper($this->direction);
            
            $query->order('tbl.table', $direction);
      		$query->order('tbl.row', $direction);
      		$query->order('tbl.original', 'DESC');
        }
        //else parent::_buildQueryWhere($query);
        /*//$nooku  = KFactory::get('admin::com.nooku.model.nooku');

      	// Assemble the clause pieces
       	$order      = $this->getState('order', 'tbl.table_name');
      	$direction  = strtoupper($this->getState('direction', 'ASC'));
      	
      	// Assemble the clause
        switch($sort) 
        {
          	case 'table':
          		$query->order('tbl.table_name', $direction);
          		$query->order('tbl.row_id', $direction);
          		$query->order('tbl.original', 'DESC');
              	break;
            default:
               if($order) {
            		$query->order($order, $direction);
               }
               break;
        }*/
    }
}