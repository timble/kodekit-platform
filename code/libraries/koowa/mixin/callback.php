<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Mixin
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Callback Command
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Mixin
 */
class KMixinCallback extends KMixinAbstract implements KCommandInterface 
{
 	/**
 	 * Array of fucntions to be executed on before commands
 	 * 
 	 * $var array
 	 */
	protected $_callbacks_before = array();
	
	/**
 	 * Array of functions to be executed on after commands
 	 * 
 	 * $var array
 	 */
	protected $_callbacks_after = array();

	/**
	 * Object constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
        
		if(is_null($config->command_chain)) {
			throw new KMixinException('command_chain [KCommandChain] option is required');
		}
	
		//Enque the command in the mixer's command chain
		$config->command_chain->enqueue($this, KCommandChain::PRIORITY_HIGH);
	}
	
	/**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'command_chain'	=> null,
    	));
    	
    	parent::_initialize($config);
    }
    
	/**
	 * Command handler
	 * 
	 * @param string  The command name
	 * @param object  The command context
	 *
	 * @return boolean
	 */
	final public function execute( $name, KCommandContext $context) 
	{
		$parts  = explode('.', $name);
		$result = true;
		
		$callbacks = ($parts[1] == 'before') ? $this->_callbacks_before :$this->_callbacks_after;
					
		if (isset($callbacks[$parts[2]]))
		{ 
			$callbacks = $callbacks[$parts[2]];
			
   		 	foreach($callbacks as $callback) 
   		 	{
   		 		$result = call_user_func($callback, $context);
				if ( $result === false) {
        			break;
        		}
   		 	}
		}
		
		return $result === false ? false : true;
	}
	
	/**
 	 * Get the registered before callbacks for a method
 	 *  	  
 	 * @param  	string	The method to return the callbacks for
 	 * @return  array	A list of registered functions	
 	 */
	public function getCallbacksBefore($method)
	{
		$result = array();
		$method = strtolower($method);
		
		if (isset($this->_callbacks_before[$method]) ) {
       	 	$result = $this->_callbacks_before[$method];
		}
		
    	return $result;
	}
	
	/**
 	 * Get the registered after functions for a method
 	 *  	  
 	 * @param  	string	The method to return the functions for
 	 * @return  array	A list of registered functions	
 	 */
	public function getCallbacksAfter($method)
	{
		$result = array();
		$method = strtolower($method);
		
		if (isset($this->_callbacks_after[$method]) ) {
       	 	$result = $this->_callbacks_after[$method];
		}
		
    	return $result;
	}
	
	/**
 	 *  Registers a callback function
 	 * 
 	 * @param  	string|array	The method name to register the callback for or an array of method names
 	 * @param 	callback		The callback function to register
 	 * @return  KObject	The mixer object
 	 */
	public function registerCallbackBefore($methods, $callback)
	{
		$methods = (array) $methods;
		
		foreach($methods as $method)
		{
			$method = strtolower($method);
		
			if (!isset($this->_callbacks_before[$method]) ) {
       	 		$this->_callbacks_before[$method] = array();	
			}
		
    		$this->_callbacks_before[$method][] = $callback;
		}
		
		return $this->_mixer;
	}
	
	/**
 	 * Unregister a callback function
 	 * 
 	 * @param  	string|array	The method name to unregister the callback from or an array of method names
 	 * @param 	callback		The callback function to unregister
 	 * @return  KObject The mixer object
 	 */
	public function unregisterCallbackBefore($methods, $callback)
	{
		$methods  = (array) $methods;
		
		foreach($methods as $method)
		{
			$method = strtolower($method);
			
			if (isset($this->_callbacks_before[$method]) ) 
			{
				$key = array_search($callback, $this->_callbacks_before[$method]);
       	 		unset($this->_callbacks_before[$method][$key]);
			}
		}
		
		return $this->_mixer;
	}
	
	/**
 	 * Registers a callback function
 	 * 
 	 * @param  	string|array	The method name to register the callback too or an array of method names
 	 * @param 	callback		The callback function to register
 	 * @return  KObject The mixer object
 	 */
	public function registerCallbackAfter($methods, $callback)
	{
		$methods   = (array) $methods;
		
		foreach($methods as $method)
		{
			$method = strtolower($method);
		
			if (!isset($this->_callbacks_after[$method]) ) {
       	 		$this->_callbacks_after[$method] = array();	
			}
			
    		$this->_callbacks_after[$method][] = $callback;
		}
			
    	return $this->_mixer;
	}
	
	/**
 	 * Unregister a callback function
 	 * 
 	 * @param  	string|array	The method name to register the function too or an array of method names
 	 * @param 	callback		The callback function to unregister
 	 * @return  KObject The mixer object
 	 */
	public function unregisterCallbackAfter($methods, $callback)
	{
		$methods = (array) $methods;
	
		foreach($methods as $method)
		{
			$method = strtolower($method);
			
			if (isset($this->_callbacks_after[$method]) ) 
			{
       	 		$key = array_search($callback, $this->_callbacks_after[$method]);
       	 		unset($this->_callbacks_after[$method][$key]);
			}
		}
				
		return $this->_mixer;
	}
	
	/**
	 * Get the methods that are available for mixin. 
	 * 
	 * This functions overloads KMixinAbstract::getMixableMethods and excludes the execute()
	 * function from the list of available mixable methods.
	 * 
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null) 
	{
        return array_diff(parent::getMixableMethods(), array('execute'));  
	}
}