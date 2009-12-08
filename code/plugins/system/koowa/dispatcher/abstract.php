<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
abstract class KDispatcherAbstract extends KObject implements KFactoryIdentifiable
{
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
	 * Recognized key values include 'name', 'default_view'
	 */
	public function __construct(array $options = array())
	{
       // Allow the identifier to be used in the initalise function
        $this->_identifier = $options['identifier'];

		// Initialize the options
        $options  = $this->_initialize($options);
        
         // Mixin a command chain
        $this->mixin(new KMixinCommandchain(array('mixer' => $this, 'command_chain' => $options['command_chain'])));
	}

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param	array	Options
     * @return 	array	Options
     */
    protected function _initialize(array $options)
    {
        $defaults = array(
        	'command_chain' =>  new KCommandChain(),
        	'identifier'	=> null
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
	 * Dispatch the controller and redirect
	 * 
	 * @param	string		The controller to dispatch. If null, it will default to
	 * 						retrieve the controller information from the request or
	 * 						default to the component name if no controller info can
	 * 						be found.
	 *
	 * @return	KDispatcherAbstract
	 */
	public function dispatch($controller)
	{
		//Create the controller object
		$controller = $this->_getController($controller);
		
		$context = KFactory::tmp('lib.koowa.command.context');
		$context['caller']     = $this;
		$context['result']     = false;
		$context['controller'] = $controller;
			
		if($this->getCommandChain()->run('dispatcher.before.dispatch', $context) === true) 
		{
			//Execute the controller, handle exeception if thrown. 
        	try
        	{
        		$context['result'] = $controller->execute(KRequest::get('request.action', 'cmd', null));
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
        	
			$this->getCommandChain()->run('dispatcher.after.dispatch', $context);
		}
		
		// Redirect if set by the controller
		if($redirect = $controller->getRedirect())
		{
			KFactory::get('lib.joomla.application')
				->redirect($redirect['url'], $redirect['message'], $redirect['messageType']);
		}

		return $this;
	}

	/**
	 * Method to get a controller object
	 *
	 * @return	object	The controller.
	 */
	protected function _getController($controller, array $options = array())
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
		
		$controller = KFactory::get($application.'::com.'.$package.'.controller.'.$controller, $options);
		return $controller;
	}
}
