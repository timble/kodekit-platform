<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract View Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KInflector
 */
abstract class KControllerPage extends KControllerAbstract
{
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
	 * View object or identifier (APP::com.COMPONENT.view.NAME.FORMAT)
	 *
	 * @var	string|object
	 */
	protected $_view;
	
	/**
	 * Model object or identifier (APP::com.COMPONENT.model.NAME)
	 *
	 * @var	string|object
	 */
	protected $_model;

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		 // Set the view identifier
		$this->_view = $config->view;
		
	    // Set the model identifier
		$this->_model = $config->model;
		
		//Register display as alias for get
		$this->registerActionAlias('display', 'get');
		
		//Enqueue the authorization command
        $command = clone $this->_identifier;
	    $command->path[] = 'command';
		$command->name = 'authorize';

        $this->getCommandChain()->enqueue( KFactory::get($command) );
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
    	    'model'	          => null,
        	'view'	          => $this->_identifier->name,
        ));

        parent::_initialize($config);
    }
    
 	/**
	 * Push the request data into the model state
	 *
	 * @param	string		The action to execute
	 * @return	mixed|false The value returned by the called method, false in error case.
	 * @throws 	KControllerException
	 */
	public function execute($action, $data = null)
	{
		$this->getModel()->set($this->getRequest());

		return parent::execute($action, $data);
	}
    
	/**
	 * Get the view object attached to the controller
	 *
	 * @return	KViewAbstract
	 */
	public function getView()
	{
	    if(!$this->_view instanceof KViewAbstract)
		{	
		    if(isset($this->_request->view)) { 
		        $this->_view = $this->_request->view;
		    }
		     
		    if(is_string($this->_view) && strpos($this->_view, '.') === false ) 
		    {
			    $identifier			= clone $this->_identifier;
			    $identifier->path	= array('view', $this->_view);
			    $identifier->name	= KRequest::format() ? KRequest::format() : 'html';
			}
		    
			//Enable the auto-filtering if the controller was dispatched or if the MVC triad was
			//called outside of the dispatcher.
			$config = array(
			    'auto_filter'  => $this->isDispatched() || !KFactory::has('dispatcher')
        	);
        	
			$this->_view = KFactory::tmp($identifier, $config);
		}
		
		return $this->_view;
	}

	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a view identifier
	 * @return	KControllerAbstract
	 */
	public function setView($view)
	{
		if(!($view instanceof KViewAbstract))
		{
			$identifier = KFactory::identify($view);

			if($identifier->path[0] != 'view') {
				throw new KControllerException('Identifier: '.$identifier.' is not a view identifier');
			}

			$view = $identifier;
		}
		
		$this->_view = $view;
		
		return $this;
	}
	
	/**
	 * Get the model object attached to the contoller
	 *
	 * @return	KModelAbstract
	 */
	public function getModel()
	{
		if(!$this->_model instanceof KModelAbstract)
		{
			$identifier = isset($this->_model) ? $this->_model : $this->_identifier->name;
			
            if(is_string($identifier) && strpos($identifier, '.') === false ) 
		    {
			    $model = $identifier;
			    
			     // Model names are always plural
			    if(KInflector::isSingular($model)) {
				    $model = KInflector::pluralize($model);
			    } 
		        
		        //Created the identifier
			    $identifier			= clone $this->_identifier;
			    $identifier->path	= array('model');
			    $identifier->name	= $model;
			}
			
			$this->_model = KFactory::tmp($identifier);
		}

		return $this->_model;
	}

	/**
	 * Method to set a model object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a model identifier
	 * @return	KControllerAbstract
	 */
	public function setModel($model)
	{
		if(!($model instanceof KModelAbstract))
		{
			$identifier = KFactory::identify($model);

			if($identifier->path[0] != 'model') {
				throw new KControllerException('Identifier: '.$identifier.' is not a model identifier');
			}

			$model = $identifier;
		}
		
		$this->_model = $model;
		
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
			$result = array(
				'url' 		=> JRoute::_($this->_redirect, false),
				'message' 	=> $this->_redirect_message,
				'type' 		=> $this->_redirect_type,
			);
		}

		return $result;
	}
	
	/**
	 * Specialised display function.
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	string|false 	The rendered output of the view or false if something went wrong
	 */
	protected function _actionGet(KCommandContext $context)
	{
		$view = $this->getView();
	
        //Set the layout in the view
	    if(isset($this->_request->layout)) {
            $view->setLayout($this->_request->layout);
	     }
	     
        //Render the view and return the output
		return $view->display();
	}
	
	/**
	 * Get a list of allowed actions
	 *
     * @return  string    The allowed actions; e.g., `GET, POST [add, edit, cancel, save], PUT, DELETE`
	 */
	protected function _actionOptions(KCommandContext $context)
	{
	    $methods = array();
        
        //Remove GET actions
        $actions = array_diff($this->getActions(), array('browse', 'read', 'display'));
          
        //Authorize the action
        foreach($actions as $key => $action)
        {
            //Find the mapped action if one exists
            if (isset( $this->_action_map[$action] )) {
                $action = $this->_action_map[$action];
            }
        
            //Check if the action can be executed
            if($this->getCommandChain()->run('before.'.$action, $context) === false) {
                unset($actions[$key]);
            } 
        }
          
        //Sort the action alphabetically.
        sort($actions);
	              
        //Retrieve HTTP methods
        foreach(array('get', 'put', 'delete', 'post', 'options') as $method) 
        {
            if(in_array($method, $actions)) {
                $methods[strtoupper($method)] = $method;
            }
        }
            
        //Retrieve POST actions 
        if(in_array('post', $methods)) 
        {
            $actions = array_diff($actions, array('get', 'put', 'delete', 'post', 'options'));
            $methods['POST'] = array_diff($actions, $methods);
        }
       
        //Render to string
        $result = implode(', ', array_keys($methods));
        
        foreach($methods as $method => $actions) 
        {
           if(is_array($actions)) {
               $result = str_replace($method, $method.' ['.implode(', ', $actions).']', $result);
           }     
        }
        
        $context->headers = array('Allow' => $result); 
	}

	/**
	 * Supports a simple form Fluent Interfaces. Allows you to set the request 
	 * properties by using the request property name as the method name.
	 *
	 * For example : $controller->view('name')->layout('form')->display();
	 *
	 * @param	string	Method name
	 * @param	array	Array containing all the arguments for the original call
	 * @return	KControllerBread
	 *
	 * @see http://martinfowler.com/bliki/FluentInterface.html
	 */
	public function __call($method, $args)
	{
		//Check first if we are calling a mixed in method.
		if(!isset($this->_mixed_methods[$method])) 
        {
			if(in_array($method, array('layout', 'view', 'format'))) 
			{
				$this->$method = $args[0];
				return $this;
			}
        }
		
		return parent::__call($method, $args);
	}
}