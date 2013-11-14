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
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
 */
interface CommandChainInterface
{
    /**
     * Break conditions
     */
    const CONDITION_FALSE     = false; //Stop when first invoker indicates that it has failed
    const CONDITION_TRUE      = true;  //Stop when first invoker indicates that it has succeeded
    const CONDITION_EXCEPTION = -1;    //Break when an invoker throws an CommandExceptionInvokerFailed exception

    /**
     * Run the commands in the chain
     *
     * If a command returns the 'break condition' the executing is halted. If no break condition is specified the
     * command chain will pass the command invokers, regardless of the invoker result returned.
     *
     * @param   string  $name
     * @param   Command $command
     * @param   mixed   $condition The break condition
     * @return  void|mixed If the chain breaks, returns the break condition. If the chain is not enabled will void
     */
    public function run($name, Command $command, $condition = null);

    /**
     * Attach a command invoker to the chain
     *
     * The priority parameter can be used to override the command priority while enqueueing the command.
     *
     * @param   CommandInvokerInterface   $invoker
     * @param   integer   $priority The command priority, usually between 1 (high priority) and 5 (lowest),
     *                              default is 3. If no priority is set, the command priority will be used
     *                              instead.
     * @return CommandChain
     * @throws \InvalidArgumentException if the object doesn't implement CommandInvokerInterface
     */
    public function enqueue(ObjectHandlable $invoker, $priority = null);

    /**
     * Removes a command invoker from the chain
     *
     * @param   CommandInvokerInterface $invoker
     * @return  boolean    TRUE on success FALSE on failure
     * @throws  \InvalidArgumentException if the object implement CommandInvokerInterface
     */
    public function dequeue(ObjectHandlable $invoker);


    /**
     * Check if the queue does contain a given command invoker
     *
     * @param  CommandInvokerInterface $invoker
     * @return bool
     * @throws  \InvalidArgumentException if the object implement CommandInvokerInterface
     */
    public function contains(ObjectHandlable $invoker);

   /**
    * Enable the chain
    *
    * @return  void
    */
    public function enable();

    /**
     * Disable the chain
     *
     * If the chain is disabled running the chain will always return TRUE
     *
     * @return  void
     */
    public function disable();

    /**
     * Set the priority of a command
     *
     * @param CommandInvokerInterface $invoker
     * @param integer           $priority
     * @return CommandChain
     * @throws \InvalidArgumentException if the object doesn't implement CommandInvokerInterface
     */
    public function setPriority(ObjectHandlable $invoker, $priority);

    /**
     * Get the priority of a command
     *
     * @param  CommandInvokerInterface $invoker
     * @return integer The command priority
     * @throws \InvalidArgumentException if the object doesn't implement CommandInvokerInterface
     */
    public function getPriority(ObjectHandlable $invoker);
}