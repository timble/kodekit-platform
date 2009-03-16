<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Pattern
 * @subpackage	Proxy
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Proxy class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Pattern
 * @subpackage  Proxy
 * @uses 		KObject
 */
abstract class KPatternProxy extends KObject
{
	/**
	 * Name of table object
	 *
	 * @var object
	 */
	protected $_object;

	/**
	 * Constructor
	 *
	 * @param	object	$object	The object to decorate
	 * @return	void
	 */
	public function __construct($object)
	{
		$this->_object = $object;
	}

	/**
	 * Get the decorated object
	 *
	 * @return	object The decorated object
	 */
	public function getObject() {
		return $this->_object;
	}

	/**
	 * Overloaded set function
	 *
	 * @param  string 	$key 	The variable name
	 * @param  mixed 	$value 	The variable value.
	 * @return mixed
	 */
	public function __set($key, $value)
	{
		if ('_' != substr($key, 0, 1)) {
            $this->getObject()->$key = $value;
            return;
        }
	}

	/**
	 * Overloaded get function
	 *
	 * @param  string $key The variable name.
	 * @return mixed
	 */
	public function __get($key)
	{
		if ('_' != substr($key, 0, 1)) {
            return $this->getObject()->$key;
        }
	}

	/**
	 * Overloaded isset function
	 *
	 * Allows testing with empty() and isset() functions
	 *
	 * @param  string 	$name 	The variable name
	 * @param  mixed 	$val 	The variable value.
	 * @return boolean
	 */
	public function __isset($key)
	{
		 if ('_' != substr($key, 0, 1)) {
            return isset($this->getObject()->$key);
        }
	}

	/**
	 * Overloaded isset function
	 *
	 * Allows unset() on object properties to work
	 *
	 * @param string $key The variable name.
	 * @return void
	 */
	public function __unset($key)
	{
		if ('_' != substr($key, 0, 1) && isset($this->getObject()->$key)) {
            unset($this->getObject()->$key);
        }
	}

   	/**
	 * Overloaded call function
	 *
	 * @param  string $method		The function name
	 * @param  array  $arguments	The function arguments
	 * @return mixed The result of the function
	 */
	public function __call($method, array $arguments)
	{
		$object = $this->getObject();
		if(method_exists($object, $method)) 
		{
 			$result = null;
			
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
 	           	case 4: 
 	               	$result = $object->$method($arguments[0], $arguments[1], $arguments[2], $arguments[3]); 
 		            break; 
 	           	default: 
 	             	// Resort to using call_user_func_array for many segments 
 		            $result = call_user_func_array(array($object, $method), $arguments);                               
 	         } 
 	         
 	         return $result;
		}
	
		return parent::__call($method, $arguments);
	}
}
