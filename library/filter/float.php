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
 * Float Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
 */
class FilterFloat extends FilterAbstract implements FilterTraversable
{
	/**
	 * Validate a value
	 *
     * @param   scalar  $value Value to be validated
	 * @return	bool	True when the variable is valid
	 */
    public function validate($value)
	{
		return (false !== filter_var($value, FILTER_VALIDATE_FLOAT));
	}

	/**
	 * Sanitize a value
	 *
     * @param   scalar  $value Value to be sanitized
	 * @return	float
	 */
    public function sanitize($value)
	{
		return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT,
			FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_SCIENTIFIC);
	}
}

