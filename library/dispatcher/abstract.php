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

        //Register the default exception handler
        $this->addEventListener('onException', array($this, 'fail'), Event::PRIORITY_LOW);
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

			    $identifier			= $this->getIdentifier()->toArray();
			    $identifier['path']	= array('controller');
			    $identifier['name']	= $controller;

                $identifier = $this->getIdentifier($identifier);
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

        $status = 0;
        if(!$context->response->isSuccess) {
            $status = (int) $context->response->getStatusCode();
        }

        exit($status);
    }
}