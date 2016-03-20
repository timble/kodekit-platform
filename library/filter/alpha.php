<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Alphabetic Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
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