<?php
/**
 * @category		Koowa
 * @package      Koowa_Filter
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link 		http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Ini filter
 *
 * @author  Johan Janssens <johan@nooku.org>
 * @package Koowa_Filter
 */
class FilterIni extends FilterAbstract
{
    /**
     * Validate a value
     *
     * @param   scalar  $value Value to be validated
     * @return   bool   True when the variable is valid
     */
    public function validate($value)
    {
        try {
            $config = ObjectConfigIni::fromString($value);
        } catch(\RuntimeException $e) {
            $config = null;
        }
        return is_string($value) && !is_null($config);
    }

    /**
     * Sanitize a value
     *
     * @param   scalar  $value Value to be sanitized
     * @return  ObjectConfig
     */
    public function sanitize($value)
    {
        if(!$value instanceof ObjectConfig)
        {
            if(is_string($value)) {
                $value = ObjectConfigIni::fromString($value);
            } else {
                $value = new ObjectConfigIni($value);
            }
        }

        return $value;
    }
}