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
 * Search Filter Behavior Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchControllerBehaviorFilter extends KControllerBehaviorAbstract
{
	/**
	 * User input filtering.
	 * 
	 * @param KCommandContext $context
	 */
	protected function _beforeBrowse(KCommandContext $context)
	{	
		$state = $context->caller->getModel()->getState();
		
		if($state->keyword) 
		{	
			require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'search.php');
			
			$keyword = $state->keyword;
			
			// Apply keyword length limitations
			if(SearchHelper::limitSearchWord($keyword)) {
				KFactory::get('lib.joomla.application')->enqueueMessage(JText::_('SEARCH_MESSAGE'), 'notice');
			}
			
			// Sanatise the keyword by removing the ignored words from it.
			if(SearchHelper::santiseSearchWord($keyword, $state->match)) {
				KFactory::get('lib.joomla.application')->enqueueMessage(KRequest::referrer(), JText::_('IGNOREKEYWORD'), 'notice');
			}
			
			$state->keyword = $keyword;
		}
	}
}