<?php
/**
 * @version		$Id: filter.php 1309 2011-05-17 16:47:27Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Term Filter Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchFilterTerm extends KFilterString
{
    /**
     * Sanitize a value
     *
     * @param   mixed   Value to be sanitized
     * @return  string
     */
    protected function _sanitize($value)
    {
        $value = parent::_sanitize($value);
        
        // Apply length limitations
		$value = $this->_limitTerm($value);
			
		//Rmove ignored words
		$value = $this->_sanitizeTerm($value);
		
        return $value;
    }
	
    public function _sanitizeTerm($term, $searchphrase = 'exact')
	{
		$lang = KFactory::get('lib.joomla.language');

		$search_ignore	= array();
		$tag			= $lang->getTag();
		$ignoreFile		= $lang->getLanguagePath().DS.$tag.DS.$tag.'.ignore.php';
		
		if (file_exists($ignoreFile)) {
			include $ignoreFile;
		}

	 	//Check for words to ignore
		$aterms = explode(' ', JString::strtolower( $term ) );

		//First case is single ignored word
		if(count( $aterms ) == 1 && in_array( JString::strtolower( $term ), $search_ignore ) ) {
			$ignored = true;
		}

		//Filter out search terms that are too small
		foreach($aterms as $aterm ) 
		{
			if(JString::strlen( $aterm ) < 3) {
				$search_ignore[] = $aterm;
			}
		}

		//Remove ignored words from type 'all' or 'any' (not exact) searches with multiple words
		if(count( $aterms ) > 1 && $searchphrase != 'exact' ) 
		{
			$pruned = array_diff( $aterms, $search_ignore );
			$term   = implode( ' ', $pruned );
		}

		return $term;
	}
	
    public function _limitTerm($term)
	{
		// Limit term to 20 characters
		if( JString::strlen( $term ) > 20 ) {
			$term 	= JString::substr( $term, 0, 19 );
		}

		// Term must contain a minimum of 3 characters
		if( $term && JString::strlen( $term ) < 3 ) {
			$term = '';
		}

		return $term;
	}
}