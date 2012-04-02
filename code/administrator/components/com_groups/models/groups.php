<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Groups Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 */

class ComGroupsModelGroups extends KModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $parent = $this->getTable()->select(array('name' => 'USERS', 'value' => 'USERS'), KDatabase::FETCH_ROW);
        
        $this->getState()
            ->insert('parent', 'int', $parent->id)
            ->insert('core', 'boolean');
    }
    
    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
    	parent::_buildQueryColumns($query);
    	
    	if(!$this->getState()->isUnique()) {
    	    $query->columns('COUNT(`parent`.`id`) - 3 AS depth');
    	}
    }
    
    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
    	if(!$this->getState()->isUnique()) 
    	{
    	    $name = $this->getTable()->getName();
            $query->join(array('parent' => $name), 'tbl.lft BETWEEN parent.lft AND parent.rgt');
    	}
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();
        
        if(!$state->isUnique()) 
        {
	        if($state->parent) 
	        {
	            $parent = $this->getTable()->select($state->parent, KDatabase::FETCH_ROW);
	
	            $query->where('tbl.lft BETWEEN :parent_lft AND :parent_rgt')
	                ->bind(array('parent_lft' => $parent->lft, 'parent_rgt' => $parent->rgt));
	        }
	        
	        if(!is_null($state->core)) {
	        	$query->where('tbl.id '.$this->getState()->core ? '<=' : '>'.' 30');
	        }
        }
    }
    
    protected function _buildQueryGroup(KDatabaseQuerySelect $query)
    {
    	if(!$this->getState()->isUnique()) {
    	   $query->group('tbl.id');
    	}
    }
    
    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
    	if(!$this->getState()->isUnique()) {
            $query->order('tbl.lft', 'ASC');
    	}
    }
}