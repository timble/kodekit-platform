<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */



/**
 * Factory Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @static
 * @uses 		KIdentifier
 */
class KFactory implements KFactoryInterface
{
	/**
	 * The object container
	 *
	 * @var	array
	 */
	protected static $_registry = null;
	
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
     * Get the identifier registry object
     * 
     * @return object KFactoryRegistry
     */
    public static function getRegistry()
    {
        return self::$_registry;
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
		$objIdentifier = KIdentifier::identify($identifier);
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
		$objIdentifier = KIdentifier::identify($identifier);
		$strIdentifier = (string) $objIdentifier;
		
		self::$_registry->offsetSet($strIdentifier, $object);
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
	        $objIdentifier = KIdentifier::identify($identifier);
	        $strIdentifier = (string) $objIdentifier;
	        $result = (bool) self::$_registry->offsetExists($strIdentifier);
		
		} catch (KIdentifierException $e) {
		    $result = false;
		}
 		
		return $result;
	}
	
	/**
     * Set a mixin or an array of mixins for an identifier
     * 
     * The mixins are mixed when the indentified object is first instantiated see {@link get}
     * Mixins are also added to objects that already exist in the object registry.
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
            $objIdentifier = KIdentifier::identify($identifier);
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
        //Create the config object
        $config = new KConfig($config);
         
        //If the object is indentifiable push the identifier in through the constructor
        if(array_key_exists('KObjectIdentifiable', class_implements($identifier->classname))) {
            $config->identifier = $identifier;
        }
                            
        // If the class has an instantiate method call it
        if(array_key_exists('KObjectInstantiatable', class_implements($identifier->classname))) {
            $result = call_user_func(array($identifier->classname, 'getInstance'), $config, self::getInstance());
        } else {
            $result = new $identifier->classname($config);
        }
     
        if(!is_object($result)) {
            throw new KFactoryException('Cannot create object from identifier : '.$identifier);
        }
                
        return $result;
    }
}

//Instantiate the factory singleton
KFactory::getInstance();