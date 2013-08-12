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
     * Attach a command to the chain
     *
     * The priority parameter can be used to override the command priority while enqueueing the command.
     *
     * @param   CommandInterface   $command
     * @param   integer             $priority The command priority, usually between 1 (high priority) and 5 (lowest),
     *                                        default is 3. If no priority is set, the command priority will be used
     *                                        instead.
     * @return CommandChain
     * @throws \InvalidArgumentException if the object doesn't implement CommandInterface
     */
    public function enqueue(ObjectHandlable $command, $priority = null);

    /**
     * Removes a command from the queue
     *
     * @param   CommandInterface $command
     * @return  boolean    TRUE on success FALSE on failure
     * @throws  \InvalidArgumentException if the object implement CommandInterface
     */
    public function dequeue(ObjectHandlable $command);

    /**
     * Check if the queue does contain a given object
     *
     * @param  CommandInterface $object
     * @return bool
     * @throws  \InvalidArgumentException if the object implement CommandInterface
     */
    public function contains(ObjectHandlable $command);

    /**
     * Run the commands in the chain
     *
     * If a command returns the 'break condition' the executing is halted.
     *
     * @param   string          $name
     * @param   CommandContext $context
     * @return  void|boolean    If the chain breaks, returns the break condition. Default returns void.
     */
    public function run($name, CommandContext $context);

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
     * @param CommandInterface $command
     * @param integer           $priority
     * @return CommandChain
     * @throws \InvalidArgumentException if the object doesn't implement CommandInterface
     */
    public function setPriority(ObjectHandlable $command, $priority);

    /**
     * Get the priority of a command
     *
     * @param  CommandInterface $object
     * @return integer The command priority
     * @throws \InvalidArgumentException if the object doesn't implement CommandInterface
     */
    public function getPriority(ObjectHandlable $command);

    /**
     * Factory method for a command context.
     *
     * @return  CommandContext
     */
    public function getContext();

    /**
     * Get the chain object stack
     *
     * @return     ObjectStack
     */
    public function getStack();
}