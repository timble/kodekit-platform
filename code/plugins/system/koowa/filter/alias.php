<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Alias filter
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterAlias extends KFilterAbstract
{
	/**
	 * Validate a value
	 * 
	 * Returns true if the string only contains US-ASCII and does not contain
	 * any spaces
	 *
	 * @param	mixed	Variable to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($value)
	{
		return KFactory::tmp('lib.koowa.filter.cmd')->validate($value);
	}
	
	/**
	 * Sanitize a value
	 * 
	 * Replace all accented UTF-8 characters by unaccented ASCII-7 "equivalents", 
	 * replace whitespaces by hyphens and lowercase the result.
	 * 
	 * @param	scalar	Variable to be sanitized
	 * @return	scalar
	 */
	protected function _sanitize($value)
	{
		//remove any '-' from the string they will be used as concatonater
		$value = str_replace('-', ' ', $value);
		
		//convert to ascii characters
		$value = KFactory::tmp('lib.koowa.filter.ascii')->sanitize($value);
		
		// remove any duplicate whitespace, and ensure all characters are alphanumeric
		$value = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $value);

		// lowercase and trim
		$value = trim(strtolower($value));
		
		return $value;
	}
}