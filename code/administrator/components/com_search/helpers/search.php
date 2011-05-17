<?php
/**
 * @version		$Id: search.php 14401 2010-01-26 14:10:00Z louis $
 * @package  Joomla
 * @subpackage	Search
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * @package		Joomla
 * @subpackage	Search
 */
class SearchHelper
{
	/**
	 * Checks an object for search terms (after stripping fields of HTML)
	 *
	 * @param object The object to check
	 * @param string Search words to check for
	 * @param array List of object variables to check against
	 * @returns boolean True if searchTerm is in object, false otherwise
	 */
	function checkNoHtml($object, $searchTerm, $fields) 
	{
		$searchRegex = array(
				'#<script[^>]*>.*?</script>#si',
				'#<style[^>]*>.*?</style>#si',
				'#<!.*?(--|]])>#si',
				'#<[^>]*>#i'
				);
		$terms = explode(' ', $searchTerm);
		if(empty($fields)) {
		    return false;
		}
		
		foreach($fields AS $field) 
		{
			if(!isset($object->$field)) continue;
			$text = $object->$field;
			
			foreach($searchRegex As $regex) {
				$text = preg_replace($regex, '', $text);
			}
		
			foreach($terms AS $term) 
			{
				if(JString::stristr($text, $term) !== false) {
					return true;
				}
			}
		}
		
		return false;
	}
}
