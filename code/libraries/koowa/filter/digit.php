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
 * Digit filter
 * 
 * Checks if all of the characters in the provided string are numerical
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterDigit extends KFilterAbstract
{
	/**
	 * Validate a value
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($value)
	{
		return empty($value) || ctype_digit($value);
	}
	
	/**
	 * Sanitize a value
	 *
	 * @param	mixed	Value to be sanitized
	 * @return	int
	 */
	protected function _sanitize($value)
	{
		$value = trim($value);
		$pattern ='/[^0-9]*/';
		return preg_replace($pattern, '', $value);
	}
}

