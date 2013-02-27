<?php
/**
 * @package        Koowa_Command
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Command Chain Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Command
 */
interface KCommandChainInterface
{
    /**
     * Attach a command to the chain
     *
     * The priority parameter can be used to override the command priority while enqueueing the command.
     *
     * @param   KCommandInterface   $command
     * @param   integer             $priority The command priority, usually between 1 (high priority) and 5 (lowest),
     *                                        default is 3. If no priority is set, the command priority will be used
     *                                        instead.
     * @return KCommandChain
     * @throws \InvalidArgumentException if the object doesn't implement KCommandInterface
     */
    public function enqueue(KObjectHandlable $command, $priority = null);

    /**
     * Removes a command from the queue
     *
     * @param   KCommandInterface $command
     * @return  boolean    TRUE on success FALSE on failure
     * @throws  \InvalidArgumentException if the object implement KCommandInterface
     */
    public function dequeue(KObjectHandlable $command);

    /**
     * Check if the queue does contain a given object
     *
     * @param  KCommandInterface $object
     * @return bool
     * @throws  \InvalidArgumentException if the object implement KCommandInterface
     */
    public function contains(KObjectHandlable $command);

    /**
     * Run the commands in the chain
     *
     * If a command returns the 'break condition' the executing is halted.
     *
     * @param   string          $name
     * @param   KCommandContext $context
     * @return  void|boolean    If the chain breaks, returns the break condition. Default returns void.
     */
    public function run($name, KCommandContext $context);

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
     * @param KCommandInterface $command
     * @param integer           $priority
     * @return KCommandChain
     * @throws \InvalidArgumentException if the object doesn't implement KCommandInterface
     */
    public function setPriority(KObjectHandlable $command, $priority);

    /**
     * Get the priority of a command
     *
     * @param  KCommandInterface $object
     * @return integer The command priority
     * @throws \InvalidArgumentException if the object doesn't implement KCommandInterface
     */
    public function getPriority(KObjectHandlable $command);

    /**
     * Factory method for a command context.
     *
     * @return  KCommandContext
     */
    public function getContext();

    /**
     * Get the chain object stack
     *
     * @return     KObjectStack
     */
    public function getStack();
}