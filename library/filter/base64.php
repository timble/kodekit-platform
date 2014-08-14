<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Base64 Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Filter
 */
class FilterBase64 extends FilterAbstract implements FilterTraversable
{
	/**
	 * Validate a value
	 *
     * @param   scalar  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        $pattern = '#^[a-zA-Z0-9/+]*={0,2}$#';
        return (is_string($value) && preg_match($pattern, $value) == 1);
    }

	/**
     * Sanitize a value
     *
     * @param   scalar  $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        $value = trim($value);
        $pattern = '#[^a-zA-Z0-9/+=]#';
        return preg_replace($pattern, '', $value);
    }
}