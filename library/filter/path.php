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
 * Path Filter
 *
 * Filters Windows and Unix style file paths
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
 */
class FilterPath extends FilterAbstract implements FilterTraversable
{
    /**
     * Validate a value
     *
     * @param   mixed  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        $result = false;

        if (is_string($value) && strlen($value))
        {
            if ($value[0] == '/' || $value[0] == '\\'
                || (strlen($value) > 3 && ctype_alpha($value[0])
                    && $value[1] == ':'
                    && ($value[2] == '\\' || $value[2] == '/')
                )
                || null !== parse_url($value, PHP_URL_SCHEME)
            ) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Sanitize a value
     *
     * @param   mixed   $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        return $this->validate($value) ? $value : '';
    }
}