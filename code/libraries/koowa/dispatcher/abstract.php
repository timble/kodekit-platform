<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract controller dispatcher
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 */
abstract class KDispatcherAbstract extends KControllerAbstract
{
	/**
	 * Controller object or identifier (com://APP/COMPONENT.controller.NAME)
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

		//Set the controller
		$this->_controller = $config->controller;
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
        	'controller' => $this->getIdentifier()->package,
            'request'    => 'koowa:dispatcher.request',
            'response'   => 'koowa:dispatcher.response',
            'user'       => 'koowa:dispatcher.user',
         ));

        parent::_initialize($config);
    }

    /**
     * Get the request object
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the KDispatcherRequestInterface
     * @return KDispatcherRequest
     */
    public function getRequest()
    {
        if(!$this->_request instanceof KDispatcherRequestInterface)
        {
            $this->_request = parent::getRequest();

            if(!$this->_request instanceof KDispatcherRequestInterface)
            {
                throw new \UnexpectedValueException(
                    'Request: '.get_class($this->_request).' does not implement KDispatcherRequestInterface'
                );
            }
        }

        return $this->_request;
    }

    /**
     * Get the response object
     *
     * @throws	\UnexpectedValueException	If the response doesn't implement the KDispatcherResponseInterface
     * @return KDispatcherResponse
     */
    public function getResponse()
    {
        if(!$this->_response instanceof KDispatcherResponseInterface)
        {
            $this->_response = parent::getResponse();

            //Set the request in the response
            $this->_response->setRequest($this->getRequest());

            if(!$this->_response instanceof KDispatcherResponseInterface)
            {
                throw new \UnexpectedValueException(
                    'Response: '.get_class($this->_response).' does not implement KDispatcherResponseInterface'
                );
            }
        }

        return $this->_response;
    }

    /**
     * Get the user object
     *
     * @throws	\UnexpectedValueException	If the user doesn't implement the KDispatcherUserInterface
     * @return KDispatcherUserInterface
     */
    public function getUser()
    {
        if(!$this->_user instanceof KDispatcherUserInterface)
        {
            $this->_user = parent::getUser();

            if(!$this->_user instanceof KDispatcherUserInterface)
            {
                throw new \UnexpectedValueException(
                    'User: '.get_class($this->_user).' does not implement KDispatcherUserInterface'
                );
            }
        }

        return $this->_user;
    }

	/**
	 * Method to get a controller object
	 *
     * @throws	\UnexpectedValueException	If the controller doesn't implement the KControllerInterface
	 * @return	KControllerAbstract
	 */
	public function getController()
	{
        if(!($this->_controller instanceof KControllerInterface))
		{
		    //Make sure we have a controller identifier
		    if(!($this->_controller instanceof KServiceIdentifier)) {
		        $this->setController($this->_controller);
			}

		    $config = array(
        		'request' 	 => $this->getRequest(),
                'response'   => $this->getResponse(),
                'user'       => $this->getUser(),
			    'dispatched' => true
        	);

			$this->_controller = $this->getService($this->_controller, $config);

            //Make sure the controller implements KControllerInterface
            if(!$this->_controller instanceof KControllerInterface)
            {
                throw new \UnexpectedValueException(
                    'Controller: '.get_class($this->_controller).' does not implement KControllerInterface'
                );
            }
		}

		return $this->_controller;
	}

	/**
	 * Method to set a controller object attached to the dispatcher
	 *
	 * @param	mixed	$controller An object that implements KControllerInterface, KServiceIdentifier object
	 * 					            or valid identifier string
	 * @return	KDispatcherAbstract
	 */
	public function setController($controller, $config = array())
	{
		if(!($controller instanceof KControllerInterface))
		{
			if(is_string($controller) && strpos($controller, '.') === false )
		    {
		        // Controller names are always singular
			    if(KInflector::isPlural($controller)) {
				    $controller = KInflector::singularize($controller);
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