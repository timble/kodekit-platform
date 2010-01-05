<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
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
	 * The identifier alias map
	 *
	 * @var	array
	 */
	protected static $_identifier_map = array();

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
		self::$_container = new ArrayObject();
        self::$_chain     = new KFactoryChain();

        self::addAdapter(new KFactoryAdapterKoowa());
	}
	
	/**
	 * Returns an identifier string. 
	 * 
	 * Accepts vairous types of parameters and returns a valid identifier. Parameters can either be an 
	 * object that implements KFactoryIdentifiable, or a KIndentifierInterface, or valid identifier 
	 * string. Fucntion will also check for identifier mappings and return the mapped identifier.
	 *
	 * @param	mixed	The identifier, an object that implements KFactoryIdentifiable, 
	 *                   a KIndentifierInterface or valid identifier string
	 * @return KIdentifier
	 */
	public static function identify($identifier)
	{
		if(is_object($identifier) && $identifier instanceof KFactoryIdentifiable) {
			$identifier = $identifier->getIdentifier();
		} 
		
		if(array_key_exists((string) $identifier, self::$_identifier_map)) {
			$identifier = self::$_identifier_map[(string) $identifier];
		}
		
		if(is_string($identifier)) {
			$identifier = new KIdentifier($identifier);
		}
		
		return $identifier;
	}

	/**
	 * Get an instance of a class based on a class identifier only creating it
	 * if it doesn't exist yet.
	 *
	 * @param	string|object	The class identifier or identifier object
	 * @param	array  			An optional associative array of configuration settings.
	 * @throws	KFactoryException
	 * @return	object  		Return object on success, throws exception on failure
	 */
	public static function get($identifier, array $options = array())
	{
		$identifier = self::identify($identifier);
		
		if(self::$_container->offsetExists((string)$identifier)) {
			return self::$_container->offsetGet((string)$identifier);
		}

		$context = new KCommandContext(); //Cannot use KFactory to avoid looping
		$context['options'] = $options;
		
		$instance = self::$_chain->run($identifier, $context);
		if(!is_object($instance)) {
			throw new KFactoryException('Cannot create object instance from identifier : '.$identifier);
		}

		self::$_container->offsetSet((string) $identifier, $instance);
		return $instance;
	}

	/**
	 * Get an instance of a class based on a class identifier always creating a
	 * new instance.
	 *
	 * @param	string|object	The class identifier or an identifier object
	 * @param 	array  			An optional associative array of configuration settings.
	 * @throws 	KFactoryException
	 * @return 	object  		Return object on success, throws exception on failure
	 */
	public static function tmp($identifier, array $options = array())
	{
		$identifier = self::identify($identifier);
	
		$context = new KCommandContext(); //Cannot use KFactory to avoid looping
		$context['options'] = $options;
		
		$instance = self::$_chain->run($identifier, $context);
		if(!is_object($instance)) {
			throw new KFactoryException('Cannot create object from identifier : '.$identifier);
		}

		return $instance;
	}

	/**
	 * Insert the object instance using the identifier
	 *
	 * @param mixed  The class identifier
	 * @param object The object instance to store
	 */
	public static function set($identifier, $object)
	{
		$identifier = self::identify($identifier);
		
		self::$_container->offsetSet((string) $identifier, $object);
	}

	/**
	 * Remove the object instance using the identifier
	 *
	 * @param mixed  The class identifier
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	public static function del($identifier)
	{
		$identifier = self::identify($identifier);

		if(self::$_container->offsetExists((string)$identifier)) {
			self::$_container->offsetUnset((string)$identifier);
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
		$identifier = self::identify($identifier);

		return (bool) self::$_container->offsetExists((string)$identifier);
	}
	
	/**
	 * Creates an alias for an identifier
	 *
	 * @param mixed  The alias 
	 * @param mixed  The class indentifier or identifier object
	 */
	public static function map($alias, $identifier)
	{		
		self::$_identifier_map[$alias] = $identifier;
	}
	
	/**
     * Set a mixin or an array of mixins for an identifier
     * 
     * The mixins are mixed when the indentified object is first instantiated see {@linkk get} and 
     * {$link tmp} Mixins are also added to existing singleton objects that already exist in the
     * object store.
     *
     * @param  mixed        An identifier string, KIdentfier object or an array of identifiers 
     * @param  string|array	A mixin identifier or a array of mixin identifiers
     * @see KObject::mixin
   	 */
   	public static function mix($identifiers, $mixins)
   	{
     	settype($identifiers, 'array');
       	settype($mixins,      'array');

     	foreach($identifiers as $identifier) 
     	{
       		$identifier    = (string) self::identify($identifier);
       		
     		if (!isset(self::$_mixin_map[$identifier]) ) {
       			self::$_mixin_map[$identifier] = array();
       		}

         	self::$_mixin_map[$identifier] = array_unique(array_merge(self::$_mixin_map[$identifier], $mixins));

          	if (self::$_container->offsetExists((string)$identifier)) 
			{
				$mixins = self::$_mixin_map[$identifier];
      			foreach($mixins as $mixin) 
      			{
        			$instance = self::$_container->offsetGet($identifier);
      				$mixin = KFactory::tmp($mixin, array('mixer'=> $instance));
          			$instance->mixin($mixin);
      			}
          	}
     	}
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