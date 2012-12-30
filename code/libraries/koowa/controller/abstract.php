<?php
/**
 * @version        $Id$
 * @package        Koowa_Controller
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Abstract Controller Class
 *
 * Note: Concrete controllers must have a singular name
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @uses        KMixinClass
 * @uses        KCommandChain
 */
abstract class KControllerAbstract extends KObject implements KControllerInterface
{
    /**
     * The controller actions
     *
     * @var array
     */
    protected $_actions = array();

    /**
     * Array of controller methods to call for a given action.
     *
     * @var array
     */
    protected $_action_map = array();

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

    //Status codes
    const STATUS_SUCCESS   = KHttpResponse::OK;
    const STATUS_CREATED   = KHttpResponse::CREATED;
    const STATUS_ACCEPTED  = KHttpResponse::ACCEPTED;
    const STATUS_UNCHANGED = KHttpResponse::NO_CONTENT;
    const STATUS_RESET     = KHttpResponse::RESET_CONTENT;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Force load the controller actions
        $this->_actions = $this->getActions();

        // Set the model identifier
        $this->_request = $config->request;

        // Set the view identifier
        $this->_response = $config->response;

        // Set the user identifier
        $this->_user = $config->user;

        //Set the dispatched state
        $this->_dispatched = $config->dispatched;

        //Set the mixer in the config
        $config->mixer = $this;

        // Mixin the command interface
        $this->mixin(new KMixinCommand($config));

        // Mixin the behavior interface
        $this->mixin(new KMixinBehavior($config));
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        //Create permission identifier
        $permission       = clone $this->getIdentifier();
        $permission->path = array($permission->path[0], 'permission');

