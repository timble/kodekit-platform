<?php
/**
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Framework;

/**
 * Json filter
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterJson extends FilterAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional Config object with configuration options
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        //Don't walk the incoming data array or object
        $this->_walk = false;
    }

    /**
     * Validate a value
     *
     * @param   scalar  Value to be validated
     * @return  bool    True when the variable is valid
     */
    protected function _validate($value)
    {
        return is_string($value) && !is_null(json_decode($value));
    }

    /**
     * Sanitize a value
     *
     * The value passed will be encoded to JSON format.
     *
     * @param   scalar  Value to be sanitized
     * @return  string
     */
    protected function _sanitize($value)
    {
        // If instance of Config casting to string will make it encode itself to JSON
        if($value instanceof Config) {
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