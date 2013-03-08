<?php
/**
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract controller dispatcher
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 */
abstract class DispatcherAbstract extends ControllerAbstract implements DispatcherInterface
{
	/**
	 * Controller object or identifier
	 *
	 * @var	string|object
	 */
	protected $_controller;

	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional Config object with configuration options.
	 */
	public function __construct(Config $config)
	{
		parent::__construct($config);

		//Set the controller
		$this->_controller = $config->controller;
	}

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Config object with configuration options.
     * @return 	void
     */
    protected function _initialize(Config $config)
    {
        //Create permission identifier
        $permission       = clone $this->getIdentifier();
        $permission->path = array('dispatcher', 'permission');

        $config->append(array(
        	'controller' => $this->getIdentifier()->package,
            'request'    => 'lib://nooku/dispatcher.request',
            'response'   => 'lib://nooku/dispatcher.response',
            'user'       => 'lib://nooku/dispatcher.user',
            'behaviors'  => array($permission),
         ));

        parent::_initialize($config);
    }

    /**
     * Get the request object
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the DispatcherRequestInterface
     * @return DispatcherRequest
     */
    public function getRequest()
    {
        if(!$this->_request instanceof DispatcherRequestInterface)
        {
            $this->_request = parent::getRequest();

            if(!$this->_request instanceof DispatcherRequestInterface)
            {
                throw new \UnexpectedValueException(
                    'Request: '.get_class($this->_request).' does not implement DispatcherRequestInterface'
                );
            }
        }

        return $this->_request;
    }

    /**
     * Get the response object
     *
     * @throws	\UnexpectedValueException	If the response doesn't implement the DispatcherResponseInterface
     * @return DispatcherResponse
     */
    public function getResponse()
    {
        if(!$this->_response instanceof DispatcherResponseInterface)
        {
            $this->_response = parent::getResponse();

            //Set the request in the response
            $this->_response->setRequest($this->getRequest());

            if(!$this->_response instanceof DispatcherResponseInterface)
            {
                throw new \UnexpectedValueException(
                    'Response: '.get_class($this->_response).' does not implement DispatcherResponseInterface'
                );
            }
        }

        return $this->_response;
    }

    /**
     * Get the user object
     *
     * @throws	\UnexpectedValueException	If the user doesn't implement the DispatcherUserInterface
     * @return DispatcherUserInterface
     */
    public function getUser()
    {
        if(!$this->_user instanceof DispatcherUserInterface)
        {
            $this->_user = parent::getUser();

            if(!$this->_user instanceof DispatcherUserInterface)
            {
                throw new \UnexpectedValueException(
                    'User: '.get_class($this->_user).' does not implement DispatcherUserInterface'
                );
            }
        }

        return $this->_user;
    }

	/**
	 * Method to get a controller object
	 *
     * @throws	\UnexpectedValueException	If the controller doesn't implement the ControllerInterface
	 * @return	ControllerAbstract
	 */
	public function getController()
	{
        if(!($this->_controller instanceof ControllerInterface))
		{
		    //Make sure we have a controller identifier
		    if(!($this->_controller instanceof ServiceIdentifier)) {
		        $this->setController($this->_controller);
			}

		    $config = array(
        		'request' 	 => $this->getRequest(),
                'response'   => $this->getResponse(),
                'user'       => $this->getUser(),
			    'dispatched' => true
        	);

			$this->_controller = $this->getService($this->_controller, $config);

            //Make sure the controller implements ControllerInterface
            if(!$this->_controller instanceof ControllerInterface)
            {
                throw new \UnexpectedValueException(
                    'Controller: '.get_class($this->_controller).' does not implement ControllerInterface'
                );
            }
		}

		return $this->_controller;
	}

	/**
	 * Method to set a controller object attached to the dispatcher
	 *
	 * @param	mixed	$controller An object that implements ControllerInterface, ServiceIdentifier object
	 * 					            or valid identifier string
	 * @return	DispatcherAbstract
	 */
	public function setController($controller, $config = array())
	{
		if(!($controller instanceof ControllerInterface))
		{
			if(is_string($controller) && strpos($controller, '.') === false )
		    {
		        // Controller names are always singular
			    if(Inflector::isPlural($controller)) {
				    $controller = Inflector::singularize($controller);
			    }

			    $identifier			= clone $this->getIdentifier();
			    $identifier->path	= array('controller');
			    $identifier->name	= $controller;
			}
		    else $identifier = $this->getIdentifier($controller);

            //Set the configuration
            $this->getService()->setConfig($identifier, $config);

			$controller = $identifier;
		}

		$this->_controller = $controller;

		return $this;
	}
}