<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 */

//Initialise the factory
KFactory::initialize();

/**
 * KFactory class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @static
 */
class KFactory
{
	/**
	 * The object container
	 *
	 * @var	array
	 */
	protected static $_container = null;

	/**
	 * The commandchain
	 *
	 * @var	KLoaderChain
	 */
	protected static $_chain = null;

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
		//Created the object container
		self::$_container = new ArrayObject();

		//Create the command chain and register the adapters
        self::$_chain = new KFactoryChain();

        //Add the koowa adapter
        self::addAdapter(new KFactoryAdapterKoowa());
	}

	/**
	 * Get an instance of a class based on a class identifier only creating it
	 * if it doesn't exist yet.
	 *
	 * @param	string|KFactoryIdentifierInterface|object	The class identifier
	 * 			or identifier object, or any object, in which case the object
	 * 			itself is returned
	 * @param	array  			An optional associative array of configuration settings.
	 * @throws	KFactoryException
	 * @return	object  		Return object on success, throws exception on failure
	 */
	public static function get($identifier, array $options = array())
	{
		if(is_object($identifier) && !($identifier instanceof KFactoryIdentifierInterface)) {
			return $identifier;
		}

		$string = (string) $identifier;

		//Check if the object already exists
		if(self::$_container->offsetExists($string)) {
			return self::$_container->offsetGet($string);
		}

		//Get an instance based on the identifier
		$instance = self::$_chain->run($identifier, $options);
		if(!is_object($instance)) {
			throw new KFactoryException('Cannot create object instance from identifier : '.$string);
		}

		self::$_container->offsetSet($string, $instance);
		return $instance;
	}

	/**
	 * Get an instance of a class based on a class identifier always creating a
	 * new instance.
	 *
	 * @param	string|object	The class identifier, or an object
	 * @param 	array  			An optional associative array of configuration settings.
	 * @throws 	KFactoryException
	 * @return 	object  		Return object on success, throws exception on failure
	 */
	public static function tmp($identifier, array $options = array())
	{
		if(is_object($identifier)&& !($identifier instanceof KFactoryIdentifierInterface)) {
			return $identifier;
		}

		$string = (string) $identifier;

		//Get an instance based on the identifier
		$object = self::$_chain->run($identifier, $options);
		if($object === false) {
			throw new KFactoryException('Cannot create object from identifier : '.$string);
		}

		return $object;
	}

	/**
	 * Insert the object instance using the identifier
	 *
	 * @param mixed  The class identifier
	 * @param object The object instance to store
	 */
	public static function set($identifier, $object)
	{
		$identifier = (string) $identifier;
		self::$_container->offsetSet($identifier, $object);
	}

	/**
	 * Remove the object instance using the identifier
	 *
	 * @param mixed  The class identifier
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	public static function del($identifier)
	{
		$identifier = (string) $identifier;

		if(self::$_container->offsetExists($identifier)) {
			self::$_container->offsetUnset($identifier);
			return true;
		}

		return false;
	}

	/**
	 * Check if the object instance exists based on the identifier
	 *
	 * @param mixed  The class identifier
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	public static function has($identifier)
	{
		$identifier = (string) $identifier;

		return (bool) self::$_container->offsetExists($identifier);
	}

	/**
	 * Add a factory adapter
	 *
	 * @param object 	A KFactoryAdapter
	 * @param integer	The adapter priority
	 * @return void
	 */
	public static function addAdapter(KFactoryAdapterInterface $adapter, $priority = 3)
	{
		self::$_chain->enqueue($adapter, $priority);
	}
}