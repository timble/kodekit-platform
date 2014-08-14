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
 * Digit Filter
 *
 * Checks if all of the characters in the provided string are numerical
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Filter
 */
class FilterDigit extends FilterAbstract implements FilterTraversable
{
    /**
     * Validate a value
     *
     * @param   scalar  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        return empty($value) || ctype_digit($value);
    }

    /**
     * Sanitize a value
     *
     * @param   scalar  $value Value to be sanitized
     * @return  int
     */
    public function sanitize($value)
    {
        $value = trim($value);
        $pattern ='/[^0-9]*/';
        return preg_replace($pattern, '', $value);
    }
}

