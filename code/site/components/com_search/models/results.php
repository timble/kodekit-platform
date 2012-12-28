<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Model Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchModelResults extends KModelAbstract
{	
	/**
	 * The constructor.
	 * 
	 * @param KConfig $config An optional configuration object.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state
		    ->insert('term'     , 'com://site/search.filter.term')
		    ->insert('match'    , 'cmd', 'all')
		    ->insert('ordering' , 'cmd', 'newest')
		    ->insert('areas'    , 'cmd', null)
		    ->insert('limit'    , 'int', 20)
		    ->insert('offset'   , 'int');
	}
	
	/**
	 * Get the search results based in the current model state.
	 * 
	 * @return Array The search results.
	 */
	public function getRowset()
	{
		if(empty($this->_rowset))
		{	
			$state = $this->getState();
			$data  = array();
			
			if($state->term) 
			{
				//Import the search plugins
			    JPluginHelper::importPlugin('search');
			    
				$results = JDispatcher::getInstance()->trigger('onSearch', 
				    array(
					    $state->term, 
					    $state->match, 
					    $state->ordering, 
					    $state->areas
					 )
		        );
		        
		        foreach($results as $result) {
                    $data = array_merge($data, $result);
                }
			}
			
			$this->_total = count($data);
		    
			//Apply limit and offset
            if($this->_state->limit) {
		        $data = array_slice($data, $this->_state->offset, $this->_state->limit);
            }
            
			$this->_rowset = $this->getService('com://site/search.database.rowset.results', array('data' => $data));
		}
		
		return $this->_rowset;
	}
	
	/**
	 * Get the total amount of found items.
	 *
	 * @return  int 
	 */
	public function getTotal()
	{
		// No caching. Recalculate the total.
	    if(empty($this->_rowset)) {
			$this->getRowset();
		}
		
		return $this->_total;
	}
}