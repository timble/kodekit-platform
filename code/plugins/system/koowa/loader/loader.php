<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Loader Adapter Interface
 */
require_once dirname(__FILE__).'/adapter/interface.php';

/**
 * Loader Adapter for the Koowa framework
 */
require_once dirname(__FILE__).'/adapter/koowa.php';

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
	 * Load a class based on a class name
	 *
	 * @param string  The class name
	 * @return boolean	Returns TRUE on success throws exception on failure
	 */
	public static function load($class)
	{
		// pre-empt further searching for the named class or interface.
		// do not use autoload, because this method is registered with
		// spl_autoload already.
		if (class_exists($class, false) || interface_exists($class, false)) {
			return true;
		}

		//Use LIFO to allow for new adapters to override existing ones
		$adpters = array_reverse(self::$_adapters);

		foreach(self::$_adapters as $adapter)
		{
    		$result = $adapter->load( $class );
			if ($result !== false)
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