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
 * Url Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
 */
class FilterUrl extends FilterAbstract implements FilterTraversable
{
    /**
     * Special URL characters
     *
     * @var array
     */
    protected static $_special_characters = array(
        // Unescaped
        '%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
        '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')',
        // Reserved
        '%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
        '%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$',
        // Score
        '%23'=>'#',
        // Percent
        '%25'=>'%'

    );

    /**
     * Validate a value
     *
     * @param   mixed   $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
	{
		$value = trim($value);
		return (false !== filter_var($value, FILTER_VALIDATE_URL));
	}

    /**
     * Sanitize a value
     *
     * @param   mixed   $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        // Escape UTF-8 characters
        $value = strtr(rawurlencode($value), static::$_special_characters);

        return filter_var($value, FILTER_SANITIZE_URL);
    }
}

