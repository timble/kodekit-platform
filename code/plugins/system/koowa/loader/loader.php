<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
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

//Initialise the loader
KLoader::initialize();

/**
 * KLoader class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @static
 */
class KLoader
{

	/**
	 * Adapter list
	 *
	 * @var array
	 */
	protected static $_adapters = null;
	
	/**
	 * Paths array
	 *
	 * @var array
	 */
	protected static $_paths = null;

	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the contructor private
	 */
	private function __construct() { }

	/**
	 * Initialize
	 *
	 * @return void
	 */
	public static function initialize()
	{
		//Created the adapter container
		self::$_adapters = array();
		
		// Register the autoloader in a way to play well with as many configurations as possible.
		spl_autoload_register(array(__CLASS__, 'load'));

		if (function_exists('__autoload')) {
			spl_autoload_register('__autoload');
		}

        //Add the koowa adapter
        self::addAdapter(new KLoaderAdapterKoowa());
	}

	/**
	 * Load a class based on a class name or an identifier
	 *
	 * @param string|object The class name, identifier or identifier object
	 * @return boolean		Returns TRUE on success throws exception on failure
	 */
	public static function load($class)
	{
		//Pre-empt further searching for the named class or interface.
		//Do not use autoload, because this method is registered with
		//spl_autoload already.
		if (class_exists($class, false) || interface_exists($class, false)) {
			return true;
		}
		
		//Get the path
		$result = self::path( $class );
		
		//Don't re-include files
		if ($result !== false && !in_array($result, get_included_files()))
		{
			$mask = E_ALL ^ E_WARNING;
			if (defined('E_DEPRECATED')) {
				$mask = $mask ^ E_DEPRECATED;
			}
			
			$old = error_reporting($mask);
			$included = include $result;
			error_reporting($old);

			if ($included) {
				return true;
			}
      	}

		return false;
	}
	
	/**
	 * Get the path based on a class name or an identifier
	 *
	 * @param string|object The class name, identifier or identifier object
	 * @return string	Returns canonicalized absolute pathname
	 */
	public static function path($class)
	{
		//Use LIFO to allow for new adapters to override existing ones
		$adapters = array_reverse(self::$_adapters);

		foreach($adapters as $adapter)
		{
    		$result = $adapter->path( $class );
			if ($result !== false) 
			{
				//Get the canonicalized absolute pathname
				$path = realpath($result);
				
				//If realpath failed return $result
				return $path !== false ? $path : $result;
      		}
		}

		return false;
	}

	/**
	 * Add a loader adapter
	 *
	 * @param object 	A KLoaderAdapter
	 * @return void
	 */
	public static function addAdapter(KLoaderAdapterInterface $adapter)
	{
		self::$_adapters[] = $adapter;
	}
}