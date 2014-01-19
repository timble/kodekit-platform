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
 * The command chain implements a queue. The command handle is used as the key. Each command can have a priority, default
 * priority is 3 The queue is ordered by priority, commands with a higher priority are called first.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
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
     * The invoker queue
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
    protected $_condition;

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  An optional KObjectConfig object with configuration options
     * @return CommandChain
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the chain enabled state
        $this->__enabled = (boolean) $config->enabled;

        //Set the chain break condition
        $this->_condition = $config->command_condition;

        $this->__stack = $this->getObject('koowa:object.stack');
        $this->__queue = $this->getObject('koowa:object.queue');
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config Configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'command_condition' => false,
            'enabled'           => true
        ));

        parent::_initialize($config);
    }

    /**
     * Enable the chain
     *
     * @return  $this
     */
    public function enable()
    {
        $this->__enabled = true;
        return $this;
    }

    /**
     * Disable the chain
     *
     * If the chain is disabled running the chain will always return TRUE
     *
     * @return  $this
     */
    public function disable()
    {
        $this->__enabled = false;
        return $this;
    }

    /**
     * Invoke a command by calling all registered invokers
     *
     * If a command invoker returns the 'break condition' the executing is halted. If no break condition is specified the
     * the command chain will execute all command invokers, regardless of the invoker result returned.
     *
     * @param  string|CommandInterface  $command    The command name or a KCommandInterface object
     * @param  array|\Traversable       $attributes An associative array or a Traversable object
     * @param  ObjectInterface          $subject    The command subject
     * @return array|mixed Returns an array of the command results in FIFO order. If the chain breaks, and the break
     *                     condition is not NULL returns the break condition instead.
     */
    public function invokeCommand($command, $attributes = null, $subject = null)
    {
        $result = array();

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

            foreach ($this->__stack->peek() as $invoker)
            {
                try {
                    $result[] = $invoker->executeCommand($command, $this->_condition);
                } catch (CommandExceptionInvoker $e) {
                    $result[] = $e;
                }

                if($this->_condition !== null && current($result) === $this->_condition)
                {
                    $result = current($result);
                    break;
                }
            }

            $this->__stack->pop();
        }

        return $result;
    }

    /**
     * Attach a command to the chain
     *
     * @param  CommandInvokerInterface  $invoker  The command invoker
     * @return CommandChain
     */
    public function addInvoker(CommandInvokerInterface $invoker)
    {
        $this->__queue->enqueue($invoker, $invoker->getPriority());
        return $this;
    }

    /**
     * Removes a command from the chain
     *
     * @param CommandInvokerInterface  $invoker  The command invoker
     * @return CommandChain
     */
    public function removeInvoker(CommandInvokerInterface $invoker)
    {
        $this->__queue->dequeue($invoker);
        return $this;
    }

    /**
     * Get the list of invokers enqueue in the chain
     *
     * @return ObjectQueue   An object queue containing the invokers
     */
    public function getInvokers()
    {
        return $this->__queue;
    }

    /**
     * Set the priority of a command
     *
     * @param  CommandInvokerInterface $invoker   A command invoker
     * @param integer                  $priority  The command priority
     * @return CommandChain
     */
    public function setInvokerPriority(CommandInvokerInterface $invoker, $priority)
    {
        $this->__queue->setPriority($invoker, $priority);
        return $this;
    }

    /**
     * Get the priority of a command
     *
     * @param  CommandInvokerInterface $invoker A command invoker
     * @return integer The command priority
     */
    public function getInvokerPriority(CommandInvokerInterface $invoker)
    {
        return $this->__queue->getPriority($invoker);
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