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
 * Callback Command Mixin
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Mixin
 */
class KMixinCallback extends KMixinAbstract implements KCommandInterface 
{
 	/**
 	 * Array of callbacks
 	 * 
 	 * $var array
 	 */
	protected $_callbacks = array();
	
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
		$config->command_chain->enqueue($this, $config->command_priority);
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
    		'command_chain'		=> null,
    		'command_priority'	=> KCommandChain::PRIORITY_HIGH
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
		$result    = true;
		
		if(isset($this->_callbacks[$name])) 
		{
			$callbacks = $this->_callbacks[$name];
					
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
 	 * Get the registered callbacks for a command
 	 *  	  
 	 * @param  	string	The method to return the functions for
 	 * @return  array	A list of registered functions	
 	 */
	public function getCallbacks($command)
	{
		$result = array();
		$command = strtolower($command);
		
		if (isset($this->_callbacks[$command]) ) {
       	 	$result = $this->_callbacks[$command];
		}
		
    	return $result;
	}
	
	/**
 	 * Registers a callback function
 	 * 
 	 * @param  	string|array	The command name to register the callback for or an array of command names
 	 * @param 	callback		The callback function to register
 	 * @return  KObject	The mixer object
 	 */
	public function registerCallback($commands, $callback)
	{
		$commands = (array) $commands;
		
		foreach($commands as $command)
		{
			$command = strtolower($command);
		
			if (!isset($this->_callbacks[$command]) ) {
       	 		$this->_callbacks[$command] = array();	
			}
		
    		$this->_callbacks[$command][] = $callback;
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
	public function unregisterCallback($commands, $callback)
	{
		$commands  = (array) $commands;
		
		foreach($commands as $command)
		{
			$command = strtolower($command);
			
			if (isset($this->_callbacks[$command]) ) 
			{
				$key = array_search($callback, $this->_callbacks[$command]);
       	 		unset($this->_callbacks[$command][$key]);
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