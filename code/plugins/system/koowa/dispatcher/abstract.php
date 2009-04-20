<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract controller dispatcher
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Dispatcher
 * @uses		KMixinClass
 * @uses        KObject
 * @uses        KFactory
 */
abstract class KDispatcherAbstract extends KObject
{
	/**
	 * Options
	 *
	 * @var array
	 */
	protected $_options;
	
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_view'
	 */
	public function __construct(array $options = array())
	{
        // Initialize the options
        $this->_options  = $this->_initialize($options);
         
        // Mixin the KClass
        $this->mixin(new KMixinClass($this, 'Dispatcher'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);
        
        // Figure out default view if none is set
        $this->_options['default_view'] = empty($this->_options['default_view']) ? $this->getClassName('suffix') : $this->_options['default_view'];
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
        	'default_view'  => '',
            'name'          => array(
                        'prefix'    => 'k',
                        'base'      => 'dispatcher',
                        'suffix'    => 'default'
                        )
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Dispatch the controller and redirect
	 * 
	 * @return	this
	 */
	public function dispatch()
	{
		// Require specific controller if requested
		$view		= KInput::get('view', 'get', 'cmd', null, $this->_options['default_view']);
        
        // Push the view back in the request in case a default view is used
        KInput::set('view', $view, 'get');

        //Get/Create the controller
        $controller = $this->getController();
        
        // Perform the Request action
        $controller->execute(KInput::get('action', array('post', 'get'), 'cmd', 'cmd'));
        
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
	public function getController(array $options = array())
	{
		$application 	= KFactory::get('lib.joomla.application')->getName();
		$component 		= $this->getClassName('prefix');
		$view 			= KInput::get('view', 'get', 'cmd');
		$controller 	= KInput::get('controller', 'get', 'cmd', null, $view);
		
		//In case we are loading a subview, we use the first part of the name as controller name
		if(strpos($controller, '.') !== false)
		{
			$result = explode('.', $controller);

			//Set the controller based on the parent
			$controller = $result[0];
		}

		// Controller names are always singular
		$controller = KInflector::singularize($controller);

		return KFactory::get($application.'::com.'.$component.'.controller.'.$controller, $options);
	}
}
