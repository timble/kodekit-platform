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
 * Command Mixin
 * 
 * Class can be used as a mixin in classes that want to implement a chain of responsibility or chain of command pattern.
 *  
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
 */
interface CommandMixinInterface
{
    /**
     * Invoke a command by calling all registered invokers
     *
     * If a command invoker returns the 'break condition' the executing is halted. If no break condition is specified the
     * the command chain will execute all command invokers, regardless of the invoker result returned.
     *
     * @param  string|CommandInterface  $command    The command name or a CommandInterface object
     * @param  array|\Traversable       $attributes An associative array or a Traversable object
     * @param  ObjectInterface          $subject    The command subject
     * @return array|mixed Returns an array of the command results in FIFO order where the key holds the invoker identifier
     *                     and the value the result returned by the invoker. If the chain breaks, and the break condition
     *                     is not NULL returns the break condition instead.
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
     * Attach a command to the chain
     *
     * The priority parameter can be used to override the command priority while enqueueing the command.
     *
     * @param  mixed $invoker An object that implements CommandInvokerInterface, an ObjectIdentifier
     *                        or valid identifier string
     * @param  array  $config  An optional associative array of configuration options
     * @return ObjectInterface The mixer object
     */
    public function addCommandInvoker($invoker, $config = array());

    /**
     * Removes a command from the chain
     *
     * @param CommandInvokerInterface  $invoker  The command invoker
     * @return ObjectInterface The mixer object
     */
    public function removeCommandInvoker(CommandInvokerInterface $invoker);

    /**
     * Get a command invoker by identifier
     *
     * @param  mixed $invoker An object that implements ObjectInterface, ObjectIdentifier object
     *                        or valid identifier string
     * @param  array  $config An optional associative array of configuration settings
     * @throws \UnexpectedValueException    If the invoker is not implementing the CommandInvokerInterface
     * @return CommandInvokerInterface
     */
    public function getCommandInvoker($invoker, $config = array());

    /**
     * Gets the command invokers
     *
     * @return array An array of command invokers
     */
    public function getCommandInvokers();
}