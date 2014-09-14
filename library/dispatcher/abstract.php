<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
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
     * List of authenticators
     *
     * Associative array of authenticators, where key holds the authenticator identifier string
     * and the value is an identifier object.
     *
     * @var array
     */
    private $__authenticators;

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

        //Add the authenticators
        $authenticators = (array) ObjectConfig::unbox($config->authenticators);

        foreach ($authenticators as $key => $value)
        {
            if (is_numeric($key)) {
                $this->addAuthenticator($value);
            } else {
                $this->addAuthenticator($key, $value);
            }
        }
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
            'controller'     => $this->getIdentifier()->package,
            'request'        => 'dispatcher.request',
            'response'       => 'dispatcher.response',
            'authenticators' => array()
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
            $this->_request = $this->getObject($this->_request);

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
            $this->_response = $this->getObject($this->_response, array(
                'request' => $this->getRequest(),
                'user'    => $this->getUser(),
            ));

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
     * @param  array  $config  An optional associative array of configuration options
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

                $identifier			= $this->getIdentifier()->toArray();
                $identifier['path']	= array('controller');
                $identifier['name']	= $controller;

                $identifier = $this->getIdentifier($identifier);
            }
            else $identifier = $this->getIdentifier($controller);

            //Set the configuration
            $identifier->getConfig()->append($config);

            $controller = $identifier;
        }

        $this->_controller = $controller;

        return $this;
    }

    /**
     * Get the controller context
     *
     * @return  Command
     */
    public function getContext()
    {
        $context = new DispatcherContext();

        $context->setSubject($this);
        $context->setRequest($this->getRequest());
        $context->setUser($this->getUser());
        $context->setResponse($this->getResponse());

        return $context;
    }

    /**
     * Attach an authenticator
     *
     * @param  mixed $authenticator An object that implements DispatcherAuthenticatorInterface, an ObjectIdentifier
     *                              or valid identifier string
     * @param  array  $config  An optional associative array of configuration options
     * @return DispatcherAbstract
     */
    public function addAuthenticator($authenticator, $config = array())
    {
        //Create the complete identifier if a partial identifier was passed
        if (is_string($authenticator) && strpos($authenticator, '.') === false)
        {
            $identifier = $this->getIdentifier()->toArray();
            $identifier['path'] = array('dispatcher', 'authenticator');
            $identifier['name'] = $authenticator;

            $identifier = $this->getIdentifier($identifier);
        }
        else $identifier = $this->getIdentifier($authenticator);

        if (!isset($this->__authenticators[(string)$identifier]))
        {
            if(!$authenticator instanceof DispatcherAuthenticatorInterface) {
                $authenticator = $this->getObject($identifier, $config);
            }

            if (!($authenticator instanceof DispatcherAuthenticatorInterface))
            {
                throw new \UnexpectedValueException(
                    "Authenticator $identifier does not implement DispatcherAuthenticatorInterface"
                );
            }

            $this->addBehavior($authenticator);

            //Store the authenticator to allow for named lookups
            $this->__authenticators[(string)$identifier] = $authenticator;
        }

        return $this;
    }

    /**
     * Gets the authenticators
     *
     * @return array An array of authenticators
     */
    public function getAuthenticators()
    {
        return $this->__authenticators;
    }

    /**
     * Forward the request
     *
     * Forward to another dispatcher internally. Method makes an internal sub-request, calling the specified
     * dispatcher and passing along the context.
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @throws	\UnexpectedValueException	If the dispatcher doesn't implement the DispatcherInterface
     */
    protected function _actionForward(DispatcherContextInterface $context)
    {
        //Get the dispatcher identifier
        if(is_string($context->param) && strpos($context->param, '.') === false )
        {
            $identifier			   = $this->getIdentifier()->toArray();
            $identifier['package'] = $context->param;
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
     * Handle errors and exceptions
     *
     * @throws \InvalidArgumentException If the action parameter is not an instance of Exception or ExceptionError
     * @param DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionFail(DispatcherContextInterface $context)
    {
        //Check an exception was passed
        if(!isset($context->param) && !$context->param instanceof Exception)
        {
            throw new \InvalidArgumentException(
                "Action parameter 'exception' [Exception] is required"
            );
        }

        //Get the exception object
        if($context->param instanceof EventException) {
            $exception = $context->param->getException();
        } else {
            $exception = $context->param;
        }

        //If the error code does not correspond to a status message, use 500
        $code = $exception->getCode();
        if(!isset(HttpResponse::$status_messages[$code])) {
            $code = '500';
        }

        //Get the error message
        $message = HttpResponse::$status_messages[$code];

        //Set the response status
        $context->response->setStatus($code , $message);

        //Send the response
        $this->send($context);
    }

    /**
     * Dispatch the request
     *
     * Dispatch to a controller internally. Functions makes an internal sub-request, based on the information in
     * the request and passing along the context.
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return	mixed
     */
    protected function _actionDispatch(DispatcherContextInterface $context)
    {
        //Send the response
        $this->send($context);
    }

    /**
     * Send the response
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionSend(DispatcherContextInterface $context)
    {
        $context->response->send();
    }
}