<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Dispatcher class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_Dispatcher
 * @uses		KPatternClass
 */

abstract class KDispatcherAbstract extends KObject
{
	/**
	 * The base path
	 *
	 * @var		string
	 */
	protected $_basePath;

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'base_path'
	 */
	public function __construct($options = array())
	{
        // Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KClass
        $this->mixin(new KPatternClass($this, 'Dispatcher'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Set a base path for use by the dispatcher
		$this->_basePath	= $options['base_path'];
	}

    /**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param	array	Options
     * @return 	array	Options
     */
    protected function _initialize($options)
    {
        $defaults = array(
            'base_path'     => JPATH_COMPONENT,
            'name'          => array(
                        'prefix'    => 'k',
                        'base'      => 'dispatcher',
                        'suffix'    => 'default'
                        )
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Typical dispatch method for MVC based architecture
	 *
	 * This function is provide as a default implementation, in most cases
	 * you will need to override it in your own dispatchers.
	 *
	 * @param	array An optional associative array of parameters to be passed in
	 */
	public function dispatch($params = array())
	{
		// Set the default view in case no view is passed with the request
		$defaultView = array_key_exists('default_view', $params) ? $params['default_view'] : $this->getClassName('suffix');

		// Require specific controller if requested
		$view		= JRequest::getCmd('view', $defaultView);
        $controller = JRequest::getCmd('controller', $view);
        // Push the view back in the request in case a default view is used
        JRequest::setVar('view', $view);

		$path       = $this->_basePath.DS.'views';

		//In case we are loading a child view set the view path accordingly
		if(strpos($controller, '.') !== false)
		{
			$result = explode('.', $controller);

			//Set the actual view name
			JRequest::setVar('view', $result[1]);

			//Set the controller based on the parent
			$controller = $result[0];

			//Set the path for the child view
			$path .= DS.$result[0];
		}
		
		// Get/Create the controller
		$options =  array(
			'base_path' => $this->_basePath.DS.'controllers',
			'view_path' => $path
		);
		
        $controller = $this->getController($controller, '', $options);
        
		// Perform the Request task
		$controller->execute(JRequest::getCmd('task'));
		
		// Redirect if set by the controller
		$controller->redirect();
	}

	/**
	 * Method to get a controller object, loading it if required.
	 *
	 * @param	string	The controller name.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Options array for the controller. Optional.
	 * @return	object	The controller.
	 */
	public function getController( $name, $prefix = '', $options = array() )
	{
		$name = KInflector::singularize($name);

		if ( empty( $prefix ) ) {
			$prefix = $this->getClassName('prefix');
		}

		$object = array(
			'type' 		=> 'controller'  ,
			'component'	=> $prefix,
			'name'		=> $name
		);

		$controller = KFactory::getInstance($object, $options);
		return $controller;
	}
}
