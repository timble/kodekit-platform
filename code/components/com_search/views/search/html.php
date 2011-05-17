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
 * Search Html View Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchViewSearchHtml extends ComDefaultViewHtml
{
	
	/**
	 * Initializes the config for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param     object     An optional KConfig object with configuration options
	 * @return  void 
	 */
	protected function _initialize(KConfig $config)
	{
		// Force model, default layout and manual data assignation.
		$config->append(array(
			'model'          => 'site::com.search.model.search', 
			'layout_default' => 'form', 
			'auto_assign'    => false
		));
		
		parent::_initialize($config);
	}
	
	/**
	 * Return the views output
	 * 
	 * This function will auto assign the model data to the view if the auto_assign
	 * property is set to TRUE.
	 *
	 * @return string     The output of the view
	 */
	public function display()
	{
		$params = KFactory::get('lib.joomla.application')->getParams();
		$model = $this->getModel();
		$state = $model->getState();
		
		foreach($results = $model->getSearchResults() as $result) 
		{	
			if($state->match == 'exact') 
			{
				$words = array($state->keyword);
				$needle = $state->keyword;
			} 
			else 
			{
				$words = preg_split('/\s+/u', $state->keyword);
				$needle = $words[0];
			}
			
			// Output filtering
			$result->text = SearchHelper::prepareSearchContent($result->text, 200, $needle);
			
			// Highlight search words
			$words = array_unique($words);
			$hlregex = '#(';
			$x = 0;
			
			foreach($words as $k => $hlword) 
			{
				$hlregex .= ($x == 0 ? '' : '|');
				$hlregex .= preg_quote($hlword, '#');
				$x++;
			}
			
			$hlregex .= ')#iu';
			$result->text = preg_replace($hlregex, '<span class="highlight">\0</span>', $result->text);
			
			// Pretty timezone aware date (if any)
			if($result->created) {
				$result->created = JHTML::Date($result->created);
			} else {
				$result->created = '';
			}
		}
		
		$this->assign('item_id', KRequest::get('get.Itemid', 'int'))
			 ->assign('offset', $state->offset)
			 ->assign('results', $results)
			 ->assign('params', $params)
			 ->assign('keyword', $state->keyword)
			 ->assign('search_results', $model->getSearchResults())
			 ->assign('total', $model->getTotal())
			 ->assign('search_areas', $model->getSearchAreas());
		
		return parent::display();
	}

}