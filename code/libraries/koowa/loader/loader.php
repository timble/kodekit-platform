<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Registry Classes
 */
require_once Koowa::getPath().'/loader/registry.php';

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
KLoader::getInstance();

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
     * Prefix map
     *
     * @var array
     */
    protected static $_prefix_map = null;
    
    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the contructor private
     */
    final private function __construct() 
    { 
        //Created the adapter registry
        self::$_adapters   = array();
        self::$_prefix_map = array();
        self::$_registry = new KLoaderRegistry();
        
        self::register();
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
    public static function getInstance($config = array())
    {
        static $instance;
        
        if ($instance === NULL) {
            $instance = new self();
        }
        
        return $instance;
    }
    
    /**
     * Registers this instance as an autoloader.
     *
     * @return void
     */
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'loadClass'));

        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
    }
    
    
    /**
     * Get the class registry object
     * 
     * @return object KLoaderRegistry
     */
    public static function getRegistry()
    {
        return self::$_registry;
    }
    
	/**
     * Get the registered adapters
     * 
     * @return array
     */
    public static function getAdapters()
    {
        return self::$_adapters;
    }
    
    /**
     * Load a class based on a class name
     *
     * @param string    The class name
     * @param string    The basepath
     * @return boolean  Returns TRUE on success throws exception on failure
     */
    public static function loadClass($class, $basepath = null)
    {
        $result = false;
         
        //Extra filter added to circomvent issues with Zend Optimiser and strange classname.        
        if((ctype_upper(substr($class, 0, 1)) || (strpos($class, '.') !== false)))
        {
            //Pre-empt further searching for the named class or interface.
            //Do not use autoload, because this method is registered with
            //spl_autoload already.
            if (!class_exists($class, false) && !interface_exists($class, false)) 
            {
                //Get the path
                $path = self::findPath( $class, $basepath );
        
                if ($path !== false) {
                    $result = self::loadFile($path);
                }
            }
            else $result = true;
        }
        
        return $result;
    }
    
	/**
     * Load a class based on an identifier
     *
     * @param string|object The identifier or identifier object
     * @return boolean      Returns TRUE on success throws exception on failure
     */
    public static function loadIdentifier($identifier)
    {
        $result = false;
         
        $identifier = KIdentifier::identify($identifier);
        
        //Get the path
        $path = $identifier->filepath;
        
        if ($path !== false) {
            $result = self::loadFile($path);
        }
        
        return $result;
    }
    
    /**
     * Load a class based on a path
     *
     * @param string	The file path
     * @return boolean  Returns TRUE on success throws exception on failure
     */
    public static function loadFile($path)
    {
        $result = false;
        
        //Don't re-include files and stat the file if it exists
        if (!in_array($path, get_included_files()) && file_exists($path))
        {
            $mask = E_ALL ^ E_WARNING;
            if (defined('E_DEPRECATED')) {
                $mask = $mask ^ E_DEPRECATED;
            }
            
            $old = error_reporting($mask);
            $included = include $path;
            error_reporting($old);

            if ($included) {
                $result = true;
            }
        }
        
        return $result;
    }
    
    /**
     * Get the path based on a class name
     *
     * @param string	The class name
     * @param string    The basepath
     * @return string   Returns canonicalized absolute pathname
     */
    public static function findPath($class, $basepath = null)
    {
        if(!self::$_registry->offsetExists((string) $class)) 
        {
            $result = false;
                
            $word  = preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class);
            $parts = explode('_', $word);
            
            if(isset(self::$_prefix_map[$parts[0]])) {
                $result = self::$_adapters[self::$_prefix_map[$parts[0]]]->findPath( $class, $basepath);
            }
             
            if ($result !== false) 
            {
                //Get the canonicalized absolute pathname
                $path = realpath($result);
                $result = $path !== false ? $path : $result;
            }
            
            self::$_registry->offsetSet((string) $class, $result);
        }
        else $result = self::$_registry->offsetGet((string)$class);
        
        return $result;
    }
    
    /**
     * Add a loader adapter
     *
     * @param object    A KLoaderAdapter
     * @return void
     */
    public static function registerAdapter(KLoaderAdapterInterface $adapter)
    {
        self::$_adapters[$adapter->getType()]     = $adapter;
        self::$_prefix_map[$adapter->getPrefix()] = $adapter->getType();
    }
}