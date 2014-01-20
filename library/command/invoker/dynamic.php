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
 * Abstract Dynamic Command Invoker
 *
 * The dynamic command invoker will translate the command name to a method name format (eg, _before[Command] or
 * _after[Command]) and add push it onto the command handlers stack before executing the command. Dynamic command
 * handlers should be declared protected.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
 */
abstract class CommandInvokerDynamic extends CommandInvokerAbstract
{
    /**
     * Command handler
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
}

