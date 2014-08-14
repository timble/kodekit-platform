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
 * Command Mixin
 * 
 * Class can be used as a mixin in classes that want to implement a chain of responsibility or chain of command pattern.
 *  
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Command
 */
interface CommandMixinInterface
{
    /**
     * Invoke a command by calling all registered handlers
     *
     * If a command handler returns the 'break condition' the executing is halted. If no break condition is specified the
     * the command chain will execute all command handlers, regardless of the handler result returned.
     *
     * @param  string|CommandInterface  $command    The command name or a CommandInterface object
     * @param  array|\Traversable       $attributes An associative array or a Traversable object
     * @param  ObjectInterface          $subject    The command subject
     * @return mixed|null If a handlers breaks, returns the break condition. NULL otherwise.
     */
    public function invokeCommand($command, $attributes = null, $subject = null);

    /**
     * Get the chain of command object
     *
     * @throws \UnexpectedValueException
     * @return CommandChainInterface
     */
    public function getCommandChain();

    /**
     * Set the chain of command object
     *
     * @param CommandChainInterface $chain A command chain object
     * @return ObjectInterface The mixer object
     */
    public function setCommandChain(CommandChainInterface $chain);

    /**
     * Attach a command handler to the chain
     *
     * @param  mixed $handler An object that implements KCommandHandlerInterface, an KObjectIdentifier
     *                        or valid identifier string
     * @param  array  $config   An optional associative array of configuration options
     * @return ObjectInterface The mixer object
     */
    public function addCommandHandler($handler, $config = array());

    /**
     * Removes a command handler from the chain
     *
     * @param CommandHandlerInterface  $handler  The command handler
     * @return ObjectInterface The mixer object
     */
    public function removeCommandHandler(CommandHandlerInterface $handler);

    /**
     * Gets the command handlers
     *
     * @return array An array of command handlers
     */
    public function getCommandHandlers();
}