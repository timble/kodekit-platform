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
 * Boolean Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
 */
class FilterBoolean extends FilterAbstract implements FilterTraversable
{
	/**
	 * Validate a value
	 *
	 *  Returns TRUE for boolean values: "1", "true", "on" and "yes", "0",
	 * "false", "off", "no", and "". Returns FALSE for all non-boolean values.
	 *
     * @param   scalar  $value Value to be validated
	 * @return	bool	True when the variable is valid
	 */
    public function validate($value)
	{
		return (null !== filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) );
	}

	/**
	 * Sanitize a value
	 *
	 * Returns TRUE for "1", "true", "on" and "yes". Returns FALSE for all other values.
	 *
     * @param   scalar  $value Value to be sanitized
	 * @return	bool
	 */
    public function sanitize($value)
	{
		return (bool) filter_var($value, FILTER_VALIDATE_BOOLEAN);
	}
}