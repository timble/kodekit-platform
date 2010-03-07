<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
abstract class KControllerAbstract extends KObject implements KFactoryIdentifiable
{
	/**
	 * Model object or identifier (APP::com.COMPONENT.model.NAME)
	 *
	 * @var	string|object
	 */
	protected $_model;
	
	/**
	 * View object or identifier (APP::com.COMPONENT.view.NAME)
	 *
	 * @var	string|object
	 */
	protected $_view;
	
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
	 * The object identifier
	 *
	 * @var KIdentifierInterface
	 */
	protected $_identifier;

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_action', 'command_chain'
	 * (this list is not meant to be comprehensive).
	 */
	public function __construct( array $options = array() )
	{
        // Allow the identifier to be used in the initalise function
        $this->_identifier = $options['identifier'];

		// Initialize the options
        $options  = $this->_initialize($options);
        
        // Set identifiers
		if(isset($options['view'])) {
			$this->setView($options['view']);
		}
		
		if(isset($options['model'])) {
			$this->setModel($options['model']);
		}

        // Mixin a command chain
        $this->mixin(new KMixinCommandchain(array('mixer' => $this, 'command_chain' => $options['command_chain'])));

        //Mixin a filter
        $this->mixin(new KMixinCommand(array('mixer' => $this, 'command_chain' => $this->getCommandChain())));
	}


    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(array $options)
    {
        $defaults = array(
            'command_chain' =>  new KCommandChain(),
        	'identifier'	=> null,
        	'model'			=> null,
        	'view'			=> null
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Get the identifier
	 *
	 * @return 	KIdentifierInterface
	 * @see 	KFactoryIdentifiable
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
	public function execute($action = null)
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
		$context['caller'] = $this;
		$context['action'] = $action;
		$context['result'] = false;
		
		if($this->getCommandChain()->run('controller.before.'.$action, $context) === true) 
		{
			$action = $context['action'];
			$method = '_action'.ucfirst($action);
	
			if (!in_array($method, $this->getMethods())) {
				throw new KControllerException("Can't execute '$action', method: '$method' does not exist");
			}
			
			$context['result'] = $this->$method();
			$this->getCommandChain()->run('controller.after.'.$action, $context);
		}

		return $context['result'];
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
	 * Get the identifier for the view with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getView()
	{
		if(!$this->_view)
		{
			$identifier			= clone $this->_identifier;
			$identifier->path	= array('view', KRequest::get('get.view', 'cmd', $identifier->name));
			$identifier->name	= KRequest::get('get.format', 'cmd', 'html');
		
			$this->_view = $identifier;
		}	
		
		return $this->_view;
	}
	
	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	An object that implements KFactoryIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a view identifier
	 * @return	KControllerAbstract
	 */
	public function setView($view)
	{
		$identifier = KFactory::identify($view);

		if($identifier->path[0] != 'view') {
			throw new KControllerException('Identifier: '.$identifier.' is not a view identifier');
		}
		
		$this->_view = $identifier;
		return $this;
	}

	/**
	 * Get the identifier for the model with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getModel()
	{
		if(!$this->_model)
		{
			$identifier			= clone $this->_identifier;
			$identifier->path	= array('model');

			// Models are always plural
			$identifier->name	= KInflector::isPlural($identifier->name) ? $identifier->name : KInflector::pluralize($identifier->name);
		
			$this->_model = $identifier;
		}
		
		return $this->_model;
	}
	
	/**
	 * Method to set a model object attached to the controller
	 *
	 * @param	mixed	An object that implements KFactoryIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a model identifier
	 * @return	KControllerAbstract
	 */
	public function setModel($model)
	{
		$identifier = KFactory::identify($model);

		if($identifier->path[0] != 'model') {
			throw new KControllerException('Identifier: '.$identifier.' is not a model identifier');
		}
		
		$this->_model = $identifier;
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
			return $this->execute($method);
		}
		
		return parent::__call($method, $args);
	}
}