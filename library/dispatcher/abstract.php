<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Dispatcher
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
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
	 * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
	 */
	public function __construct(ObjectConfig $config)
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
     * @param ObjectConfig $config 	An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
        	'controller' => $this->getIdentifier()->package,
            'request'    => 'dispatcher.request',
            'response'   => 'dispatcher.response',
            'user'       => 'dispatcher.user',
            'behaviors'  => array('permissible'),
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
		    if(!($this->_controller instanceof ObjectIdentifier)) {
		        $this->setController($this->_controller);
			}

		    $config = array(
        		'request' 	 => $this->getRequest(),
                'user'       => $this->getUser(),
                'response'   => $this->getResponse(),
			    'dispatched' => true
        	);

			$this->_controller = $this->getObject($this->_controller, $config);

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
	 * @param	mixed	$controller An object that implements ControllerInterface, ObjectIdentifier object
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
			    if(StringInflector::isPlural($controller)) {
				    $controller = StringInflector::singularize($controller);
			    }

			    $identifier			= clone $this->getIdentifier();
			    $identifier->path	= array('controller');
			    $identifier->name	= $controller;
			}
		    else $identifier = $this->getIdentifier($controller);

            //Set the configuration
            $identifier->setConfig($config);

			$controller = $identifier;
		}

		$this->_controller = $controller;

		return $this;
	}

    /**
     * Forward the request
     *
     * Forward to another dispatcher internally. Method makes an internal sub-request, calling the specified
     * dispatcher and passing along the context.
     *
     * @param CommandContext $context	A command context object
     * @throws	\UnexpectedValueException	If the dispatcher doesn't implement the DispatcherInterface
     */
    protected function _actionForward(CommandContext $context)
    {
        //Get the dispatcher identifier
        if(is_string($context->param) && strpos($context->param, '.') === false )
        {
            $identifier			 = clone $this->getIdentifier();
            $identifier->package = $context->param;
        }
        else $identifier = $this->getIdentifier($context->param);

        //Create the dispatcher
        $config = array(
            'request' 	 => $context->request,
            'response'   => $context->response,
            'user'       => $context->user,
        );

        $dispatcher = $this->getObject($identifier, $config);

        if(!$dispatcher instanceof DispatcherInterface)
        {
            throw new \UnexpectedValueException(
                'Dispatcher: '.get_class($dispatcher).' does not implement DispatcherInterface'
            );
        }

        $dispatcher->dispatch($context);
    }

    /**
     * Dispatch the request
     *
     * Dispatch to a controller internally. Functions makes an internal sub-request, based on the information in
     * the request and passing along the context.
     *
     * @param   CommandContext	$context A command context object
     * @return	mixed
     */
    protected function _actionDispatch(CommandContext $context)
    {
        //Send the response
        $this->send($context);
    }

    /**
     * Redirect
     *
     * Redirect to a URL externally. Method performs a 301 (permanent) redirect. Method should be used to immediately
     * redirect the dispatcher to another URL after a GET request.
     *
     * @param CommandContext $context   A command context object
     * @throws	\UnexpectedValueException	If the dispatcher doesn't implement the DispatcherInterface
     */
    protected function _actionRedirect(CommandContext $context)
    {
        $url = $context->param;

        $context->response->setStatus(DispatcherResponse::MOVED_PERMANENTLY);
        $context->response->setRedirect($url);
        $this->send();

        return false;
    }

    /**
     * Send the response
     *
     * @param CommandContext $context	A command context object
     */
    public function _actionSend(CommandContext $context)
    {
        $context->response->send();
        exit(0);
    }
}