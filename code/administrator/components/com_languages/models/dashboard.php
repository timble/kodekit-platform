<?php

class ComLanguagesModelDashboard extends KModelAbstract
{
    /**
     * Returns a list of newly added items
     *
     * @return array
     */
    public function getAdditions()
    {
        $db = JFactory::getDBO();
    	$query = $db->getQuery()
        	->select(array('n.*', 'u.name AS created_by_name'))
        	->from('nooku_nodes AS n')
        	->join('LEFT', 'users AS u', 'u.id = n.created_by')
        	->where('n.original', '=', '1')
        	->where('n.deleted' , '=', '0')
        	->order('created', 'DESC');
        
        $db->select($query, 0, 5);
        $list = $db->loadObjectList();
        return $list;
    }

    /**
     * Returns a list of recently changed (translated) items
     * 
     * @return array
     */
    public function getChanges()
    {
        $db = JFactory::getDBO();
        
    	$query = $db->getQuery()
        	->select(array('n.*', 'u.name AS modified_by_name'))
        	->from('nooku_nodes AS n')
        	->join('LEFT', 'users AS u', 'u.id = n.modified_by')
        	->where('n.deleted' , '=', '0')
        	->where('n.original' , '=', '0')
        	->where('n.status' , '!=', Nooku::STATUS_MISSING)
        	->order('n.modified', 'DESC');
        
        $db->select($query, 0, 5);
        $list = $db->loadObjectList();

        return $list;
    }

    /**
     * Returns a list of recently deleted items
     *
     * @return array
     */
    public function getDeletes()
    {
		$db = JFactory::getDBO();
		
    	$query = $db->getQuery()
        	->select(array('n.*', 'u.name AS modified_by_name'))
        	->from('nooku_nodes AS n')
        	->join('LEFT', 'users AS u', 'u.id = n.modified_by')
        	->where('n.deleted' , '=', '1')
        	->order('n.modified', 'DESC');
    	
        $db->select($query, 0, 5);
        $list = $db->loadObjectList();

        return $list;
    }
}