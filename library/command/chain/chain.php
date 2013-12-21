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
 * The command chain implements a double linked list. The command handle is used as the key. Each command can have a
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
    protected $_enabled;

    /**
     * The chain stack
     *
     * Used to track recursive chain nesting.
     *
     * @var ObjectStack
     */
    private $__stack;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return CommandChain
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_enabled = (boolean) $config->enabled;
        $this->__stack = $this->getObject('lib:object.stack');
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
            'enabled' => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Attach a command invoker to the chain
     *
     * The priority parameter can be used to override the command priority while enqueueing the command.
     *
     * @param   CommandInvokerInterface  $invoker
     * @param   integer                  $priority The command priority, usually between 1 (high priority) and 5 (lowest),
     *                                        default is 3. If no priority is set, the command priority will be used
     *                                        instead.
     * @return CommandChain
     * @throws \InvalidArgumentException if the object doesn't implement CommandInvokerInterface
     */
    public function enqueue(ObjectHandlable $invoker, $priority = null)
    {
        if (!$invoker instanceof CommandInvokerInterface) {
            throw new \InvalidArgumentException('Invoker needs to implement CommandInvokerInterface');
        }

        $priority = is_int($priority) ? $priority : $invoker->getPriority();
        return parent::enqueue($invoker, $priority);
    }

    /**
     * Removes a command invoker from the queue
     *
     * @param   CommandInvokerInterface $invoker
     * @return  boolean    TRUE on success FALSE on failure
     * @throws  \InvalidArgumentException if the object implement CommandInvokerInterface
     */
    public function dequeue(ObjectHandlable $invoker)
    {
        if (!$invoker instanceof CommandInvokerInterface) {
            throw new \InvalidArgumentException('Invoker needs to implement CommandInvokerInterface');
        }

        return parent::dequeue($invoker);
    }

    /**
     * Check if the queue does contain a given command invoker
     *
     * @param  CommandInvokerInterface $invoker
     * @return bool
     * @throws  \InvalidArgumentException if the object implement CommandInvokerInterface
     */
    public function contains(ObjectHandlable $invoker)
    {
        if (!$invoker instanceof CommandInvokerInterface) {
            throw new \InvalidArgumentException('Invoker needs to implement CommandInvokerInterface');
        }

        return parent::contains($invoker);
    }

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
    public function run($name, Command $command, $condition = null)
    {
        if ($this->isEnabled())
        {
            $this->__stack->push(clone $this);

            foreach ($this->__stack->top() as $invoker)
            {
                if($condition === self::CONDITION_EXCEPTION)
                {
                    try
                    {
                        $invoker->execute($name, $command);
                    }
                    catch (CommandExceptionInvoker $e)
                    {
                        $this->__stack->pop();
                        return $e;
                    }
                }
                else
                {
                    $result = $invoker->execute($name, $command);

                    if($condition && $result === $condition)
                    {
                        $this->__stack->pop();
                        return $condition;
                    }
                }
            }

            $this->__stack->pop();
        }
    }

    /**
     * Enable the chain
     *
     * @return  CommandChain
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
     * @return  CommandChain
     */
    public function disable()
    {
        $this->_enabled = false;
        return $this;
    }

    /**
     * Set the priority of a command invoker
     *
     * @param CommandInvokerInterface $invoker
     * @param integer $priority
     * @return CommandChain
     * @throws \InvalidArgumentException if the object doesn't implement CommandInvokerInterface
     */
    public function setPriority(ObjectHandlable $invoker, $priority)
    {
        if (!$invoker instanceof CommandInvokerInterface) {
            throw new \InvalidArgumentException('Invoker needs to implement CommandInvokerInterface');
        }

        return parent::setPriority($invoker, $priority);
    }

    /**
     * Get the priority of a command invoker
     *
     * @param  CommandInvokerInterface $invoker
     * @return integer The command priority
     * @throws \InvalidArgumentException if the object doesn't implement CommandInvokerInterface
     */
    public function getPriority(ObjectHandlable $invoker)
    {
        if (!$invoker instanceof CommandInvokerInterface) {
            throw new \InvalidArgumentException('Invoker needs to implement CommandInvokerInterface');
        }

        return parent::getPriority($invoker);
    }

    /**
     * Check of the command chain is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_enabled;
    }
}