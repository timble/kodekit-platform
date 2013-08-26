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
 * Abstract Controller
 *
 * Note: Concrete controllers must have a singular name
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerAbstract extends Object implements ControllerInterface
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
    const STATUS_SUCCESS   = HttpResponse::OK;
    const STATUS_CREATED   = HttpResponse::CREATED;
    const STATUS_ACCEPTED  = HttpResponse::ACCEPTED;
    const STATUS_UNCHANGED = HttpResponse::NO_CONTENT;
    const STATUS_RESET     = HttpResponse::RESET_CONTENT;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
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

        // Mixin the behavior interface
        $this->mixin('lib:behavior.mixin', $config);
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
            'command_chain'     => 'lib:command.chain',
            'dispatch_events'   => true,
            'event_dispatcher'  => 'event.dispatcher',
            'enable_callbacks'  => true,
            'dispatched'        => false,
            'request'           => 'lib:controller.request',
            'response'          => 'lib:controller.response',
            'user'              => 'lib:controller.user',
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
     * @param   string         $action  The action to execute
     * @param   CommandContext $context A command context object
     * @throws  ControllerException If the action method doesn't exist
     * @return  mixed|false The value returned by the called method, false in error case.
     */
    public function execute($action, CommandContext $context)
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
                    throw new ControllerExceptionNotImplemented("Can't execute '$command', method: '$method' does not exist");
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
            $this->_request = $this->getObject($this->_request);

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
     * Set the user object
     *
     * @param ControllerUserInterface $user A request object
     * @return ControllerUser
     */
    public function setUser(ControllerUserInterface $user)
    {
        $this->_user = $user;
        return $this;
    }

    /**
     * Get the user object
     *
     * @throws	\UnexpectedValueException	If the user doesn't implement the ControllerUserInterface
     * @return ControllerUserInterface
     */
    public function getUser()
    {
        if(!$this->_user instanceof ControllerUserInterface)
        {
            $this->_user = $this->getObject($this->_user, array(
                'request' => $this->getRequest(),
            ));

            if(!$this->_user instanceof ControllerUserInterface)
            {
                throw new \UnexpectedValueException(
                    'User: '.get_class($this->_user).' does not implement ControllerUserInterface'
                );
            }
        }

        return $this->_user;
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
     * Get the command chain context
     *
     * Overrides CommandMixin::getCommandContext() to insert the request and response objects into the controller
     * command context.
     *
     * @return  CommandContext
     * @see CommandMixin::getCommandContext
     */
    public function getCommandContext()
    {
        $context = parent::getCommandContext();

        $context->request    = $this->getRequest();
        $context->user       = $this->getUser();
        $context->response   = $this->getResponse();

        return $context;
    }

    /**
     * Register (map) an action to a method in the class.
     *
     * @param   string  $alias   The action.
     * @param   string  $action  The name of the method in the derived class to perform for this action.
     * @return  ControllerAbstract
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
     * @param   string  $method Method name
     * @param   array   $args   Array containing all the arguments for the original call
     * @see execute()
     */
    public function __call($method, $args)
    {
        if (!isset($this->_mixed_methods[$method]))
        {
            //Handle action alias method
            if (in_array($method, $this->getActions()))
            {
                //Get the data
                $data = !empty($args) ? $args[0] : array();

                //Create a context object
                if (!($data instanceof CommandContextInterface))
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
            $parts = StringInflector::explode($method);

            if ($parts[0] == 'is' && isset($parts[1]))
            {
                if (!isset($this->_mixed_methods[$method])) {
                    return false;
                }
            }
        }

        return parent::__call($method, $args);
    }
}