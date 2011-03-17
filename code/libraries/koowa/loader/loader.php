<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Excpetion Classes
 */
require_once Koowa::getPath().'/exception/interface.php';
require_once Koowa::getPath().'/exception/exception.php';

/**
 * Indentifier Classes
 */
require_once Koowa::getPath().'/identifier/interface.php';
require_once Koowa::getPath().'/identifier/identifier.php';
require_once Koowa::getPath().'/identifier/exception.php';

/**
 * Loader Classes
 */
require_once Koowa::getPath().'/loader/adapter/interface.php';
require_once Koowa::getPath().'/loader/adapter/exception.php';
require_once Koowa::getPath().'/loader/adapter/abstract.php';
require_once Koowa::getPath().'/loader/adapter/koowa.php';

//Instantiate the loader singleton
KLoader::instantiate();

/**
 * KLoader class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Loader
 * @static
 */
class KLoader
{
    /**
     * The file container
     *
     * @var array
     */
    protected static $_registry = null;
    
    /**
     * Adapter list
     *
     * @var array
     */
    protected static $_adapters = null;
    

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the contructor private
     */
    final private function __construct() 
    { 
        //Created the adapter registry
        self::$_adapters  = array();
        self::$_registry = new ArrayObject();
        
        // Register the autoloader in a way to play well with as many configurations as possible.
        spl_autoload_register(array(__CLASS__, 'load'));

        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
    }
        
    /**
     * Clone 
     *
     * Prevent creating clones of this class
     */
    final private function __clone() { }

    /**
     * Singleton instance
     * 
     * @param   array   Options containing 'table', 'name'
     *
     * @return void
     */
    public static function instantiate()
    {
        static $instance;
        
        if ($instance === NULL) {
            $instance = new self();
        }
        
        return $instance;
    }

    /**
     * Load a class based on a class name or an identifier
     *
     * @param string|object The class name, identifier or identifier object
     * @return boolean      Returns TRUE on success throws exception on failure
     */
    public static function load($class)
    {
        //Extra filter added to circomvent issues with Zend Optimiser and strange classname.        
        if((ctype_upper(substr($class, 0, 1)) || (strpos($class, '.') !== false)))
        {
            //Pre-empt further searching for the named class or interface.
            //Do not use autoload, because this method is registered with
            //spl_autoload already.
            if (class_exists($class, false) || interface_exists($class, false)) {
                return true;
            }
        
            //Get the path
            $result = self::path( $class );
        
            //Don't re-include files and stat the file if it exists
            if ($result !== false && !in_array($result, get_included_files()) && file_exists($result))
            {
                $mask = E_ALL ^ E_WARNING;
                if (defined('E_DEPRECATED')) {
                    $mask = $mask ^ E_DEPRECATED;
                }
            
                $old = error_reporting($mask);
                $included = include $result;
                error_reporting($old);

                if ($included) {
                    return $result;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Get the path based on a class name or an identifier
     *
     * @param string|object The class name, identifier or identifier object
     * @return string   Returns canonicalized absolute pathname
     */
    public static function path($class)
    {
        if(self::$_registry->offsetExists((string)$class)) {
            return self::$_registry->offsetGet((string)$class);
        }
        
        $result = false;
                
        //If the class is a classname try to find the adapter based on the 
        //class prefix to reduce overhead in running through the chain, if 
        //it's an identifier run through all 
        if(ctype_upper(substr($class, 0, 1)))
        {
            $word  = preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class);
            $parts = explode('_', $word);
            
            if(isset(self::$_adapters[$parts[0]])) {
                $result = self::$_adapters[$parts[0]]->path( $class );
            }
        } 
        else 
        {
            if(!($class instanceof KIdentifier)) {
                $class = new KIdentifier($class);
            }
            
            $adapters = array_reverse(self::$_adapters);
            foreach($adapters as $adapter)
            {
                if($result = $adapter->path( $class )) {
                    break;
                }
            }
        }
        
        if ($result !== false) 
        {
            //Get the canonicalized absolute pathname
            $path = realpath($result);
            $result = $path !== false ? $path : $result;
            
            if($result !== false) {
                self::$_registry->offsetSet((string) $class, $result);
            }
        }
        
        return $result;
    }

    /**
     * Add a loader adapter
     *
     * @param object    A KLoaderAdapter
     * @return void
     */
    public static function addAdapter(KLoaderAdapterInterface $adapter)
    {
        self::$_adapters[$adapter->getPrefix()] = $adapter;
    }
}