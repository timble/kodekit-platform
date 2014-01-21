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
 * Abstract Dynamic Behavior
 *
 * The dynamic behavior will translate the command name to a method name format (eg, _before[Command] or _after[Command])
 * and add push it onto the command handlers stack before executing the command. Dynamic command handlers should be
 * declared protected.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Behavior
 */
abstract class BehaviorDynamic extends BehaviorAbstract
{
    /**
     * Command handler
     *
     *  This function translates the command name to a command handler function of the format '_before[Command]' or
     * '_after[Command]. Command handler functions should be declared protected.
     *
     * @param  CommandInterface $command    The command
     * @param  mixed            $condition  The break condition
     * @return array|mixed Returns an array of the handler results in FIFO order. If a handler breaks and the break
     *                     condition is not NULL returns the break condition.
     */
    public function executeCommand(CommandInterface $command, $condition = null)
    {
        $parts  = explode('.', $command->getName());
        $method = '_'.$parts[0].ucfirst($parts[1]);

        if(method_exists($this, $method)) {
            $this->addCommandHandler($command->getName(), $method);
        }

        return parent::executeCommand($command, $condition);
    }

    /**
     * Get an object handle
     *
     * @return string A string that is unique, or NULL
     * @see executeCommand()
     */
    public function getHandle()
    {
        foreach($this->getMethods() as $method)
        {
            if (substr($method, 0, 7) == '_before' || substr($method, 0, 6) == '_after') {
                return ObjectMixinAbstract::getHandle();
            }
        }

        return parent::getHandle();
    }
}