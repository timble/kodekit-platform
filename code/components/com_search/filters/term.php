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
        
        $search_ignore = array();
        
        // Limit term to 20 characters
		if( JString::strlen( $value ) > 20 ) {
			$value = JString::substr( $value, 0, 19 );
		}

		// Term must contain a minimum of 3 characters
		if( $value && JString::strlen( $value ) < 3 ) {
			$value = '';
		}
		
        //Filter out search terms that are too small
		$words = explode(' ', JString::strtolower( $value ) );

		foreach($words as $word ) 
		{
			if(JString::strlen( $word ) < 3) {
				$search_ignore[] = $word;
			}
		}

		if(count( $words ) > 1 ) 
		{
			$pruned = array_diff( $words, $search_ignore );
			$value   = implode( ' ', $pruned );
		}
		
        return $value;
    }
}