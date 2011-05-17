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
		
		if($state->term) 
		{	
			$term = $state->term;
			
			// Apply keyword length limitations
			if($this->_limitSearchTerm($term)) {
				KFactory::get('lib.joomla.application')->enqueueMessage(JText::_('SEARCH_MESSAGE'), 'notice');
			}
			
			// Sanatise the keyword by removing the ignored words from it.
			if($this->_sanitizeSearchTerm($term, $state->match)) {
				KFactory::get('lib.joomla.application')->enqueueMessage(KRequest::referrer(), JText::_('IGNOREKEYWORD'), 'notice');
			}
			
			$state->term = $term;
		}
	}
	
    public function _sanitizeSearchTerm(&$term, $searchphrase)
	{
		$ignored = false;

		$lang = KFactory::get('lib.joomla.language');

		$search_ignore	= array();
		$tag			= $lang->getTag();
		$ignoreFile		= $lang->getLanguagePath().DS.$tag.DS.$tag.'.ignore.php';
		if (file_exists($ignoreFile)) {
			include $ignoreFile;
		}

	 	// check for words to ignore
		$aterms = explode( ' ', JString::strtolower( $term ) );

		// first case is single ignored word
		if ( count( $aterms ) == 1 && in_array( JString::strtolower( $term ), $search_ignore ) ) {
			$ignored = true;
		}

		// filter out search terms that are too small
		foreach( $aterms AS $aterm ) 
		{
			if (JString::strlen( $aterm ) < 3) {
				$search_ignore[] = $aterm;
			}
		}

		// next is to remove ignored words from type 'all' or 'any' (not exact) searches with multiple words
		if ( count( $aterms ) > 1 && $searchphrase != 'exact' ) 
		{
			$pruned = array_diff( $aterms, $search_ignore );
			$term   = implode( ' ', $pruned );
		}

		return $ignored;
	}
	
    public function _limitSearchTerm(&$term)
	{
		$restriction = false;

		// limit searchword to 20 characters
		if ( JString::strlen( $term ) > 20 ) 
		{
			$term 	= JString::substr( $term, 0, 19 );
			$restriction 	= true;
		}

		// searchword must contain a minimum of 3 characters
		if ( $term && JString::strlen( $term ) < 3 ) 
		{
			$searchword 	= '';
			$restriction 	= true;
		}

		return $restriction;
	}
}