<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
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
	 * The default view
	 *
	 * @var array
	 */
	protected $_default_view;
	
	/**
	 * The object identifier
	 *
	 * @var object 
	 */
	protected $_identifier = null;

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_view'
	 */
	public function __construct(array $options = array())
	{
        // Set the objects identifier
        $this->_identifier = $options['identifier'];
		
		// Initialize the options
        $options  = $this->_initialize($options);
        
        // Figure out defaulview if none is set
        $this->_default_view = empty($options['default_view']) ? $this->_identifier->name : $options['default_view'];
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
        	'default_view'  => 'default',
        	'identifier'	=> null
        );

        return array_merge($defaults, $options);
    }
    
	/**
	 * Get the identifier
	 *
	 * @return 	object A KFactoryIdentifier object
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Dispatch the controller and redirect
	 *
	 * @return	this
	 */
	public function dispatch()
	{
		// Require specific controller if requested
		$view = KRequest::get('get.view', 'cmd', $this->_default_view);

        // Push the view back in the request in case a default view is used
        KRequest::set('get.view', $view);

        //Get/Create the controller
        $controller = $this->_getController();

        // Perform the Request action
        $action  = KRequest::get('request.action', 'cmd', null);
        
        //Execute the controller, handle exeception if thrown.
        try
        {
        	$controller->execute($action);
        }
        catch (KControllerException $e)
        {
        	if($e->getCode() == KHttp::STATUS_UNAUTHORIZED)
        	{
				KFactory::get('lib.koowa.application')
					->redirect( 'index.php', JText::_($e->getMessage()) );
        	}
        	else
        	{
        		// rethrow, we don't know what to do with other error codes yet
        		throw $e;
        	}
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
	protected function _getController(array $options = array())
	{
		$application 	= $this->_identifier->application;
		$package 		= $this->_identifier->package;
		$view 			= KRequest::get('get.view', 'cmd');
		$controller 	= KRequest::get('get.controller', 'cmd', $view);

		//In case we are loading a subview, we use the first part of the name as controller name
		if(strpos($controller, '.') !== false)
		{
			$result = explode('.', $controller);

			//Set the controller based on the parent
			$controller = $result[0];
		}

		// Controller names are always singular
		$controller = KInflector::singularize($controller);

		return KFactory::get($application.'::com.'.$package.'.controller.'.$controller, $options);
	}
}
