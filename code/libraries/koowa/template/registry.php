<?php
/**
 * @version     $Id: template.php 2026 2010-05-14 16:47:03Z johanjanssens $
 * @category    Koowa
 * @package     Koowa_Template
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

 /**
  * Template class
  * 
  * @author     Johan Janssens <johan@nooku.org>
  * @category   Koowa
  * @package    Koowa_Template
  */
class KTemplateRegistry 
{ 
    /**
     * The object container
     *
     * @var array
     */
    protected static $_registry = null;
    
    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the contructor private
     */
    final private function __construct(KConfig $config) 
    { 
        self::$_registry = new ArrayObject();
    }
    
    /**
     * Clone 
     *
     * Prevent creating clones of this class
     */
    final private function __clone() { }
    
    /**
     * Force creation of a singleton
     *
     * @return void
     */
    public static function instantiate($config = array())
    {
        static $instance;
        
        if ($instance === NULL) 
        {
            if(!$config instanceof KConfig) {
                $config = new KConfig($config);
            }
            
            $instance = new self($config);
        }
        
        return $instance;
    }
    
    /**
     * Insert the template instance in the registry based on the path
     *
     * @param mixed  The path
     * @param object The template object
     */
    public static function set($path, KTemplateAbstract $template)
    {
        self::$_registry->offsetSet($path, $template);
    }
    
    /**
     * Get a template instance from the registry based on a path
     *
     * @param mixed  The path
     * @param object The template object
     */
    public static function get($path)
    {
        if(self::$_registry->offsetExists($path)) {
            return self::$_registry->offsetGet($path);
        }
        
        return null;
    }

    /**
     * Remove the template instance from the registry based on the path
     *
     * @param   string  The path
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public static function del($path)
    {
        if(self::$_registry->offsetExists($path)) {
            self::$_registry->offsetUnset($path);
            return true;
        }

        return false;
    }   
}