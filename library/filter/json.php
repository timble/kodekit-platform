<?php
/**
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Json filter
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterJson extends FilterAbstract
{
    /**
     * Validate a value
     *
     * @param   scalar  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        return is_string($value) && !is_null(json_decode($value));
    }

    /**
     * Sanitize a value
     *
     * The value passed will be encoded to JSON format.
     *
     * @param   scalar  $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        // If instance of ObjectConfig casting to string will make it encode itself to JSON
        if($value instanceof ObjectConfig) {
            $result = (string) $value;
        }
        else
        {
            //Don't re-encode if the value is already in json format
            if(is_string($value) && (json_decode($value) !== NULL)) {
                $result = $value;
            } else {
                $result = json_encode($value);
            }
        }

        return $result;
    }
}