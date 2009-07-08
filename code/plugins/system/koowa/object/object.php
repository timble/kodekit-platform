<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Object
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
     * Modifies a property of the object, creating it if it does not already exist.
     *
     * @param   string $property The name of the property
     * @param   mixed  $value The value of the property to set
     * @throws KObjectException
     * @return  this
     */
    public function set( $property, $value = null )
    {
        if('_' == substr($property, 0, 1)) {
        	throw new KObjectException("Protected or private properties can't be set outside of object scope in ".get_class($this));
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
