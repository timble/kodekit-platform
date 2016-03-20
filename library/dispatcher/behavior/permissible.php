<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Dispatcher Permissible Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 */
class DispatcherBehaviorPermissible extends DispatcherBehaviorAbstract
{
    /**
     * The permission object
     *
     * @var DispatcherPermissionInterface
     */
    protected $_permission;

    /**
     * Constructor.
     *
     * @param  ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_permission = $config->permission;
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => self::PRIORITY_HIGH,
            'permission' => null
        ));

        parent::_initialize($config);
    }

    /**
     * Command handler
     *
     * Only handles before.action commands to check authorization rules.
     *
     * @param CommandInterface         $command    The command
     * @param CommandChainInterface    $chain      The chain executing the command
     * @throws  ControllerExceptionRequestForbidden       If the user is authentic and the actions is not allowed.
     * @throws  ControllerExceptionRequestNotAuthorized   If the user is not authentic and the action is not allowed.
     * @return  boolean Return TRUE if action is permitted. FALSE otherwise.
     */
    public function execute(CommandInterface $command, CommandChainInterface $chain)
    {
        $parts = explode('.', $command->getName());

        if($parts[0] == 'before')
        {
            $action = $parts[1];

            if($this->canExecute($action) === false)
            {
                $message = 'Action '.ucfirst($action).' Not Allowed';

                if($this->getUser()->isAuthentic())
                {
                    if (!$this->getUser()->isEnabled()) {
                        $message = 'User account is disabled';
                    }

                    throw new ControllerExceptionRequestForbidden($message);
                }
                else throw new ControllerExceptionRequestNotAuthorized($message);

                return false;
            }
        }

        return true;
    }

    /**
     * Check if an action can be executed
     *
     * @param   string  $action Action name
     * @return  boolean True if the action can be executed, otherwise FALSE.
     */
    public function canExecute($action)
    {
        //Check if the action is allowed
        $method = 'can'.ucfirst($action);

        if(!in_array($method, $this->getMixer()->getMethods()))
        {
            $actions = $this->getActions();
            $actions = array_flip($actions);

            $result = isset($actions[$action]);
        }
        else $result = $this->$method();

        return $result;
    }

    /**
     * Mixin Notifier
     *
     * This function is called when the mixin is being mixed. It will get the mixer passed in.
     *
     * @param ObjectMixable $mixer The mixer object
     * @return void
     */
    public function onMixin(ObjectMixable $mixer)
    {
        parent::onMixin($mixer);

        //Create and mixin the permission if it's doesn't exist yet
        if (!$this->_permission instanceof DispatcherPermissionInterface)
        {
            $permission = $this->_permission;

            if (!$permission || (is_string($permission) && strpos($permission, '.') === false))
            {
                $identifier = $mixer->getIdentifier()->toArray();
                $identifier['path'] = array('dispatcher', 'permission');

                if ($permission) {
                    $identifier->name = $permission;
                }

                $permission = $identifier;
            }

            if (!$permission instanceof ObjectIdentifierInterface) {
                $permission = $this->getIdentifier($permission);
            }

            $this->_permission = $mixer->mixin($permission);
        }
    }

    /**
     * Get an object handle
     *
     * Force the object to be enqueue in the command chain.
     *
     * @return string A string that is unique, or NULL
     * @see execute()
     */
    public function getHandle()
    {
        return ObjectMixinAbstract::getHandle();
    }
}