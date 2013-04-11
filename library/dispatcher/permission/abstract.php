<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage	Permission
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Abstract Dispatcher Permission Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage	Permission
 */
abstract class DispatcherPermissionAbstract extends ControllerPermissionAbstract
{
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

            //Check if the action is allowed
            $method = 'can'.ucfirst($action);

            if(method_exists($this, $method))
            {
                if($this->$method() === false)
		        {
                    throw new DispatcherExceptionActionNotAllowed('Action: '.$method.' not allowed');
		            return false;
		        }
            }
        }

        return true;
    }
}