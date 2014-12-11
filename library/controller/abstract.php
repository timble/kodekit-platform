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
 * Abstract Controller
 *
 * Note: Concrete controllers must have a singular name
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerAbstract extends Object implements ControllerInterface, CommandCallbackDelegate
{
    /**
     * The controller actions
     *
     * @var array
     */
    protected $_actions = array();

    /**
     * Response object or identifier
     *
     * @var	string|object
     */
    protected $_response;

    /**
     * Request object or identifier
     *
     * @var	string|object
     */
    protected $_request;

    /**
     * User object or identifier
     *
     * @var	string|object
     */
    protected $_user;

    /**
     * Has the controller been dispatched
     *
     * @var boolean
     */
    protected $_dispatched;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the dispatched state
        $this->_dispatched = $config->dispatched;

        //Force load the controller actions
        $this->_actions = $this->getActions();

        // Set the model identifier
        $this->_request = $config->request;

        // Set the view identifier
        $this->_response = $config->response;

        // Set the user identifier
        $this->_user = $config->user;

        // Mixin the behavior (and command) interface
        $this->mixin('lib:behavior.mixin', $config);

        // Mixin the event interface
        $this->mixin('lib:event.mixin', $config);
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'command_chain'    => 'lib:command.chain',
            'command_handlers' => array('lib:command.handler.event'),
            'dispatched'       => false,
            'request'          => 'lib:controller.request',
            'response'         => 'lib:controller.response',
            'user'             => 'lib:user',
            'behaviors'        => array('permissible'),
        ));

        parent::_initialize($config);
    }

    /**
     * Has the controller been dispatched
     *
     * @return  boolean    Returns true if the controller has been dispatched
     */
    public function isDispatched()
    {
        return $this->_dispatched;
    }

    /**
     * Execute an action by triggering a method in the derived class.
     *
     * @param   string            $action  The action to execute
     * @param   ControllerContextInterface $context A controller context object
     * @throws  ControllerException If the action method doesn't exist
     * @return  mixed|false The value returned by the called method, false in error case.
     */
    public function execute($action, ControllerContextInterface $context)
    {
        $action = strtolower($action);

        //Set the context subject
        $context_subject = $context->getSubject();
        $context->setSubject($this);

        //Set the context action
        $context_action = $context->getAction();
        $context->setAction($action);

        if($this->invokeCommand('before.'.$action, $context) !== false)
        {
            $method = '_action' . ucfirst($action);

            if (!method_exists($this, $method))
            {
                if (isset($this->_mixed_methods[$action]))
                {
                    $context->setName('action.' . $action);
                    $context->result = $this->_mixed_methods[$action]->execute($context, $this->getCommandChain());
                }
                else
                {
                    throw new ControllerExceptionActionNotImplemented(
                        "Can't execute '$action', method: '$method' does not exist"
                    );
                }
            }
            else  $context->result = $this->$method($context);

            $this->invokeCommand('after.'.$action, $context);
        }

        //Reset the context subject
        $context->setSubject($context_subject);
        $context->setAction($context_action);

        return $context->result;
    }

    /**
     * Invoke a command handler
     *
     * @param string            $method    The name of the method to be executed
     * @param CommandInterface  $command   The command
     * @return mixed Return the result of the handler.
     */
    public function invokeCommandCallback($method, CommandInterface $command)
    {
        return $this->$method($command);
    }

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed in objects, in a LIFO order.
     *
     * @@param   mixed  $mixin  An object that implements ObjectMixinInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return  Object
     */
    public function mixin($mixin, $config = array())
    {
        if ($mixin instanceof ControllerBehaviorAbstract)
        {
            foreach ($mixin->getMethods() as $method)
            {
                if (substr($method, 0, 7) == '_action') {
                    $this->_actions[] = strtolower(substr($method, 7));
                }
            }

            $this->_actions = array_unique($this->_actions);
        }

        return parent::mixin($mixin, $config);
    }

    /**
     * Gets the available actions in the controller.
     *
     * @return  array Array[i] of action names.
     */
    public function getActions()
    {
        if (!$this->_actions)
        {
            $this->_actions = array();

            foreach ($this->getMethods() as $method)
            {
                if (substr($method, 0, 7) == '_action') {
                    $this->_actions[] = strtolower(substr($method, 7));
                }
            }

            $this->_actions = array_unique($this->_actions);
        }

        return $this->_actions;
    }

    /**
     * Set the request object
     *
     * @param ControllerRequestInterface $request A request object
     * @return ControllerAbstract
     */
    public function setRequest(ControllerRequestInterface $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Get the request object
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the ControllerRequestInterface
     * @return ControllerRequestInterface
     */
    public function getRequest()
    {
        if(!$this->_request instanceof ControllerRequestInterface)
        {
            $this->_request = $this->getObject($this->_request,  array(
                'url'  => $this->getIdentifier(),
            ));

            if(!$this->_request instanceof ControllerRequestInterface)
            {
                throw new \UnexpectedValueException(
                    'Request: '.get_class($this->_request).' does not implement ControllerRequestInterface'
                );
            }
        }

        return $this->_request;
    }

    /**
     * Set the response object
     *
     * @param ControllerResponseInterface $request A request object
     * @return ControllerAbstract
     */
    public function setResponse(ControllerResponseInterface $response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * Get the response object
     *
     * @throws	\UnexpectedValueException	If the response doesn't implement the ControllerResponseInterface
     * @return ControllerResponseInterface
     */
    public function getResponse()
    {
        if(!$this->_response instanceof ControllerResponseInterface)
        {
            $this->_response = $this->getObject($this->_response, array(
                'request' => $this->getRequest(),
                'user'    => $this->getUser(),
            ));

            if(!$this->_response instanceof ControllerResponseInterface)
            {
                throw new \UnexpectedValueException(
                    'Response: '.get_class($this->_response).' does not implement ControllerResponseInterface'
                );
            }
        }

        return $this->_response;
    }

    /**
     * Set the user object
     *
     * @param UserInterface $user A request object
     * @return User
     */
    public function setUser(UserInterface $user)
    {
        $this->_user = $user;
        return $this;
    }

    /**
     * Get the user object
     *
     * @throws	\UnexpectedValueException	If the user doesn't implement the UserInterface
     * @return UserInterface
     */
    public function getUser()
    {
        if(!$this->_user instanceof UserInterface)
        {
            $this->_user = $this->getObject($this->_user, array(
                'request' => $this->getRequest(),
            ));

            if(!$this->_user instanceof UserInterface)
            {
                throw new \UnexpectedValueException(
                    'User: '.get_class($this->_user).' does not implement UserInterface'
                );
            }
        }

        return $this->_user;
    }

    /**
     * Get the controller context
     *
     * @return  ControllerContextInterface
     */
    public function getContext()
    {
        $context = new ControllerContext();

        $context->subject  = $this;
        $context->request  = $this->getRequest();
        $context->response = $this->getResponse();
        $context->user     = $this->getUser();

        return $context;
    }

    /**
     * Execute a controller action by it's name.
     *
     * Function is also capable of checking is a behavior has been mixed successfully using is[Behavior]
     * function. If the behavior exists the function will return TRUE, otherwise FALSE.
     *
     * @param   string  $method Method name
     * @param   array   $args   Array containing all the arguments for the original call
     * @see execute()
     */
    public function __call($method, $args)
    {
        //Handle action alias method
        if (in_array($method, $this->getActions()))
        {
            //Get the data
            $data = !empty($args) ? $args[0] : array();

            //Create a context object
            if (!($data instanceof CommandInterface))
            {
                $context = $this->getContext();

                //Store the parameters in the context
                $context->param = $data;

                //Force the result to false before executing
                $context->result = false;
            }
            else $context = $data;

            //Execute the action
            return $this->execute($method, $context);
        }

        if(!isset($this->_mixed_methods[$method]))
        {
            //Check if a behavior is mixed
            $parts = StringInflector::explode($method);

            if ($parts[0] == 'is' && isset($parts[1])) {
                return false;
            }
        }

        return parent::__call($method, $args);
    }
}