        $config->append(array(
            'command_chain'     => 'koowa:command.chain',
            'dispatch_events'   => true,
            'event_dispatcher'  => 'koowa:event.dispatcher.default',
            'enable_callbacks'  => true,
            'dispatched'        => false,
            'request'           => 'koowa:controller.request',
            'response'          => 'koowa:controller.response',
            'user'              => 'koowa:controller.user',
            'behaviors'         => array($permission),
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
     * @param   string      The action to execute
     * @param   object      A command context object
     * @throws  KControllerException If the action method doesn't exist
     * @return  mixed|false The value returned by the called method, false in error case.
     */
    public function execute($action, KCommandContext $context)
    {
        $action = strtolower($action);

        //Update the context
        $context->action  = $action;
        $context->setSubject($this);

        //Find the mapped action
        if (isset($this->_action_map[$action])) {
            $command = $this->_action_map[$action];
        } else {
            $command = $action;
        }

        //Execute the action
        if ($this->getCommandChain()->run('before.' . $command, $context) !== false)
        {
            $method = '_action' . ucfirst($command);

            if (!method_exists($this, $method))
            {
                if (isset($this->_mixed_methods[$command])) {
                    $context->result = $this->_mixed_methods[$command]->execute('action.' . $command, $context);
                } else {
                    throw new KControllerExceptionNotImplemented("Can't execute '$command', method: '$method' does not exist");
                }
            }
            else  $context->result = $this->$method($context);

            $this->getCommandChain()->run('after.' . $command, $context);
        }

        return $context->result;
    }

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed in objects, in a LIFO order.
     *
     * @@param   mixed    An object that implements KMixinInterface, KServiceIdentifier object
     *                     or valid identifier string
     * @param    array An optional associative array of configuration options
     * @return  KObject
     */
    public function mixin($mixin, $config = array())
    {
        if ($mixin instanceof KControllerBehaviorAbstract)
        {
            foreach ($mixin->getMethods() as $method)
            {
                if (substr($method, 0, 7) == '_action') {
                    $this->_actions[] = strtolower(substr($method, 7));
                }
            }

            $this->_actions = array_unique(array_merge($this->_actions, array_keys($this->_action_map)));
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

            $this->_actions = array_unique(array_merge($this->_actions, array_keys($this->_action_map)));
        }

        return $this->_actions;
    }

    /**
     * Set the request object
     *
     * @param KControllerRequestInterface $request A request object
     * @return KControllerAbstract
     */
    public function setRequest(KControllerRequestInterface $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Get the request object
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the KControllerRequestInterface
     * @return KControllerRequestInterface
     */
    public function getRequest()
    {
        if(!$this->_request instanceof KControllerRequestInterface)
        {
            $this->_request = $this->getService($this->_request);

            if(!$this->_request instanceof KControllerRequestInterface)
            {
                throw new \UnexpectedValueException(
                    'Request: '.get_class($this->_request).' does not implement KControllerRequestInterface'
                );
            }
        }

        return $this->_request;
    }

    /**
     * Set the response object
     *
     * @param KControllerResponseInterface $request A request object
     * @return KControllerAbstract
     */
    public function setResponse(KControllerResponseInterface $response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * Get the response object
     *
     * @throws	\UnexpectedValueException	If the response doesn't implement the KControllerResponseInterface
     * @return KControllerResponseInterface
     */
    public function getResponse()
    {
        if(!$this->_response instanceof KControllerResponseInterface)
        {
            $this->_response = $this->getService($this->_response);

            if(!$this->_response instanceof KControllerResponseInterface)
            {
                throw new \UnexpectedValueException(
                    'Response: '.get_class($this->_response).' does not implement KControllerResponseInterface'
                );
            }
        }

        return $this->_response;
    }

    /**
     * Set the user object
     *
     * @param KControllerUserInterface $user A request object
     * @return KControllerUser
     */
    public function setUser(KControllerUserInterface $user)
    {
        $this->_user = $user;
        return $this;
    }

    /**
     * Get the user object
     *
     * @throws	\UnexpectedValueException	If the user doesn't implement the KControllerUserInterface
     * @return KControllerUserInterface
     */
    public function getUser()
    {
        if(!$this->_user instanceof KControllerUserInterface)
        {
            $this->_user = $this->getService($this->_user);

            if(!$this->_user instanceof KControllerUserInterface)
            {
                throw new \UnexpectedValueException(
                    'User: '.get_class($this->_user).' does not implement KControllerUserInterface'
                );
            }
        }

        return $this->_user;
    }

    /**
     * Get the command chain context
     *
     * Overrides KMixinCommand::getCommandContext() to insert the request and response objects into the controller
     * command context.
     *
     * @return  KCommandContext
     * @see KMixinCommand::getCommandContext
     */
    public function getCommandContext()
    {
        $context = parent::getCommandContext();

        $context->request    = $this->getRequest();
        $context->response   = $this->getResponse();
        $context->user       = $this->getUser();

        return $context;
    }

    /**
     * Register (map) an action to a method in the class.
     *
     * @param   string  The action.
     * @param   string  The name of the method in the derived class to perform
     *                  for this action.
     * @return  KControllerAbstract
     */
    public function registerActionAlias($alias, $action)
    {
        $alias = strtolower($alias);

        if (!in_array($alias, $this->getActions())) {
            $this->_action_map[$alias] = $action;
        }

        //Force reload of the actions
        $this->_actions = array_unique(array_merge($this->_actions, array_keys($this->_action_map)));

        return $this;
    }

    /**
     * Execute a controller action by it's name.
     *
     * Function is also capable of checking is a behavior has been mixed successfully using is[Behavior]
     * function. If the behavior exists the function will return TRUE, otherwise FALSE.
     *
     * @param   string  Method name
     * @param   array   Array containing all the arguments for the original call
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
            if (!($data instanceof KCommandContext))
            {
                $context = $this->getCommandContext();

                //Store the parameters in the context
                $context->param = $data;

                //Automatic set the data in the request if an associative array is passed
                if(is_array($data) && !is_numeric(key($data))) {
                    $context->request->data->add($data);
                }

                $context->result = false;
            }
            else $context = $data;

            //Execute the action
            return $this->execute($method, $context);
        }

        //Check if a behavior is mixed
        $parts = KInflector::explode($method);

        if ($parts[0] == 'is' && isset($parts[1]))
        {
            if (!isset($this->_mixed_methods[$method])) {
                return false;
            }

            return true;
        }

        return parent::__call($method, $args);
    }
}