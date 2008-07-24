<?php
/**
 * @version     $Id:array.php 46 2008-03-01 18:39:32Z mjaz $
 * @package     Koowa_Helper
 * @subpacakge	Array
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Array helper
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Helper
 * @subpacakge	Array
 * @version     1.0
 */
class KHelperArray
{
    /**
     * Typecast each element of the array. Recursive (optional)
     *
     * @param	array	Array to typecast
     * @param	string	Type (boolean|int|float|string|array|object|null)
     * @param	boolean	Recursive
     * @return	array
     */
    public static function settype($array, $type, $recursive = true)
    {
        foreach($array as $k => $v)
        {
            if($recursive AND is_array($v))
            {
                $array[$k] = self::settype($v, $type, $recursive);
            }
            else
            {
            	settype($array[$k], $type);
            }
        }
        return $array;
    }
    
    /**
     * Extracts a column from an array of arrays or objects
     *
     * @param 	array	List of arrays or objects
     * @param   string  The index of the column or name of object property
     * @return  array   Column of values from the source array
     */
    public static function getColumn($array, $index)
    {
        $result = array();
        
        foreach($array as $k => $v)
        {
            if(is_object($v)) {
                $result[$k] = $v->$index;
            } else {
                $result[$k] = $v[$index];
            }
        }
        
        return $result;
    }

    /**
     * Count array items recursively
     * 
     * @param	array
     * @return	int
     */
    public static function count($array)
    {
        $count = 0;

        foreach($array as $v){
            if(is_array($v)){
                $count += self::count($v);
            } else {
                $count++;
            }
        }
        return $count;
    
    }
}