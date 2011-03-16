<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

/**
 * Json filter
 * 
 * If the value being sanitized is a json string it will be decoded, otherwise
 * the value will be encoded upon sanitisation. 
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Filter
 */
class KFilterJson extends KFilterAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config) 
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
     * @param   scalar  Value to be sanitized
     * @return  string
     */
    protected function _sanitize($value)
    {
        $result = null;
        
        if(is_a($value, 'KConfig')) {
            $value = $value->toArray(); 
        }   
        
        if(is_string($value)) {
            $result = json_decode($value);
        }
        
        if(is_null($result)) {
            $result =  json_encode($value);
        }
        
        return $result;
    }
}

