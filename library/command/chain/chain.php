<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Command Chain
 *
 * The command chain implements a queue. The command handle is used as the key. Each command can have a priority, default
 * priority is 3 The queue is ordered by priority, commands with a higher priority are called first.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Command
 */
class CommandChain extends Object implements CommandChainInterface
{
    /**
     * The chain stack
     *
     * Used to track recursive chain nesting.
     *
     * @var ObjectStack
     */
    private $__stack;

    /**
     * The handler queue
     *
     * @var ObjectQueue
     */
    private $__queue;

    /**
     * Enabled status of the chain
     *
     * @var boolean
     */
    private $__enabled;

    /**
     * The chain break condition
     *
     * @var boolean
     */
    protected $_break_condition;

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  An optional ObjectConfig object with configuration options
     * @return CommandChain
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the chain enabled state
        $this->setEnabled($config->enabled);

        //Set the chain break condition
        $this->_break_condition = $config->break_condition;

        $this->__stack = $this->getObject('lib:object.stack');
        $this->__queue = $this->getObject('lib:object.queue');
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'break_condition' => false,
            'enabled'         => true
        ));

        parent::_initialize($config);
    }

    /**
     * Execute a command by executing all registered handlers
     *
     * If a command handler returns the 'break condition' the executing is halted. If no break condition is specified the
     * the command chain will execute all command handlers, regardless of the handler result returned.
     *
     * @param  string|CommandInterface  $command    The command name or a KCommandInterface object
     * @param  array|\Traversable       $attributes An associative array or a Traversable object
     * @param  ObjectInterface          $subject    The command subject
     * @return mixed|null If a handler breaks, returns the break condition. NULL otherwise.
     */
    public function execute($command, $attributes = null, $subject = null)
    {
        $result = null;

        if ($this->isEnabled())
        {
            $this->__stack->push(clone $this->__queue);

            //Make sure we have an command object
            if (!$command instanceof CommandInterface)
            {
                if($attributes instanceof CommandInterface)
                {
                    $name    = $command;
                    $command = $attributes;

                    $command->setName($name);
                }
                else $command = new Command($command, $attributes, $subject);
            }

            foreach ($this->__stack->peek() as $handler)
            {
                $result = $handler->execute($command, $this);

                if($result === $this->getBreakCondition()) {
                    break;
                }
            }

            $this->__stack->pop();
        }

        return $result;
    }

    /**
     * Add a command handler to the chain
     *
     * @param  CommandHandlerInterface  $handler  The command handler
     * @return CommandChain
     */
    public function addHandler(CommandHandlerInterface $handler)
    {
        $this->__queue->enqueue($handler, $handler->getPriority());
        return $this;
    }

    /**
     * Remove a command handler from the chain
     *
     * @param  CommandHandlerInterface  $handler  The command handler
     * @return CommandChain
     */
    public function removeHandler(CommandHandlerInterface $handler)
    {
        $this->__queue->dequeue($handler);
        return $this;
    }

    /**
     * Get the list of command handlers enqueued in the chain
     *
     * @return  ObjectQueue   An object queue containing the handlers
     */
    public function getHandlers()
    {
        return $this->__queue;
    }

    /**
     * Set the priority of a command handler
     *
     * @param  CommandHandlerInterface $handler   A command handler
     * @param integer                   $priority  The command priority
     * @return CommandChain
     */
    public function setHandlerPriority(CommandHandlerInterface $handler, $priority)
    {
        $this->__queue->setPriority($handler, $priority);
        return $this;
    }

    /**
     * Get the priority of a command handler
     *
     * @param  CommandHandlerInterface $handler A command handler
     * @return integer The command priority
     */
    public function getHandlerPriority(CommandHandlerInterface $handler)
    {
        return $this->__queue->getPriority($handler);
    }

    /**
     * Set the break condition of the chain
     *
     * @param mixed|null $condition The break condition, or NULL to set reset the break condition
     * @return CommandChain
     */
    public function setBreakCondition($condition)
    {
        $this->_break_condition = $condition;
        return $this;
    }

    /**
     * Get the break condition of the chain
     *
     * @return mixed|null   Returns the break condition, or NULL if not break condition is set.
     */
    public function getBreakCondition()
    {
        return $this->_break_condition;
    }

    /**
     * Enable the chain
     *
     * @return  CommandChain
     */
    public function setEnabled($enabled)
    {
        $this->__enabled = (bool) $enabled;
        return $this;
    }

    /**
     * Check of the command chain is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->__enabled;
    }
}