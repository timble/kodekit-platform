<?php
/**
 * @package		Koowa_Controller
 * @subpackage	Permission
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract Controller Permission Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage	Permission
 */
abstract class ControllerPermissionAbstract extends ControllerBehaviorAbstract implements ControllerPermissionInterface
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Config object with configuration options.
     * @return void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'priority'   => Command::PRIORITY_HIGH,
            'auto_mixin' => true
        ));

        parent::_initialize($config);
    }

	/**
     * Command handler
     *
     * Only handles before.action commands to check authorization rules.
     *
     * @param   string $name     The command name
     * @param   object $context  The command context
     * @throws  ControllerExceptionForbidden       If the user is authentic and the actions is not allowed.
     * @throws  ControllerExceptionUnauthorized    If the user is not authentic and the action is not allowed.
     * @return  boolean     Can return both true or false.
     */
    public function execute( $name, CommandContext $context)
    {
        $parts = explode('.', $name);

        if($parts[0] == 'before')
        {
            $action = $parts[1];

            if($this->isPermitted($action) === false)
		    {
                if($context->user->isAuthentic()) {
                    throw new ControllerExceptionForbidden('Action '.ucfirst($action).' Not Allowed');
                } else {
                    throw new ControllerExceptionUnauthorized('Action '.ucfirst($action).' Not Allowed');
                }

		        return false;
		    }
        }

        return true;
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
        return MixinAbstract::getHandle();
    }

    /**
     * Check if an action can be executed
     *
     * @param   string  Action name
     * @return  boolean True if the action can be executed, otherwise FALSE.
     */
    public function isPermitted($action)
    {
        //Check if the action is allowed
        $method = 'can'.ucfirst($action);

        if(!method_exists($this, $method))
        {
            $actions = $this->getActions();
            $actions = array_flip($actions);

            $result = isset($actions[$action]);
        }
        else $result = $this->$method();

        return $result;
    }
}