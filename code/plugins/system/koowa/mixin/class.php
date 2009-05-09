<?php
/**
 * @version     $Id:object.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package     Koowa_Mixin
 * @subpackage 	Class
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Class utitlity
 *
 * Can be used as a mixin in classes that rely on their name, such as KView
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Mixin
 * @uses 		KObject
 * @uses		KInflector
 */
class KMixinClass extends KMixinAbstract
{
    /**
     * the object's basename
     *
     * @var string
     */
    protected $_name_base;

    /**
     * Classname
     *
     * @var array
     */
    protected $_name_parts;

    /**
     * Constructor
     *
     * @param	object	Object
     * @param	string	Basename for the object [controller|view|...]
     */
	public function __construct($mixer, $basename)
    {
    	parent::__construct($mixer);
    	
        $this->_name_base    = $basename;
        $this->_name_parts   = KInflector::split($basename, get_class($this->_mixer));
    }

    /**
     * Get the array with the classname parts, or get one specific part
     *
     * @param	string			Part [all|prefix|base|suffix]
     * @return 	string|array	String when a part is provided
     */
    public function getClassName($part = null)
    {
        $parts = $this->_name_parts;
    
    	switch($part)
        {
        	case 'all':
                $name = $parts['prefix'].'_'.$parts['base'].'_'.$parts['suffix'];
        		return KInflector::classify($name);
					
            case null:
                return $parts;
				
            default:
                return $parts[$part];
        }
    }

    /**
     * Set the classname
     *
     * This is usefull when working with K<Base>Default classes, where default
     * is used as a placeholder
     *
     * @param	array Array with optional prefix, base, suffix
     */
    public function setClassName(array $array)
    {
        foreach(array('prefix', 'base', 'suffix') as $part)
        {
            if(array_key_exists($part, $array)) {
            	$this->_name_parts[$part] = $array[$part];
            }
        }
        
        return $this->_mixer;
    }
}