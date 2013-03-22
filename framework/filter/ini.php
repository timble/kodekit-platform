<?php
/**
 * @category		Koowa
 * @package      Koowa_Filter
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link 		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Ini filter
 *
 * @author  Johan Janssens <johan@nooku.org>
 * @package Koowa_Filter
 */
class FilterIni extends Framework\FilterAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional Framework\Config object with configuration options
     */
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        //Don't walk the incoming data array or object
        $this->_walk = false;
    }

    /**
     * Validate a value
     *
     * @param    scalar Value to be validated
     * @return   bool   True when the variable is valid
     */
    protected function _validate($value)
    {
        try {
            $config = ConfigIni::fromString($value);
        } catch(\RuntimeException $e) {
            $config = null;
        }
        return is_string($value) && !is_null($config);
    }

    /**
     * Sanitize a value
     *
     * @param   string  Value to be sanitized
     * @return  Config
     */
    protected function _sanitize($value)
    {
        if(!$value instanceof Config)
        {
            if(is_string($value)) {
                $value = ConfigIni::fromString($value);
            }
        }

        return $value;
    }
}