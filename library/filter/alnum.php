<?php
/**
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Alphanumeric filter.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterAlnum extends FilterAbstract implements FilterTraversable
{
	/**
	 * Validate a variable
	 *
	 * @param	scalar	$value Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	public function validate($value)
	{
		$value = trim($value);
		return ctype_alnum($value);
	}

	/**
	 * Sanitize a variable
	 *
     * @param   scalar  $value Value to be sanitized
	 * @return	string
	 */
	public function sanitize($value)
	{
		$value = trim($value);

	    $pattern 	= '/[^\w]*/';
    	return preg_replace($pattern, '', $value);
	}
}