<?php
/**
 * @version     $Id:array.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package     Koowa_Helper
 * @subpackage	Array
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Array helper
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Helper
 * @subpackage	Array
 * @static
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
    public static function settype(array $array, $type, $recursive = true)
    {
        foreach($array as $k => $v)
        {
            if($recursive && is_array($v)) {
                $array[$k] = self::settype($v, $type, $recursive);
            } else {
            	settype($array[$k], $type);
            }
        }
        return $array;
    }
    
 	/**
     * Count array items recursively
     * 
     * @param	array
     * @return	int
     */
    public static function count(array $array)
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
    
    /**
     * Extracts a column from an array of arrays or objects
     *
     * @param 	array	List of arrays or objects
     * @param   string  The index of the column or name of object property
     * @return  array   Column of values from the source array
     */
    public static function getColumn(array $array, $index)
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
	 * Utility function to map an array to a string
	 *
	 * @static
	 * @param	array	$array		The array to map.
	 * @param	string	$inner_glue 	The inner glue to use, default '='
	 * @param	string	$outer_glue		The outer glue to use, defaut  ' '
	 * @param	boolean	$keepOuterKey	
	 * @return	string	The string mapped from the given array
	 */
	public static function toString( array $array = null, $inner_glue = '=', $outer_glue = ' ', $keepOuterKey = false )
	{
		$output = array();

		if (is_array($array))
		{
			foreach ($array as $key => $item)
			{
				if (is_array ($item))
				{
					if ($keepOuterKey) {
						$output[] = $key;
					}
					
					// This is value is an array, go and do it again!
					$output[] = KHelperArray::toString( $item, $inner_glue, $outer_glue, $keepOuterKey);
				}
				else $output[] = $key.$inner_glue.'"'.$item.'"';
			}
		}

		return implode( $outer_glue, $output);
	}
}