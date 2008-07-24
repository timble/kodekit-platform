<?php
/**
 * @version		$Id:object.php 46 2008-03-01 18:39:32Z mjaz $
 * @package		Koowa
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Object class
 *
 * Provides getters and setters, error handling, mixin features
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package		Koowa
 * @example		mixins.php	Mixin example
 */
class KObject
{
    /**
	 * An array of errors
	 *
	 * @var		array of error messages
     */
	protected $_errors = array();

    /**
     * Mixed in objects
     *
     * @var array
     */
    protected $_mixinObjects = array();

    /**
     * Mixed in methods
     *
     * @var array
     */
    protected $_mixinMethods = array();

    /**
     * Returns a property of the object or the default value if the property is not set.
     *
     * @param   string $property The name of the property
     * @param   mixed  $default The default value
     * @return  mixed The value of the property
     */
    public function get($property, $default=null)
    {
        if(isset($this->$property)) {
            return $this->$property;
        }
        return $default;
    }

    /**
     * Returns an associative array of object properties
     *
     * @return  array
     */
    public function getProperties()
    {
        $vars  = get_object_vars($this);

        foreach ($vars as $key => $value)
        {
            if ('_' == substr($key, 0, 1)) {
                unset($vars[$key]);
            }
        }

        return $vars;
    }

	/**
	 * Get the most recent error message
	 *
	 * @param	integer	$i Option error index
	 * @param	boolean	$toString Indicates if JError objects should return their error message
	 * @return	string	Error message
	 */
	public function getError($i = null, $toString = true )
	{
		// Find the error
		if ( $i === null)
        {
			// Default, return the last message
			$error = end($this->_errors);
		}
		elseif( !array_key_exists($i, $this->_errors ))
        {
			// If $i has been specified but does not exist, return false
			return false;
		}
		else
        {
			$error	= $this->_errors[$i];
		}

		// Check if only the string is requested
		if ( JError::isError($error) && $toString )
        {
			return $error->toString();
		}

		return $error;
	}

	/**
	 * Return all errors, if any
	 *
	 * @return	array	Array of error messages or JErrors
	 */
	public function getErrors()
	{
		return $this->_errors;
	}
	
	/**
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
     * Modifies a property of the object, creating it if it does not already exist.
     *
     * @param   string $property The name of the property
     * @param   mixed  $value The value of the property to set
     * @return  this
     */
    public function set( $property, $value = null )
    {
        if('_' == substr($property, 0, 1)) {
        	throw new KException("Protected or private properties can't be set outside of object scope in ".get_class($this));
        }
        $this->$property = $value;
        return $this;
    }

    /**
    * Set the object properties based on a named array/hash
    *
    * @param    $array  mixed Either and associative array or another object
    * @return   this
    */
    public function setProperties( $properties )
    {
        $properties = (array) $properties;

        foreach ($properties as $k => $v) {
            $this->set($k, $v);
        }

        return $this;
    }

	/**
	 * Add an error message
	 *
	 * @param	string $error Error message
     * @return 	this
	 */
	public function setError($error)
	{
		array_push($this->_errors, $error);

        return $this;
	}

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed
     * in objects, in a LIFO order
     *
     * @param	object
     * @return	this
     */
    public function mixin($object)
    {
        array_unshift($this->_mixinObjects, $object);

        $remove = array('__construct', '__destruct');
        $methods = array_diff(get_class_methods($object), get_class_methods($this), $remove);

        foreach($methods as $method) {
            $this->_mixinMethods[$method] = $object;
        }

        return $this;
    }

    /**
     * Search the method map, and call the method or trigger an error
     *
   	 * @param  string $function		The function name
	 * @param  array  $arguments	The function arguments
	 * @return mixed The result of the function
     */
    public function __call($method, $args)
    {
        if(isset($this->_mixinMethods[$method])) {
            return call_user_func_array(array($this->_mixinMethods[$method], $method), $args);
        }

        $trace = debug_backtrace();
        trigger_error("Call to undefined method {$trace[1]['class']}::$method()", E_USER_ERROR);
    }
}
