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
class CommandMixin extends ObjectMixinAbstract implements CommandMixinInterface
{
    /**
     * Chain of command object
     *
     * @var CommandChainInterface
     */
    private $__command_chain;

    /**
     * List of event subscribers
     *
     * Associative array of command invokers, where key holds the invokers identifier string
     * and the value is an identifier object.
     *
     * @var array
     */
    private $__command_invokers = array();

    /**
     * Object constructor
     *
     * @param  ObjectConfig $config Configuration options
     * @throws \InvalidArgumentException
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if(is_null($config->command_chain)) {
            throw new \InvalidArgumentException('command_chain [CommandChainInterface] config option is required');
        }

        //Create a command chain object
        $this->__command_chain = $config->command_chain;

        //Add the mixer if its implements the command invoker interface
        if($this->getMixer() instanceof CommandInvokerInterface) {
            $this->addCommandInvoker($this->getMixer());
        }

        //Add the event subscribers
        $invokers = (array) ObjectConfig::unbox($config->command_invokers);

        foreach ($invokers as $key => $value)
        {
            if (is_numeric($key)) {
                $this->addCommandInvoker($value);
            } else {
                $this->addCommandInvoker($key, $value);
            }
        }
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
            'command_chain'     => 'lib:command.chain',
            'command_invokers'  => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Mixin Notifier
     *
     * This function is called when the mixin is being mixed. It will get the mixer passed in.
     *
     * @param ObjectMixable $mixer The mixer object
     * @return void
     */
    public function onMixin(ObjectMixable $mixer)
    {
        parent::onMixin($mixer);

        //Add the mixer if its implements the command invoker interface
        if($mixer instanceof CommandInvokerInterface) {
            $this->addCommandInvoker($mixer);
        }
    }

    /**
     * Invoke a command by calling all registered invokers
     *
     * If a command invoker returns the 'break condition' the executing is halted. If no break condition is specified the
     * the command chain will execute all command invokers, regardless of the invoker result returned.
     *
     * @param  string|CommandInterface  $command    The command name or a CommandInterface object
     * @param  array|\Traversable       $attributes An associative array or a Traversable object
     * @param  ObjectInterface          $subject    The command subject
     * @return array|mixed Returns an array of the command results in FIFO order. If the chain breaks, and the break
     *                     condition is not NULL returns the break condition.
     */
    public function invokeCommand($command, $attributes = null, $subject = null)
    {
        return $this->getCommandChain()->invokeCommand($command, $attributes, $subject);
    }

    /**
     * Get the chain of command object
     *
     * @throws \UnexpectedValueException
     * @return CommandChainInterface
     */
    public function getCommandChain()
    {
        if(!$this->__command_chain instanceof CommandChainInterface)
        {
            $this->__command_chain = $this->getObject($this->__command_chain);

            if(!$this->__command_chain instanceof CommandChainInterface)
            {
                throw new \UnexpectedValueException(
                    'CommandChain: '.get_class($this->__command_chain).' does not implement CommandChainInterface'
                );
            }
        }

        return $this->__command_chain;
    }

    /**
     * Set the chain of command object
     *
     * @param CommandChainInterface $chain A command chain object
     * @return ObjectInterface The mixer object
     */
    public function setCommandChain(CommandChainInterface $chain)
    {
        $this->__command_chain = $chain;
        return $this->getMixer();
    }

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
    public function addCommandInvoker($invoker, $config = array())
    {
        if (!($invoker instanceof CommandInvokerInterface)) {
            $invoker = $this->getCommandInvoker($invoker, $config);
        }

        $this->getCommandChain()->addInvoker($invoker);
        return $this->getMixer();
    }

    /**
     * Removes a command from the chain
     *
     * @param CommandInvokerInterface  $invoker  The command invoker
     * @return ObjectInterface The mixer object
     */
    public function removeCommandInvoker(CommandInvokerInterface $invoker)
    {
        $this->getCommandChain()->removeInvoker($invoker);
        return $this->getMixer();
    }

    /**
     * Get a command invoker by identifier
     *
     * @param  mixed $invoker An object that implements ObjectInterface, ObjectIdentifier object
     *                        or valid identifier string
     * @param  array  $config An optional associative array of configuration settings
     * @throws \UnexpectedValueException  If the invoker is not implementing the CommandInvokerInterface
     * @return CommandInvokerInterface
     */
    public function getCommandInvoker($invoker, $config = array())
    {
        if (!($invoker instanceof ObjectIdentifier))
        {
            //Create the complete identifier if a partial identifier was passed
            if (is_string($invoker) && strpos($invoker, '.') === false)
            {
                $identifier = $this->getIdentifier()->toArray();
                $identifier['path'] = array('command', 'invoker');
                $identifier['name'] = $invoker;

                $identifier = $this->getIdentifier($identifier);
            }
            else $identifier = $this->getIdentifier($invoker);
        }
        else $identifier = $invoker;

        if (!isset($this->__command_invokers[(string)$identifier]))
        {
            $invoker = $this->getObject($identifier, $config);

            //Check the event subscriber interface
            if (!($invoker instanceof CommandInvokerInterface))
            {
                throw new \UnexpectedValueException(
                    "Command Invoker $identifier does not implement CommandInvokerInterface"
                );
            }
        }
        else $invoker = $this->__command_invokers[(string)$identifier];

        return $invoker;
    }

    /**
     * Gets the command invokers
     *
     * @return array An array of command invokers
     */
    public function getCommandInvokers()
    {
        return $this->getCommandChain()->getInvokers()->toArray();
    }
}