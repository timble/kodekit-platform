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
 * Command Chain
 *
 * The command queue implements a double linked list. The command handle is used as the key. Each command can have a
 * priority, default priority is 3 The queue is ordered by priority, commands with a higher priority are called first.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
 */
class CommandChain extends ObjectQueue implements CommandChainInterface
{
    /**
     * Enabled status of the chain
     *
     * @var boolean
     */
    protected $_enabled = true;

    /**
     * The chain's break condition
     *
     * @see run()
     * @var boolean
     */
    protected $_break_condition = false;

    /**
     * The command context object
     *
     * @var CommandContext
     */
    protected $_context = null;

    /**
     * The chain stack
     *
     * @var    ObjectStack
     */
    protected $_stack;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return CommandChain
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_break_condition = (boolean)$config->break_condition;
        $this->_enabled = (boolean)$config->enabled;
        $this->_context = $config->context;
        $this->_stack   = $config->stack;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'stack'     => $this->getObject('lib:object.stack'),
            'context'   => new CommandContext(),
            'enabled'   => true,
            'break_condition' => false,
        ));

        parent::_initialize($config);
    }

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
    public function enqueue(ObjectHandlable $command, $priority = null)
    {
        if (!$command instanceof CommandInterface) {
            throw new \InvalidArgumentException('Command needs to implement CommandInterface');
        }

        $priority = is_int($priority) ? $priority : $command->getPriority();
        return parent::enqueue($command, $priority);
    }

    /**
     * Removes a command from the queue
     *
     * @param   CommandInterface $command
     * @return  boolean    TRUE on success FALSE on failure
     * @throws  \InvalidArgumentException if the object implement CommandInterface
     */
    public function dequeue(ObjectHandlable $command)
    {
        if (!$command instanceof CommandInterface) {
            throw new \InvalidArgumentException('Command needs to implement CommandInterface');
        }

        return parent::dequeue($command);
    }

    /**
     * Check if the queue does contain a given object
     *
     * @param  CommandInterface $object
     * @return bool
     * @throws  \InvalidArgumentException if the object implement CommandInterface
     */
    public function contains(ObjectHandlable $command)
    {
        if (!$command instanceof CommandInterface) {
            throw new \InvalidArgumentException('Command needs to implement CommandInterface');
        }

        return parent::contains($command);
    }

    /**
     * Run the commands in the chain
     *
     * If a command returns the 'break condition' the executing is halted.
     *
     * @param   string          $name
     * @param   CommandContext $context
     * @return  void|boolean    If the chain breaks, returns the break condition. Default returns void.
     */
    public function run($name, CommandContext $context)
    {
        if ($this->_enabled)
        {
            $this->getStack()->push(clone $this);

            foreach ($this->getStack()->top() as $command)
            {
                if ($command->execute($name, $context) === $this->_break_condition)
                {
                    $this->getStack()->pop();
                    return $this->_break_condition;
                }
            }

            $this->getStack()->pop();
        }
    }

    /**
     * Enable the chain
     *
     * @return  void
     */
    public function enable()
    {
        $this->_enabled = true;
        return $this;
    }

    /**
     * Disable the chain
     *
     * If the chain is disabled running the chain will always return TRUE
     *
     * @return  void
     */
    public function disable()
    {
        $this->_enabled = false;
        return $this;
    }

    /**
     * Set the priority of a command
     *
     * @param CommandInterface $command
     * @param integer           $priority
     * @return CommandChain
     * @throws \InvalidArgumentException if the object doesn't implement CommandInterface
     */
    public function setPriority(ObjectHandlable $command, $priority)
    {
        if (!$command instanceof CommandInterface) {
            throw new \InvalidArgumentException('Command needs to implement CommandInterface');
        }

        return parent::setPriority($command, $priority);
    }

    /**
     * Get the priority of a command
     *
     * @param  CommandInterface $object
     * @return integer The command priority
     * @throws \InvalidArgumentException if the object doesn't implement CommandInterface
     */
    public function getPriority(ObjectHandlable $command)
    {
        if (!$command instanceof CommandInterface) {
            throw new \InvalidArgumentException('Command needs to implement CommandInterface');
        }

        return parent::getPriority($command);
    }

    /**
     * Factory method for a command context.
     *
     * @return  CommandContext
     */
    public function getContext()
    {
        return clone $this->_context;
    }

    /**
     * Get the chain object stack
     *
     * @return     ObjectStack
     */
    public function getStack()
    {
        return $this->_stack;
    }
}