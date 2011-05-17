<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
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
class ComSearchModelSearch extends KModelAbstract
{	
	/**
	 * The search results.
	 * 
	 * @var array An array containing row objects (sdtClass) as returned from the search plugins.
	 */
	protected $_search_results = array();
	
	/**
	 * The search areas.
	 * 
	 * @var array Associative array containing the search areas.
	 */
	protected $_search_areas = array();
	
	/**
	 * The constructor.
	 * 
	 * @param KConfig $config An optional configuration object.
	 */
	public function __construct(KConfig $config = null)
	{
		if(!$config) {
			$config = new KConfig();
		}
		
		parent::__construct($config);
		
		$state = $this->getState();
		$state->insert('keyword', 'string');
		$state->insert('match', 'cmd', 'all');
		$state->insert('ordering', 'cmd', 'newest');
		$state->insert('areas', 'cmd', null);
		$state->insert('limit', 'int', 20);
		$state->insert('offset', 'int');
	}
	
	/**
	 * Get the search results based in the current model state.
	 * 
	 * @return Array The search results.
	 */
	public function getSearchResults()
	{
		if(empty($this->_search_results)) 
		{	
			$state = $this->getState();
			$results = array();
			$search_results = array();
			
			if($state->keyword) 
			{
				JPluginHelper::importPlugin('search');
				$dispatcher = & JDispatcher::getInstance();
				$results = $dispatcher->trigger('onSearch', array(
					$state->keyword, 
					$state->match, 
					$state->ordering, 
					$state->areas));
			}
			
			foreach($results as $result) {
				$search_results = array_merge($search_results, $result);
			}
			
			$this->_total = count($search_results);
			
			$this->_search_results = ($state->limit) ? array_splice($search_results, $state->offset, $state->limit) : $search_results;
		}
		
		return $this->_search_results;
	}
	
	/**
	 * Get the search areas as provided by the search plugins.
	 * 
	 * @return Array The search areas.
	 */
	public function getSearchAreas()
	{
		if(empty($this->_search_areas)) 
		{	
			$search_areas = array();
			
			JPluginHelper::importPlugin('search');
			$dispatcher = & JDispatcher::getInstance();
			$results = $dispatcher->trigger('onSearchAreas');
			
			foreach($results as $search_area) {
				$search_areas = array_merge($search_areas, $search_area);
			}
			
			$this->_search_areas = $search_areas;
		}
		
		return $this->_search_areas;
	}
	
	/**
	 * Get the total amount of found items.
	 *
	 * @return  int 
	 */
	public function getTotal()
	{
		// No caching. Recalculate the total.
	    if(empty($this->_search_results)) {
			$this->getSearchResults();
		}
		
		return $this->_total;
	}
	
	/**
	 * Reset all cached data and reset the model state to it's default state.
	 * 
	 * @param   boolean If TRUE use defaults when resetting. Default is TRUE
	 * @return KModelAbstract 
	 */
	public function reset($default = true)
	{
		$this->_search_areas = array();
		$this->_search_results = array();
		
		parent::reset($default);
		
		return $this;
	}
}