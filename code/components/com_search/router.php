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
 * Search Router
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */

/**
 * Transforms a non-SEF URI to a SEF URI.
 * 
 * @param array The variables
 * @return array The segments of the SEF URI
 */
function SearchBuildRoute(&$query)
{
	$segments = array();
	
	if(isset($query['view'])) {
		unset($query['view']);
	}
	
	return $segments;
}

/**
 * Provides variables from a SEF URI.
 * 
 * @param array The URI segments.
 * @return array The variables.
 */
function SearchParseRoute($segments)
{
	$vars = array();
	
	return $vars;
}