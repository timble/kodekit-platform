<?php
/**
 * @version		$Id$
 * @package		Koowa_Controller
 * @subpackage	Permission
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Controller Permission Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage	Permission
 */
abstract class KControllerPermissionAbstract extends KControllerBehaviorAbstract implements KControllerPermissionInterface
{
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
        $config->append(array(
            'priority'   => KCommand::PRIORITY_HIGH,
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
     * @throws  KControllerExceptionForbidden       If the user is authentic and the actions is not allowed.
     * @throws  KControllerExceptionUnauthorized    If the user is not authentic and the action is not allowed.
     * @return  boolean     Can return both true or false.
     */
    public function execute( $name, KCommandContext $context)
    {
        $parts = explode('.', $name);

        if($parts[0] == 'before')
        {
            $action = $parts[1];

            //Check if the action is allowed
            $method = 'can'.ucfirst($action);

            if(method_exists($this, $method))
            {
		        if($this->$method() === false)
		        {
                    if($context->user->isAuthentic()) {
                        throw new KControllerExceptionForbidden('Action '.ucfirst($action).' Not Allowed');
                    } else {
                        throw new KControllerExceptionUnauthorized('Action '.ucfirst($action).' Not Allowed');
                    }

		            return false;
		        }
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
        return KMixinAbstract::getHandle();
    }

    /**
     * Generic authorize handler for controller render actions
     *
     * Default implementation checks of the controller has a render action handler defined.
     *
     * @return  boolean     Can return both true or false.
     */
    public function canRender()
    {
        $actions = $this->getActions();
        $actions = array_flip($actions);

        return isset($actions['render']);
        return true;
    }

	/**
     * Generic authorize handler for controller browse actions
     *
     * Default implementation checks of the controller has a browse action handler defined.
     *
     * @return  boolean     Can return both true or false.
     */
    public function canBrowse()
    {
        $actions = $this->getActions();
        $actions = array_flip($actions);

        return isset($actions['browse']);
    }

	/**
     * Generic authorize handler for controller read actions
     *
     * Default implementation checks of the controller has a read action handler defined.
     *
     * @return  boolean     Can return both true or false.
     */
    public function canRead()
    {
        $actions = $this->getActions();
        $actions = array_flip($actions);

        return isset($actions['read']);
    }

	/**
     * Generic authorize handler for controller edit actions
     *
     * Default implementation checks of the controller has an edit action handler defined.
     *
     * @return  boolean     Can return both true or false.
     */
    public function canEdit()
    {
        $actions = $this->getActions();
        $actions = array_flip($actions);

        return isset($actions['edit']);
    }

 	/**
     * Generic authorize handler for controller add actions
     *
     * Default implementation checks of the controller has an add action handler defined.
     *
     * @return  boolean     Can return both true or false.
     */
    public function canAdd()
    {
        $actions = $this->getActions();
        $actions = array_flip($actions);

        return isset($actions['add']);
    }

 	/**
     * Generic authorize handler for controller delete actions
     *
     * Default implementation checks of the controller has a delete action handler defined.
     *
     * @return  boolean     Can return both true or false.
     */
    public function canDelete()
    {
        $actions = $this->getActions();
        $actions = array_flip($actions);

        return isset($actions['delete']);
    }
}