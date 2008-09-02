<?php
/**
* @version      $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
* @package      Koowa
* @copyright    Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

/**
 * Koowa is active
 */
define('KOOWA', 1);

/**
 * DS is a shortcut for DIRECTORY_SEPARATOR
 */
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Register Koowa::load() with SPL.
 */
spl_autoload_register(array('Koowa', 'load'));

/**
 * Koowa class
 *
 * Loads classes and files, and provides metadata for Koowa such as version info
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa
 * @version     1.0
 */
class Koowa
{
    /**
     * Koowa version
     */
    const _VERSION = '0.5.4';

    /**
     * Path to Koowa libraries
     */
    protected static $_path;

    /**
     * Get the version of the Koowa library
     */
    public static function getVersion()
    {
   	    return self::_VERSION;
    }

    /**
     * Get path to Koowa libraries
     */
    public static function getPath()
    {
    	if(!isset(self::$_path)) {
        	self::$_path = dirname(__FILE__);
        }

        return self::$_path;
    }

	/**
     * Load the file for a class
     *
     * Is capable of autoloading Koowa library classes based on a camelcased
     * classname that represents the directory structure.
     *
     * @param   string  $class  The class that will be loaded
     * @return  boolean True on success
     */
    public static function load( $class )
    {
    	// pre-empt further searching for the named class or interface.
		// do not use autoload, because this method is registered with
		// spl_autoload already.
		if (class_exists($class, false) || interface_exists($class, false)) {
			return;
		}

		// if class start with a 'K' it is a Koowa framework class.
		// create the path and register it with the loader.
		switch(substr($class, 0, 1))
		{
			case 'K' :
                switch(strtoupper(substr(PHP_OS, 0, 3)))
                {
                    case 'WIN':
                        $path = strtolower(preg_replace('/(?<=\\w)([A-Z])/', DS.'\$1', ltrim($class, 'K')));
                        break;
                    default:
                        $path = strtolower(preg_replace('/(?<=\\w)([A-Z])/', DS.'$1', ltrim($class, 'K')));
                        break;
                }
				self::register($class,  dirname(__FILE__).DS.$path.'.php');
                break;
		}

		$classes = self::register();
        if(array_key_exists( strtolower($class), $classes)) {
            include($classes[strtolower($class)]);
            return true;
        }

        return false;
    }

    /**
     * Add a class to autoload
     *
     * Proxies the JLoader::register function
     *
     * @param	string $classname	The class name
     * @param	string $file		Full path to the file that holds the class
     * @return	array|boolean  		Array of classes
     * @see JLoader::register
     */
    public static function register ($class = null, $file = null) {
        return JLoader::register($class, $file);
    }

	/**
 	 * Intelligent file importer
	 *
 	 * @package		Koowa
 	 * @param string $path A dot syntax path
 	 */
	public static function import( $path, $basepath = '')
	{
		$parts = explode( '.', $path );

		$result = '';
		switch($parts[0])
		{
			case 'joomla'    :
				$result = JLoader::import($path, null, 'libraries.' );
				break;

			case 'com'   :
				$name   = $parts[1];

				unset($parts[0]);
				unset($parts[1]);

				$base   = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_'.$name;
				$path   = implode('.', $parts);

				$result = JLoader::import($path, $base, $name.'.' );
				break;

        	case 'plg'   :
				unset($parts[0]);
				$base   = JPATH_PLUGINS;
				$path   = implode('.', $parts);
				$result = JLoader::import($path, $base, '.' );
				break;

       	 	case 'koowa':
				unset($parts[0]);
				$base   = Koowa::getPath();
				$path   = implode('.', $parts);
				$result = JLoader::import($path, $base);
				break;

			default :
				$result = JLoader::import($path, JPATH_COMPONENT, substr(basename(JPATH_COMPONENT), 4).'.' );
				break;

		}

		return $result;
	}
	
    /**
     * Get the URL to the folder containing all media assets
     *
     * @param 	boolean	Return the relative path only
     * @return 	string	URL
     */
    public static function getMediaURL()
    {
    	return JURI::root().'media/plg_koowa/';
    }
}