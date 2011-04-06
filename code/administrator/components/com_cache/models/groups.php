<?php
/**
 * @version     $Id: sections.php 592 2011-03-16 00:30:35Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Cache Group Model
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class ComCacheModelGroups extends KModelAbstract
{	
    public function __construct(KConfig $config)
	{
	    parent::__construct($config);
		
		$this->_state
		    ->insert('name'     , 'cmd')
		    ->insert('site'     , 'cmd', 'default')
		 	->insert('limit'    , 'int')
            ->insert('offset'   , 'int')
            ->insert('sort'     , 'cmd')
            ->insert('direction', 'word', 'asc')
            ->insert('search'   , 'string');
	}
	
    public function getList()
    {  
        if(!isset($this->_list))
        {
            $data = $this->_getData();
            
            //Apply state information
            if($this->_state->name) {    
		       $data = array_intersect_key($data, array_flip((array)$this->_state->name));
		    }
		    
            foreach($data as $key => $value)
	        {     
	            if($this->_state->search)
	            { 
	                if($value['name'] != $this->_state->search) {
		               unset($data[$key]);
		            }
	            }
            } 

		    //Set the total
		    $this->_total = count($data);
		    
		    //Apply limit and offset
            if($this->_state->limit) {
		        $data = array_slice($data, $this->_state->offset, $this->_state->limit);
            }
		      
		    $this->_list = KFactory::tmp('admin::com.cache.database.rowset.groups', array('data' => $data));
        }
        
        return $this->_list;
    }

    public function getTotal()
    {
        if(!isset($this->_total)) {
            $this->getList();
        }
        
        return $this->_total;
    }
   
    public function getColumn($column)
    {   
        if (!isset($this->_column[$column])) 
        {   
            $result = array();
            $data   = $this->_getData();
		    
	        foreach($data as $key => $value) 
		    {
	            $value = (object) $value;
	            
		        if(isset ($value->{$column})) {
			        $result[$key] = $data[$key];
		        }
		    }
		    
		    $this->_column[$column] = KFactory::tmp('admin::com.cache.database.rowset.groups', array('data' => $data));
        }
            
        return $this->_column[$column];
    }
    
    protected function _getData()
    {
        $data = array();
        $keys = KFactory::tmp('admin::com.cache.model.keys')->site($this->_state->site)->getList();
       
        foreach($keys as $key) 
        {
            if(!isset($data[$key->group])) 
            {
                $data[$key->group] = array(
			   		'name'  => $key->group,
                    'site'  => $key->site,
			      	'count' => 0,
			       	'size'  => 0,
			     );
             }
			      
	        $data[$key->group]['size'] += $key->size;
            $data[$key->group]['count']++;
         }
         
         return $data;
    }
}