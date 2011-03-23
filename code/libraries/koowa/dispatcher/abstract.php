<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract controller dispatcher
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Dispatcher
 * @uses		KMixinClass
 * @uses        KObject
 * @uses        KFactory
 */
abstract class KDispatcherAbstract extends KControllerAbstract
{
	/**
	 * Controller object or identifier (APP::com.COMPONENT.controller.NAME)
	 *
	 * @var	string|object
	 */
	protected $_controller;
	
	/**
	 * Default controller name
	 *
	 * @var	string
	 */
	protected $_controller_default;
	
	/**
	 * The request persistency
	 * 
	 * @var boolean
	 */
	protected $_request_persistent;

	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		//Set the request persistency
		$this->_request_persistent = $config->request_persistent;
		
		//Set the controller default
		$this->_controller_default = $config->controller_default;
		
		if($config->controller !== null) {
			$this->setController($config->controller);
		}

		$this->registerCallback('before.dispatch', array($this, 'authorize'));
		
		if(KRequest::method() != 'GET') {
			$this->registerCallback('after.dispatch' , array($this, 'forward'));
	  	}

	    $this->registerCallback('after.dispatch', array($this, 'render'));
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
    	$config->append(array(
        	'controller'			=> null,
    		'controller_default'	=> $this->_identifier->package,
    		'request'				=> KRequest::get('get', 'string'),
    		'request_persistent' 	=> false
        ));

        parent::_initialize($config);
    }

	/**
	 * Method to get a controller identifier
	 *
	 * @return	object	The controller.
	 */
	public function getController()
	{
		if(!$this->_controller)
		{
			$application 	= $this->_identifier->application;
			$package 		= $this->_identifier->package;

			//Get the controller name
			$controller = KRequest::get('get.view', 'cmd', $this->_controller_default);
		
			// Controller names are always singular
			if(KInflector::isPlural($controller)) {
				$controller = KInflector::singularize($controller);
			}
			
			$config = array(
        		'request' 	   => $this->_request,
        		'persistent'   => $this->_request_persistent,
			    'dispatched'   => true	
        	);

			$this->_controller = KFactory::get($application.'::com.'.$package.'.controller.'.$controller, $config);
		}

		return $this->_controller;
	}

	/**
	 * Method to set a controller object attached to the dispatcher
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a controller identifier
	 * @return	KDispatcherAbstract
	 */
	public function setController($controller)
	{
		if(!($controller instanceof KControllerAbstract))
		{
			$identifier = KFactory::identify($controller);

			if($identifier->path[0] != 'controller') {
				throw new KDispatcherException('Identifier: '.$identifier.' is not a controller identifier');
			}

			$this->_controller = $identifier;
		}
		
		$this->_controller = $controller;
		return $this;
	}

	/**
	 * Get the data from the request based the request method
	 *
	 * @return	array 	An array with the request data
	 */
	public function getData()
	{
		$method = KRequest::method();
        $data   = $method != 'GET' ? KRequest::get(strtolower($method), 'raw') : null;
        
        return $data;
	}
	
	/**
	 * Get the action 
	 *
	 * @return	string 	The action to dispatch
	 */
	public function getAction()
	{
        //For none GET requests get the action based on action variable or request method
	    if(KRequest::method() != KHttpRequest::GET) {
            $action = KRequest::get('post.action', 'cmd', strtolower(KRequest::method()));
        } else {
           $action = $this->getController()->getAction();
        }
           
        return $action;
	}

	/**
	 * Dispatch the controller
	 *
	 * @param   object		A command context object
	 * @return	mixed
	 */
	protected function _actionDispatch(KCommandContext $context)
	{        	
	    //Set the default controller
	    if($context->data) {
        	$this->_controller_default = KConfig::toData($context->data);
        }
 
        //Set the date in the context
        $context->data = $this->getData();
         
        //Execute the controller
        $result = $this->getController()->execute($this->getAction(), $context);
        
        //Set the response header
	    if($context->status) {
		    header(KHttpResponse::getHeader($context->status));
		}
		
        return $result;
	}
	
	/**
	 * Authorize the controller dispatching 
	 *
	 * @return  void|false Returns false if the authorization failed
	 * @throws 	KDispatcherException
	 */
	public function _actionAuthorize(KCommandContext $context)
	{
	    return true;
	}

	/**
	 * Forward after a post request
	 *
	 * Either do a redirect or a execute a browse or read action in the controller
	 * depending on the request method and type
	 *
	 * @return mixed
	 */
	public function _actionForward(KCommandContext $context)
	{
		if (KRequest::type() == 'HTTP')
		{
			if($redirect = KFactory::get($this->getController())->getRedirect())
			{
				KFactory::get('lib.koowa.application')
					->redirect($redirect['url'], $redirect['message'], $redirect['type']);
			}
		}

		if(KRequest::type() == 'AJAX')
		{
			$view = KRequest::get('get.view', 'cmd');
			$context->result = KFactory::get($this->getController())->execute('display', $context);
			return $context->result;
		}
	}

	/**
	 * Push the controller data into the document
	 *
	 * This function divert the standard behavior and will push specific controller data
	 * into the document
	 *
	 * @return	mixed
	 */
	protected function _actionRender(KCommandContext $context)
	{
	    if(is_string($context->result)) {
		     return $context->result;
		}
	}
}