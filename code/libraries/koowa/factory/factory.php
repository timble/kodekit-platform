<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

//Instantiate the factory singleton
KFactory::getInstance();

/**
 * KFactory class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @static
 * @uses 		KIdentifier
 * @uses		KCommandContext
 */
class KFactory
{
	/**
	 * The object container
	 *
	 * @var	array
	 */
	protected static $_registry = null;
	
	/**
     * Adapter list
     *
     * @var array
     */
    protected static $_adapters = null;
	
	/**
	 * The identifier alias map
	 *
	 * @var	array
	 */
	protected static $_identifier_map = array();
	
	/**
	 * The mixin map
	 *
	 * @var	array
	 */
	protected static $_mixin_map = array();

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
	public static function getInstance($config = array())
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
	 * Returns an identifier string. 
	 * 
	 * Accepts various types of parameters and returns a valid identifier. Parameters can either be an 
	 * object that implements KObjectIdentifiable, or a KIdentifierInterface, or valid identifier 
	 * string. Function will also check for identifier mappings and return the mapped identifier.
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIdentifierInterface or valid identifier string
	 * @return KIdentifier
	 */
	public static function identify($identifier)
	{		
		if(!is_string($identifier)) 
		{
			if($identifier instanceof KObjectIdentifiable) {
			    $identifier = $identifier->getIdentifier();
		    }   
		} 
		
		$alias = (string) $identifier;
		if(array_key_exists($alias, self::$_identifier_map)) {
			$identifier = self::$_identifier_map[$alias];
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
	public static function get($identifier, array $config = array())
	{
		$objIdentifier = self::identify($identifier);
		$strIdentifier = (string) $objIdentifier;
		
		if(!self::$_registry->offsetExists($strIdentifier))
		{
			//Instantiate the identifier
			$instance = self::_instantiate($objIdentifier, $config);
		
			//Perform the mixin 
			self::_mixin($strIdentifier, $instance);
		}
		else $instance = self::$_registry->offsetGet($strIdentifier);
	    
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
		$objIdentifier = self::identify($identifier);
		$strIdentifier = (string) $objIdentifier;
		
		self::$_registry->offsetSet($strIdentifier, $object);
	}

	/**
	 * Remove the object instance using the identifier
	 *
	 * @param mixed  The class identifier
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	public static function del($identifier)
	{
		$objIdentifier = self::identify($identifier);
		$strIdentifier = (string) $objIdentifier;

		if(self::$_registry->offsetExists($strIdentifier)) {
			self::$_registry->offsetUnset($strIdentifier);
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
		try 
		{
	        $objIdentifier = self::identify($identifier);
	        $strIdentifier = (string) $objIdentifier;
	        $result = (bool) self::$_registry->offsetExists($strIdentifier);
		
		} catch (KIdentifierException $e) {
		    $result = false;
		}
 		
		return $result;
	}
	
	/**
	 * Creates an alias for an identifier
	 *
	 * @param mixed  The alias 
	 * @param mixed  The class indentifier or identifier object
	 */
	public static function map($alias, $identifier)
	{		
		$identifier = self::identify($identifier);
		
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
     * @param  string|array A mixin identifier or a array of mixin identifiers
     * @see KObject::mixin
     */
    public static function mix($identifiers, $mixins)
    {
        settype($identifiers, 'array');
        settype($mixins,      'array');

        foreach($identifiers as $identifier) 
        {
            $objIdentifier = self::identify($identifier);
            $strIdentifier = (string) $objIdentifier;
            
            if (!isset(self::$_mixin_map[$strIdentifier]) ) {
                self::$_mixin_map[$strIdentifier] = array();
            }

            self::$_mixin_map[$strIdentifier] = array_unique(array_merge(self::$_mixin_map[$strIdentifier], $mixins));

            if (self::$_registry->offsetExists($strIdentifier)) 
            {
                $instance = self::$_registry->offsetGet($strIdentifier);
                
                //Perform the mixin 
                self::_mixin($strIdentifier, $instance);
            }
        }
    }

    /**
     * Add a factory adapter
     *
     * @param object    A KFactoryAdapter
     * @return void
     */
    public static function addAdapter(KFactoryAdapterInterface $adapter)
    {
        self::$_adapters[$adapter->getType()] = $adapter;
    }
    
    /**
     * Perform the actual mixin of all registered mixins with an object 
     * 
     * @param   string  The identifier string
     * @param   object  A KObject instance to used as the mixer
     * @return void
     */
    protected static function _mixin($identifier, $instance)
    {
        if(isset(self::$_mixin_map[$identifier]) && $instance instanceof KObject)
        {
            $mixins = self::$_mixin_map[$identifier];
            foreach($mixins as $mixin) 
            {
                $mixin = KFactory::get($mixin, array('mixer'=> $instance));
                $instance->mixin($mixin);
            }
        }
    }
    
    /**
     * Get an instance of a class based on a class identifier
     *
     * @param   string          The identifier string 
     * @param   array           An optional associative array of configuration settings.
     * @throws  KFactoryException
     * @return  object          Return object on success, throws exception on failure
     */
    protected static function _instantiate($identifier, array $config = array())
    {          
        $config = new KConfig($config);
        
        if(isset(self::$_adapters[$identifier->type])) 
        {
            $result = self::$_adapters[$identifier->type]->instantiate($identifier, $config);
          
            //If we get a string returned we assume it's a classname
            if(is_string($result)) 
            {
                //Set the classname
                $identifier->classname = $result;
            
                //Set the filepath
                $identifier->filepath = KLoader::path($identifier);
            
                //If the object is indentifiable push the identifier in through the constructor
                if(array_key_exists('KObjectIdentifiable', class_implements($identifier->classname))) {
                    $config->identifier = $identifier;
                }
                            
                // If the class has an instantiate method call it
                if(array_key_exists('KObjectInstantiatable', class_implements($identifier->classname))) {
                    $result = call_user_func(array($identifier->classname, 'getInstance'), $config);
                } else {
                    $result = new $identifier->classname($config);
                }
            }
        }
        
        if(!is_object($result)) {
            throw new KFactoryException('Cannot create object from identifier : '.$identifier);
        }
                
        return $result;
    }
}