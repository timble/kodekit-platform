<?php
/**
 * @version 	$Id:factory.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Factory
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * KFactory class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
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
	 * @var	object
	 */
	protected static $_chain = null;
	
	/**
	 * True if the factory has been initialized
	 *
	 * @var	object
	 */
	private static $_initialized = false;
	
	/**
	 * Constructor
	 * 
	 * Prevent creating instances of this class by making the contrucgtor private
	 */
	private function __construct() { }
	
	/**
	 * Get an instance of a class based on a class identifier only creating it
	 * if it doesn't exist yet.
	 *
	 * @param mixed  $identifier	The class identifier
	 * @param array  $options 		An optional associative array of configuration settings.
	 *
	 * @throws KFactoryException
	 * @return object
	 */
	public static function get($identifier, array $options = array())
	{
		self::_initialize(); //Initialise the factory
		
		//Check if the object already exists
		if(self::$_container->offsetExists($identifier)) {
			return self::$_container->offsetGet($identifier);
		} 
		
		//Get an instance based on the identifier
		$instance = self::$_chain->run($identifier, $options);
		if(!is_object($instance)) {
			throw new KFactoryException('Cannot create object instance from identifier : '.$identifier);	
		}	
		
		self::$_container->offsetSet($identifier, $instance);
		return $instance;
	}
	
	/**
	 * Get an instance of a class based on a class identifier always creating a 
	 * new instance.
	 *
	 * @param mixed  $identifier	The class identifier
	 * @param array  $options 		An optional associative array of configuration settings.
	 *
	 * @throws KFactoryException
	 * @return object
	 */
	public static function tmp($identifier, array $options = array())
	{
		self::_initialize(); //Initialise the factory
		
		//Get an instance based on the identifier
		$object = self::$_chain->run($identifier, $options);
		if($object === false) {
			throw new KFactoryException('Cannot create object from identifier : '.$identifier);	
		}	
		
		return $object;
	}
	
	/**
	 * Insert the object instance using the identifier
	 *
	 * @param mixed  $identifier 	The class identifier
	 * @param object $object 		The object instance to store
	 */
	public static function set($identifier, $object)
	{
		self::$_container->offsetSet($identifier, $object);
	}
	
	/**
	 * Remove the object instance using the identifier
	 *
	 * @param mixed  $identifier 	The class identifier
	 *
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	public static function del($identifier)
	{
		if(self::$_container->offsetExists($identifier)) {
			self::$_container->offsetUnset($identifier);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check if the object instance exists based on the identifier
	 *
	 * @param mixed  $identifier 	The class identifier
	 *
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	public static function has($identifier)
	{
		if(self::$_container->offsetExists($identifier)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Add a factory adapter
	 * 
	 * @param object 	$adapter	A KFactoryAdapter
	 * @param integer	$priority	The adapter priority
	 *
	 * @return void
	 */
	public static function addAdapter(KFactoryAdapterInterface $adapter, $priority = 3)
	{
		self::$_chain->enqueue($adapter, $priority);
	}
	
	/**
	 * Initialize
	 */	
	protected static function _initialize()
	{
		if(self::$_initialized === true) {
			return;
		}
		
		self::$_initialized = true;
	
		//Created the object container
		self::$_container = new ArrayObject();
	
		//Create the command chain and register the adapters
        self::$_chain = new KFactoryChain();
        
        //TODO : move the registration of the adapters out of the initialize
        self::addAdapter(new KFactoryAdapterKoowa());
       	self::addAdapter(new KFactoryAdapterJoomla());
        self::addAdapter(new KFactoryAdapterComponent());
	}
}