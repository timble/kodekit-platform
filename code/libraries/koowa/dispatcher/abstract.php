<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract controller dispatcher
 *
 * @author		Johan Janssens <johan@koowa.org>
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
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		if($config->controller !== null) {
			$this->setController($config->controller);
		}

		if(KRequest::method() != 'GET') {
			$this->registerCallbackAfter('dispatch', array($this, 'forward'));
	  	}

	  	$this->registerCallbackAfter('dispatch', array($this, 'render'));
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
        	'controller'	=> null
        ));

        parent::_initialize($config);
    }

	/**
	 * Method to get a controller identifier
	 *
	 * @return	object	The controller.
	 */
	public function getController($controller = null)
	{
		if($controller && !$this->_controller)
		{
			$application 	= $this->_identifier->application;
			$package 		= $this->_identifier->package;

			//Get the controller name
			$controller = KRequest::get('get.controller', 'cmd', $controller);

			//In case we are loading a subview, we use the first part of the name as controller name
			if(strpos($controller, '.') !== false)
			{
				$result = explode('.', $controller);

				//Set the controller based on the parent
				$controller = $result[0];
			}

			// Controller names are always singular
			if(KInflector::isPlural($controller)) {
				$controller = KInflector::singularize($controller);
			}

			$this->_controller = new KIdentifier($application.'::com.'.$package.'.controller.'.$controller);
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
		$identifier = KFactory::identify($controller);

		if($identifier->path[0] != 'controller') {
			throw new KDispatcherException('Identifier: '.$identifier.' is not a controller identifier');
		}

		$this->_controller = $identifier;
		return $this;
	}

	/**
	 * Get the action that is was/will be performed.
	 *
	 * If the action cannot be found in the POST request it will determined based on the request 
	 * method and mapped to one of the 5 BREAD actions.
	 *
	 * - GET    : either 'browse' (for list views) or 'read' (for item views).
	 * - POST   : add
	 * - PUT    : edit
	 * - DELETE : delete
	 *
	 * @return	 string Action name
	 */
	public function getAction()
	{
		$action = KRequest::get('post.action', 'cmd');

		if(empty($action))
		{
			switch(KRequest::method())
			{
				case 'GET'    :
				{
					//Determine if the action is browse or read based on the view information
					$view   = KRequest::get('get.view', 'cmd');
					$action = KInflector::isPlural($view) ? 'browse' : 'read';
				} break;

				case 'POST'   : $action = 'add';    break;
				case 'PUT'    : $action = 'edit'  ; break;
				case 'DELETE' : $action = 'delete';	break;
			}
		}

		return $action;
	}

	/**
	 * Dispatch the controller
	 *
	 * @param	string		The controller to dispatch. If null, it will default to
	 * 						retrieve the controller information from the request or
	 * 						default to the component name if no controller info can
	 * 						be found.
	 *
	 * @return	KDispatcherAbstract
	 */
	protected function _actionDispatch($controller)
	{
        try
        {
        	$config = array(
        		'request' 	   => KRequest::get('get', 'url'),
        		'persistent'   => true,
        		'auto_display' => true
        	);

        	$data   = KRequest::get('post', 'raw');
        	$action = $this->getAction();

        	$result = KFactory::get($this->getController($controller), $config)->execute($action, $data);
        }
        catch (KControllerException $e)
        {
        	if($e->getCode() == KHttp::STATUS_UNAUTHORIZED)
        	{
				KFactory::get('lib.koowa.application')
					->redirect( 'index.php', JText::_($e->getMessage()) );
        	}
        	// Re-throw, we don't know what to do with other error codes yet
        	else throw $e;
        }

        return $result;
	}

	/**
	 * Forward after a post request
	 *
	 * Either do a redirect or a execute a browse or read action in the controller
	 * depending on the request method and type
	 *
	 * @return void
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
			$context->result = KFactory::get($this->getController())->execute(KInflector::isPlural($view) ? 'browse' : 'read');
		}
	}

	/**
	 * Push the controller data into the document
	 *
	 * This function divert the standard behavior and will push specific controller data
	 * into the document
	 *
	 * @return	KDispatcherDefault
	 */
	protected function _actionRender(KCommandContext $context)
	{
		if(is_string($context->result)) {
			echo $context->result;
		}
	}
}