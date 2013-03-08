<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Cache Group Model
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class ComCacheModelGroups extends Framework\ModelAbstract
{	
    public function __construct(Framework\Config $config)
	{
	    parent::__construct($config);
		
		$this->_state
		    ->insert('name'     , 'cmd')
		    ->insert('site'     , 'cmd')
		 	->insert('limit'    , 'int')
            ->insert('offset'   , 'int')
            ->insert('sort'     , 'cmd')
            ->insert('direction', 'word', 'asc')
            ->insert('search'   , 'string');
	}
	
    public function getRowset()
    {  
        if(!isset($this->_rowset))
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
		      
		    $this->_rowset = $this->getService('com://admin/cache.database.rowset.groups', array('data' => $data));
        }
        
        return $this->_rowset;
    }

    public function getTotal()
    {
        if(!isset($this->_total)) {
            $this->getRowset();
        }
        
        return $this->_total;
    }
    
    protected function _getData()
    {
        $data = array();
        $keys = $this->getService('com://admin/cache.model.items')->site($this->_state->site)->getRowset();
       
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