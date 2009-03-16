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
	public function __construct(array $options = array())
	{
        // Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KClass
        $this->mixin(new KMixinClass($this, 'Dispatcher'));

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
    protected function _initialize(array $options)
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
	public function dispatch(array $params = array())
	{
		// Set the default view in case no view is passed with the request
		$defaultView = array_key_exists('default_view', $params) ? $params['default_view'] : $this->getClassName('suffix');

		// Require specific controller if requested
		$view		= KInput::get('view', array('post', 'get'), 'cmd', null, $defaultView);
        $controller = KInput::get('controller', array('post', 'get'), 'cmd', null, $view);
        
        // Push the view back in the request in case a default view is used
        KInput::set('view', $view, 'get');

		$path = $this->_basePath.DS.'views';

		//In case we are loading a child view set the view path accordingly
		if(strpos($controller, '.') !== false)
		{
			$result = explode('.', $controller);

			//Set the actual view name
			KInput::set('view', $result[1], 'get');

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
		
        $controller = $this->getController($controller, '', '', $options);

        // Perform the Request task
		$controller->execute(KInput::get('task', array('post', 'get'), 'cmd'));
		
		// Redirect if set by the controller
		$controller->redirect();
	}

	/**
	 * Method to get a controller object, loading it if required.
	 *
	 * @param	string	$view 			The name fo the controller.
	 * @param	string	$component		The name of the component. Optional.
	 * @param	string	$application	The name of the application. Optional.
	 * @param	array	$options        Options array for the controller. Optional.
	 * @return	object	The controller.
	 */
	public function getController( $controller, $component = '', $application = '', array $options = array() )
	{
		$controller = KInflector::singularize($controller);

		if ( empty( $component ) ) {
			$component = $this->getClassName('prefix');
		}
		
		if (empty( $application) )  {
			$application = KFactory::get('lib.joomla.application')->getName();
		}
		
		return KFactory::get($application.'::com.'.$component.'.controller.'.$controller, $options);
	}
}
