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
 * Command Chain Interface
 *
 * The command chain implements a queue. The command handle is used as the key. Each command can have a priority, default
 * priority is 3 The queue is ordered by priority, commands with a higher priority are called first.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Command
 */
interface CommandChainInterface
{
    /**
     * Enable the chain
     *
     * @return  $this
     */
    public function enable();

    /**
     * Disable the chain
     *
     * If the chain is disabled running the chain will always return TRUE
     *
     * @return  $this
     */
    public function disable();

    /**
     * Execute a command by executing all registered handlers
     *
     * If a command handler returns the 'break condition' the executing is halted. If no break condition is specified the
     * the command chain will execute all command handlers, regardless of the handler result returned.
     *
     * @param string|CommandInterface  $command    The command name or a KCommandInterface object
     * @param array|\Traversable         $attributes An associative array or a Traversable object
     * @param ObjectInterface          $subject    The command subject
     * @return mixed|null If a handlers breaks, returns the break condition. NULL otherwise.
     */
    public function execute($command, $attributes = array(), $subject = null);

    /**
     * Attach a command to the chain
     *
     * @param CommandHandlerInterface  $handler  The command handler
     * @return CommandChainInterface
     */
    public function addHandler(CommandHandlerInterface $handler);

    /**
     * Removes a command from the chain
     *
     * @param  CommandHandlerInterface  $handler  The command handler
     * @return  CommandChain
     */
    public function removeHandler(CommandHandlerInterface $handler);

    /**
     * Get the list of handler enqueue in the chain
     *
     * @return  ObjectQueue   An object queue containing the handlers
     */
    public function getHandlers();

    /**
     * Set the priority of a command handler
     *
     * @param CommandHandlerInterface $handler   A command handler
     * @param integer                   $priority  The command priority
     * @return CommandChainInterface
     */
    public function setHandlerPriority(CommandHandlerInterface $handler, $priority);

    /**
     * Get the priority of a command handlers
     *
     * @param  CommandHandlerInterface $handler A command handler
     * @return integer The command priority
     */
    public function getHandlerPriority(CommandHandlerInterface $handler);

    /**
     * Set the break condition
     *
     * @param mixed|null $condition The break condition, or NULL to set reset the break condition
     * @return CommandChainInterface
     */
    public function setBreakCondition($condition);

    /**
     * Get the break condition
     *
     * @return mixed|null   Returns the break condition, or NULL if not break condition is set.
     */
    public function getBreakCondition();

    /**
     * Check of the command chain is enabled
     *
     * @return bool
     */
    public function isEnabled();
}