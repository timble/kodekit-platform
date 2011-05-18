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
 * Select Template Helper Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchTemplateHelperSelect extends KTemplateHelperSelect
{	
	/**
	 * Search areas checklist helper.
	 *
	 * @param Array $config An optional configuration object
	 */
	public function searchareas($config = array())
	{	
		$config = new KConfig($config);
		
        $areas = array();
		
		//Import the search plugins
		JPluginHelper::importPlugin('search');
		$results = JDispatcher::getInstance()->trigger('onSearchAreas');
			    
		foreach($results as $result) {
		    $areas = array_merge($areas, $result);
        }
			
		// Get and format the search areas
		foreach($areas as $value => $title) 
		{
			$search_area = new stdClass();
			$search_area->value = $value;
			$search_area->title = $title;
			$search_areas[] = $search_area;
		}
		
		$config->append(array(
		   'list' => $search_areas,
		   'name' => 'areas', 
		    'key' => 'value'
		))->append(array(
			'selected' => $config->{$config->name})
	    );
		
		return parent::checklist($config);
	
	}
	
	/**
	 * Search phrase radiolist helper.
	 *
	 * @param Array $config An optional configuration object
	 */
	public function searchphrase($config = array())
	{	
		$config = new KConfig($config);
		
		$search_phrases = array();
		
		foreach(array('all' => 'All words', 'any' => 'Any words', 'exact' => 'Exact phrase') as $value => $title) 
		{
			$search_phrase = new stdClass();
			$search_phrase->value = $value;
			$search_phrase->title = $title;
			$search_phrases[] = $search_phrase;
		}
		
		$config->append(array(
			'list' => $search_phrases, 
			'name' => 'match', 
			'key' => 'value', 
			'translate' => true))
			->append(array('selected' => $config->{$config->name}));
		
		$html = parent::radiolist($config);
		
		// Romove unwanted linebreaks.
		return str_replace('<br />', '', $html);
	}
	
	/**
	 * Ordering option list helper.
	 *
	 * @param Array $config An optional configuration object
	 */
	public function ordering($config = array())
	{
		$config = new KConfig($config);
		
		$options = array();
		$orders = array(
			'newest' => 'Newest first', 
			'oldest' => 'Oldest first', 
			'popular' => 'Most popular', 
			'alpha' => 'Alphabetical', 
			'category' => 'Section/Category');
		
		foreach($orders as $value => $title) {
			$options[] = $this->option(array('value' => $value, 'text' => $title));
		}
		
		$config->append(array('options' => $options, 'name' => 'ordering', 'translate' => true))
			   ->append(array('selected' => $config->{$config->name}));
		
		return parent::optionlist($config);
	}
}
