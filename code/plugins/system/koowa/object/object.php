<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Object
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Object class
 *
 * Provides getters and setters, mixin, object handles
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Object
 */
class KObject
{
    /**
     * Mixed in methods
     *
     * @var array
     */
    protected $_mixed_methods = array();
    
   /**
	 * The object identifier
	 * 
	 * Public access is allowed via __get() with $identifier. The identifier
	 * is only available of the object implements the KObjectIndetifiable
	 * interface
	 *
	 * @var KIdentifierInterface
	 */
	protected $_identifier;
     
	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null) 
	{ 
		//Set the identifier before initialise is called
		if($this instanceof KObjectIdentifiable) {
			$this->_identifier = $config->identifier;
		}
		
		if($config) {
			$this->_initialize($config);
		}
	}
	
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
    	//do nothing
    }
	    
 	/**
     * Set the object properties
     *
     * @param   string|array|object	The name of the property, an associative array or an object
     * @param   mixed  				The value of the property
     * @throws	KObjectException
     * @return  KObject
     */
    public function set( $property, $value = null )
    {
    	if(is_object($property)) {
    		$property = (array) $property;
    	}
    	
    	if(is_array($property)) 
        {
        	foreach ($property as $k => $v) {
            	$this->set($k, $v);
        	}
        }
        else 
        {
       		if('_' == substr($property, 0, 1)) {
        		throw new KObjectException("Protected or private properties can't be set outside of object scope in ".get_class($this));
        	}
        	
        	$this->$property = $value;
        }
    	
        return $this;
    }

    /**
     * Get the object properties
     * 
     * If no property name is given then the function will return an associative
     * array of all properties.
     * 
     * If the property does not exist and a  default value is specified this is
     * returned, otherwise the function return NULL.
     *
     * @param   string	The name of the property
     * @param   mixed  	The default value
     * @return  mixed 	The value of the property, an associative array or NULL
     */
    public function get($property = null, $default = null)
    {
        $result = $default;
    	
    	if(is_null($property)) 
        {
        	$result  = get_object_vars($this);

        	foreach ($result as $key => $value)
        	{
            	if ('_' == substr($key, 0, 1)) {
                	unset($result[$key]);
            	}
        	}
        } 
        else
        {
    		if(isset($this->$property)) {
            	$result = $this->$property;
        	}
        }
        
        return $result;
    }

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed
     * in objects, in a LIFO order. 
     *
     * @param	object	An object that implements KMinxInterface
     * @return	KObject
     */
    public function mixin(KMixinInterface $object)
    {
       	$methods = $object->getMixableMethods($this);

        foreach($methods as $method) {
            $this->_mixed_methods[$method] = $object;
        }

        return $this;
    }
    
	/**
	 * Get a handle for this object
	 *
	 * This function returns an unique identifier for the object. This id can be used as
	 * a hash key for storing objects or for identifying an object
	 *
	 * @return string A string that is unique
	 */
	public function getHandle()
	{
		return spl_object_hash( $this );
	}
	
	/**
	 * Get a list of all the available methods
	 *
	 * This function returns an array of all the methods, both native and mixed in
	 *
	 * @return array An array 
	 */
	public function getMethods()
	{
		$native = get_class_methods($this);
		$mixed  = array_keys($this->_mixed_methods);
		
		return array_merge($native, $mixed);
	}
	
    /**
     * Search the mixin method map and call the method or trigger an error
     *
   	 * @param  string 	The function name
	 * @param  array  	The function arguments
	 * @throws BadMethodCallException 	If method could not be found
	 * @return mixed The result of the function
     */
    public function __call($method, array $arguments)
    {
        if(isset($this->_mixed_methods[$method])) 
        {
            $object = $this->_mixed_methods[$method];
 			$result = null;
 			
 			//Switch the mixin's attached mixer
 			$object->mixer = $this;
 			
			// Call_user_func_array is ~3 times slower than direct method calls. 
 		    switch(count($arguments)) 
 		    { 
 		    	case 0 :
 		    		$result = $object->$method();
 		    		break;
 		    	case 1 : 
 	              	$result = $object->$method($arguments[0]); 
 		           	break; 
 	           	case 2: 
 	               	$result = $object->$method($arguments[0], $arguments[1]); 
 		           	break; 
 		      	case 3: 
 	              	$result = $object->$method($arguments[0], $arguments[1], $arguments[2]); 
 	               	break; 
 	           	default: 
 	             	// Resort to using call_user_func_array for many segments 
 		            $result = call_user_func_array(array($object, $method), $arguments);                               
 	         } 
 	         
        	return $result;
        }
        
      	throw new BadMethodCallException('Call to undefined method :'.$method); 	
    }
}