<?php
/**
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Alphabetic filter.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterAlpha extends FilterAbstract implements FilterTraversable
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
		return ctype_alpha($value);
	}

	/**
	 * Sanitize a variable
	 *
     * @param   scalar  $value Value to be sanitized
	 * @return	string
	 */
    public function sanitize($value)
	{
		$pattern 	= '/[^[a-zA-Z]*/';
    	return preg_replace($pattern, '', $value);
	}
}