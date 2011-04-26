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
		
		//Set the controller
		$this->_controller = $config->controller;
		
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
        	'controller'			=> $this->_identifier->package,
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
		if(!$this->_controller instanceof KControllerAbstract)
		{
		    if(isset($this->_request->view)) { 
		        $this->_controller = $this->_request->view;
		    }
		   
		    if(is_string($this->_controller) && strpos($this->_controller, '.') === false ) 
		    {
		        // Controller names are always singular
			    if(KInflector::isPlural($this->_controller)) {
				    $this->_controller = KInflector::singularize($this->_controller);
			    } 
			    
		        //Created the identifier
			    $identifier			= clone $this->_identifier;
			    $identifier->path	= array('controller');
			    $identifier->name	= $this->_controller;
			}
		    
			$config = array(
        		'request' 	   => $this->_request,
        		'persistent'   => $this->_request_persistent,
			    'dispatched'   => true	
        	);
        
			$this->_controller = KFactory::tmp($identifier, $config);
		}
	
		return $this->_controller;
	}

	/**
	 * Method to set a controller object attached to the dispatcher
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDispatcherException	If the identifier is not a controller identifier
	 * @return	KDispatcherAbstract or KIdentifier object
	 */
	public function setController($controller)
	{
		if(!($controller instanceof KControllerAbstract))
		{
			$identifier = KFactory::identify($controller);

			if($identifier->path[0] != 'controller') {
				throw new KDispatcherException('Identifier: '.$identifier.' is not a controller identifier');
			}

			$controller = $identifier;
		}
		
		$this->_controller = $controller;
	
		return $this;
	}
	
	/**
	 * Set the request information
	 *
	 * @param array	An associative array of request information
	 * @return KControllerBread
	 */
	public function setRequest(array $request)
	{
		$this->_request = new KConfig();
		foreach($request as $key => $value) {
		    $this->$key = $value;
		}
		
		return $this;
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
	        $this->_controller = KConfig::toData($context->data);
	    }
	    
	    //Execute the controller
	    $action = KRequest::get('post.action', 'cmd', strtolower(KRequest::method()));
	    
	    if(KRequest::method() != KHttpRequest::GET) {
            $context->data = KRequest::get(strtolower(KRequest::method()), 'raw');;
        }
	     
	    $result = $this->getController()->execute($action, $context);
	           
        return $result;
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
			if($redirect = $this->getController()->getRedirect())
			{
			    KFactory::get('lib.joomla.application')
					->redirect($redirect['url'], $redirect['message'], $redirect['type']);
			}
		}

		if(KRequest::type() == 'AJAX')
		{
			$view = KRequest::get('get.view', 'cmd');
			$context->result = $this->getController()->execute('display', $context);
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
	    //Headers
	    if($context->headers) 
	    {
	        foreach($context->headers as $name => $value) {
	            header($name.' : '.$value);
	        }
	    }
	    
	    //Status
        if($context->status) {
           header(KHttpResponse::getHeader($context->status));
        }
	    
	    if(is_string($context->result)) {
		     return $context->result;
		}
	}
}