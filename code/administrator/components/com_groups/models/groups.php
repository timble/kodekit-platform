<?php
class ComGroupsModelGroups extends KModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $parent = $this->getTable()->select(array('name' => 'USERS', 'value' => 'USERS'), KDatabase::FETCH_ROW);
        $this->_state->insert('parent', 'int', $parent->id);
        
        $this->_state->insert('core', 'boolean');
    }
    
    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
    	parent::_buildQueryColumns($query);
    	
    	if(!$this->_state->isUnique()) {
    	    $query->select('COUNT(`parent`.`id`) - 3 AS depth');
    	}
    }
    
    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
    	if(!$this->_state->isUnique()) {
    	    $name = $this->getTable()->getName();
            $query->join('LEFT', $name.' AS parent', 'tbl.lft BETWEEN parent.lft AND parent.rgt');
    	}
    }
    
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);

        if(!$this->_state->isUnique()) {
	        if($this->_state->parent) {
	            $parent = $this->getTable()->select($this->_state->parent, KDatabase::FETCH_ROW);
	
	            $query->where('tbl.lft', '>', $parent->lft, 'AND')
	                ->where('tbl.rgt', '<', $parent->rgt);
	        }
	        
	        if(!is_null($this->_state->core)) {
	        	$query->where('tbl.id', $this->_state->core ? '<=' : '>', 30);
	        }
        }
    }
    
    protected function _buildQueryGroup(KDatabaseQuery $query)
    {
    	if(!$this->_state->isUnique()) {
    	   $query->group('tbl.id');
    	}
    }
    
    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
    	if(!$this->_state->isUnique()) {
            $query->order('tbl.lft', 'ASC');
    	}
    }
}