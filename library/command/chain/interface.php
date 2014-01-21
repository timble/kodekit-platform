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
 * Command Chain Interface
 *
 * The command chain implements a queue. The command handle is used as the key. Each command can have a priority, default
 * priority is 3 The queue is ordered by priority, commands with a higher priority are called first.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
     * Invoke a command by calling all registered invokers
     *
     * If a command invoker returns the 'break condition' the executing is halted. If no break condition is specified the
     * the command chain will execute all command invokers, regardless of the invoker result returned.
     *
     * @param  string|CommandInterface  $command    The command name or a KCommandInterface object
     * @param  array|\Traversable       $attributes An associative array or a Traversable object
     * @param  ObjectInterface          $subject    The command subject
     * @return array|mixed Returns an array of the command results in FIFO order where the key holds the invoker identifier
     *                     and the value the result returned by the invoker. If the chain breaks, and the break condition
     *                     is not NULL returns the break condition instead.
     */
    public function invokeCommand($command, $attributes = array(), $subject = null);

    /**
     * Attach a command to the chain
     *
     * @param  CommandInvokerInterface  $invoker  The command invoker
     * @return CommandChainInterface
     */
    public function addInvoker(CommandInvokerInterface $invoker);

    /**
     * Get the list of invokers enqueue in the chain
     *
     * @return ObjectQueue An object queue containing the invokers
     */
    public function getInvokers();

    /**
     * Set the priority of a command invoker
     *
     * @param CommandInvokerInterface $invoker   A command invoker
     * @param integer                   $priority  The command priority
     * @return CommandChain
     */
    public function setInvokerPriority(CommandInvokerInterface $invoker, $priority);

    /**
     * Get the priority of a command invoker
     *
     * @param  CommandInvokerInterface $invoker A command invoker
     * @return integer The command priority
     */
    public function getInvokerPriority(CommandInvokerInterface $invoker);

    /**
     * Check of the command chain is enabled
     *
     * @return bool
     */
    public function isEnabled();
}