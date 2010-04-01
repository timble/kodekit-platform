<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Controller Class
 *
 * Note: Concrete controllers must have a singular name
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Controller
 * @uses		KMixinClass
 * @uses 		KCommandChain
 * @uses        KObject
 * @uses        KFactory
 */
abstract class KControllerAbstract extends KObject implements KObjectIdentifiable
{
	/**
	 * Array of class methods to call for a given action.
	 *
	 * @var	array
	 */
	protected $_action_map = array();

	/**
	 * Current or most recent action to be performed.
	 *
	 * @var	string
	 */
	protected $_action;

	/**
	 * URL for redirection.
	 *
	 * @var	string
	 */
	protected $_redirect = null;

	/**
	 * Redirect message.
	 *
	 * @var	string
	 */
	protected $_redirect_message = null;

	/**
	 * Redirect message type.
	 *
	 * @var	string
	 */
	protected $_redirect_type = 'message';

	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct( KConfig $config = null)
	{
        //If no config is passed create it
		if(!isset($config)) $config = new KConfig();
		
		parent::__construct($config);
        
        // Set the view identifier
		if(!empty($config->view)) {
			$this->setView($config->view);
		}
		
		// Set the model identifier
		if(!empty($config->model)) {
			$this->setModel($config->model);
		}
		
		//Set the action
		$this->_action = $config->action;

        // Mixin a command chain
        $this->mixin(new KMixinCommandchain(new KConfig(
        	array('mixer' => $this, 'command_chain' => $config->command_chain)
        )));

        //Mixin a filter
        $this->mixin(new KMixinCommand(new KConfig(
        	array('mixer' => $this, 'command_chain' => $this->getCommandChain())
        )));
	}


    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'command_chain' =>  new KCommandChain(),
    		'action'		=> null
        ));
        
        parent::_initialize($config);
    }
    
    /**
	 * Get the object identifier
	 * 
	 * @return	KIdentifier	
	 * @see 	KObjectIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Execute an action by triggering a method in the derived class.
	 *
	 * @param	string		The action to execute
	 * @return	mixed|false The value returned by the called method, false in error case.
	 * @throws 	KControllerException
	 */
	public function execute($action = null, $data = null)
	{
		$action = strtolower($action);
		
		//Set the original action in the controller to allow it to be retrieved
		$this->setAction($action);

		//Find the mapped action if one exists
		if (isset( $this->_action_map[$action] )) {
			$action = $this->_action_map[$action];
		}
		
		//Create the command arguments object
		$context = $this->getCommandChain()->getContext();
		$context->caller = $this;
		$context->action = $action;
		$context->data   = $data;
		$context->result = false;
		
		if($this->getCommandChain()->run('controller.before.'.$action, $context) === true) 
		{
			$action = $context->action;
			$method = '_action'.ucfirst($action);
	
			if (!in_array($method, $this->getMethods())) {
				throw new KControllerException("Can't execute '$action', method: '$method' does not exist");
			}
			
			$context->result = $this->$method($data);
			$this->getCommandChain()->run('controller.after.'.$action, $context);
		}

		return $context->result;
	}

	/**
	 * Gets the available actions in the controller.
	 *
	 * @return	array Array[i] of action names.
	 */
	public function getActions()
	{
		$result = array();
		foreach(get_class_methods($this) as $action)
		{
			if(substr($action, 0, 7) == '_action') {
				$result[] = strtolower(substr($action, 7));
			}
			
			$result = array_unique(array_merge($result, array_keys($this->_action_map)));
		}
		return $result;
	}

	/**
	 * Get the action that is was/will be performed.
	 *
	 * @return	 string Action name
	 */
	public function getAction()
	{
		return $this->_action;
	}

	/**
	 * Set the action that will be performed.
	 *
	 * @param	string Action name
	 * @return  KControllerAbstract
	 */
	public function setAction($action)
	{
		$this->_action = $action;
		return $this;
	}

	/**
	 * Register (map) an action to a method in the class.
	 *
	 * @param	string	The action.
	 * @param	string	The name of the method in the derived class to perform
	 *                  for this action.
	 * @return	KControllerAbstract
	 */
	public function registerActionAlias( $alias, $action )
	{
		$this->_action_map[strtolower( $alias )] = $action;
		return $this;
	}

	/**
	 * Unregister (unmap) an action
	 *
	 * @param	string	The action
	 * @return	KControllerAbstract
	 */
	public function unregisterActionAlias( $action )
	{
		unset($this->_action_map[strtolower($action)]);
		return $this;
	}

	/**
	 * Set a URL for browser redirection.
	 *
	 * @param	string URL to redirect to.
	 * @param	string	Message to display on redirect. Optional, defaults to
	 * 			value set internally by controller, if any.
	 * @param	string	Message type. Optional, defaults to 'message'.
	 * @return	KControllerAbstract
	 */
	public function setRedirect( $url, $msg = null, $type = 'message' )
	{
		$this->_redirect   		 = $url;
		$this->_redirect_message = $msg;
		$this->_redirect_type	 = $type;

		return $this;
	}

	/**
	 * Returns an array with the redirect url, the message and the message type
	 *
	 * @return array	Named array containing url, message and messageType, or null if no redirect was set
	 */
	public function getRedirect()
	{
		$result = array();
		
		if(!empty($this->_redirect))
		{
			$url = $this->_redirect;
		
			//Create the url if no full URL was passed
			if(strrpos($url, '?') === false) {
				$url = 'index.php?option=com_'.$this->_identifier->package.'&'.$url;
			}
		
			$result = array(
				'url' 		=> JRoute::_($url, false),
				'message' 	=> $this->_redirect_message,
				'type' 		=> $this->_redirect_type,
			);
		}
		
		return $result;
	}
	
	/**
	 * Executse a controller action by it's name. 
	 * 
	 * @param	string	Method name
	 * @param	array	Array containing all the arguments for the original call
	 * @see execute()
	 */
	public function __call($method, $args)
	{
		if(in_array($method, $this->getActions())) {
			return $this->execute($method, !empty($args) ? $args[0] : null);
		}
		
		return parent::__call($method, $args);
	}
}