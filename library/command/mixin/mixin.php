<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Command Mixin
 * 
 * Class can be used as a mixin in classes that want to implement a chain of responsibility or chain of command pattern.
 *  
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Command
 */
class CommandMixin extends CommandCallbackAbstract implements CommandMixinInterface, CommandHandlerInterface
{
    /**
     * Chain of command object
     *
     * @var CommandChainInterface
     */
    private $__command_chain;

    /**
     * The command priority
     *
     * @var integer
     */
    protected $_priority;

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

        //Add the command handlers
        $handlers = (array) ObjectConfig::unbox($config->command_handlers);

        foreach ($handlers as $key => $value)
        {
            if (is_numeric($key)) {
                $this->addCommandHandler($value);
            } else {
                $this->addCommandHandler($key, $value);
            }
        }

        //Add the command callbacks
        foreach($this->getMixer()->getMethods() as $method)
        {
            $match = array();
            if (preg_match('/_(after|before)([A-Z]\S*)/', $method, $match)) {
                $this->addCommandCallback($match[1].'.'.strtolower($match[2]), $method);
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
            'command_chain'    => 'lib:command.chain',
            'command_handlers' => array(),
            'priority'         => self::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Mixin Notifier
     *
     * This function is called when the mixin is being mixed. It will get the mixer passed in.
     *
     * @param  ObjectMixable $mixer The mixer object
     * @return void
     */
    public function onMixin(ObjectMixable $mixer)
    {
        parent::onMixin($mixer);

        //Add mixer to the command chain to be able to execute the registered command handlers.
        $this->addCommandHandler($this);
    }

    /**
     * Execute the callbacks
     *
     * @param CommandInterface         $command    The command
     * @param CommandChainInterface    $chain      The chain executing the command
     * @return mixed|null If a handler breaks, returns the break condition. NULL otherwise.
     */
    public function execute(CommandInterface $command, CommandChainInterface $chain)
    {
        return parent::invokeCallbacks($command);
    }

    /**
     * Invoke a command by calling all registered handlers
     *
     * If a command handler returns the 'break condition' the executing is halted. If no break condition is specified the
     * the command chain will execute all command handlers, regardless of the handler result returned.
     *
     * @param  string|CommandInterface  $command    The command name or a CommandInterface object
     * @param  array|\Traversable       $attributes An associative array or a Traversable object
     * @param  ObjectInterface          $subject    The command subject
     * @return mixed|null If a handler breaks, returns the break condition. NULL otherwise.
     */
    public function invokeCommand($command, $attributes = null, $subject = null)
    {
        return $this->getCommandChain()->execute($command, $attributes, $subject);
    }

    /**
     * Invoke a command callback or delegate invocation to the mixer
     *
     * @param string            $method    The name of the method to be executed
     * @param CommandInterface  $command   The command
     * @return mixed Return the result of the handler.
     */
    public function invokeCommandCallback($method, CommandInterface $command)
    {
        $mixer = $this->getMixer();

        if($mixer instanceof CommandCallbackDelegate) {
            $result = $mixer->invokeCommandCallback($method, $command);
        } else {
            $result = $mixer->$method($command);
        }

        return $result;
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
            $config = array('break_condition' => $this->getBreakCondition());
            $this->__command_chain = $this->getObject($this->__command_chain, $config);

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
     * Add a command callback
     *
     * If the handler has already been added. It will not be re-added but parameters will be merged. This allows to
     * change or add parameters for existing handlers.
     *
     * @param  	string          $command  The command name to register the handler for
     * @param 	string|\Closure $method   The name of the method or a Closure object
     * @param   array|object    $params   An associative array of config parameters or a ObjectConfig object
     * @throws  \InvalidArgumentException If the method does not exist
     * @return  CommandMixin
     */
    public function addCommandCallback($command, $method, $params = array())
    {
        if (is_string($method) && !method_exists($this->getMixer(), $method))
        {
            throw new \InvalidArgumentException(
                'Method does not exist '.get_class().'::'.$method
            );
        }

        return parent::addCommandCallback($command, $method, $params);
    }

    /**
     * Attach a command to the chain
     *
     * @param  mixed $handler An object that implements KCommandHandlerInterface, an KObjectIdentifier
     *                        or valid identifier string
     * @param  array  $config  An optional associative array of configuration options
     * @return ObjectInterface The mixer object
     */
    public function addCommandHandler($handler, $config = array())
    {
        //Create the complete identifier if a partial identifier was passed
        if (is_string($handler) && strpos($handler, '.') === false)
        {
            $identifier = $this->getIdentifier()->toArray();
            $identifier['path'] = array('command', 'handler');
            $identifier['name'] = $handler;

            $identifier = $this->getIdentifier($identifier);
        }
        else
        {
            if($handler instanceof CommandHandlerInterface) {
                $identifier = $handler->getIdentifier();
            } else {
                $identifier = $this->getIdentifier($handler);
            }
        }

        if (!$this->getCommandChain()->getHandlers()->hasIdentifier($identifier))
        {
            if (!($handler instanceof CommandHandlerInterface)) {
                $handler = $this->getObject($identifier, $config);
            }

            if (!($handler instanceof CommandHandlerInterface))
            {
                throw new \UnexpectedValueException(
                    "Command Handler $identifier does not implement CommandHandlerInterface"
                );
            }

            //Enqueue the handler
            $this->getCommandChain()->addHandler($handler);
        }

        return $this->getMixer();
    }

    /**
     * Removes a command from the chain
     *
     * @param  CommandHandlerInterface  $handler  The command handler
     * @return ObjectInterface The mixer object
     */
    public function removeCommandHandler(CommandHandlerInterface $handler)
    {
        $this->getCommandChain()->removeHandler($handler);
        return $this->getMixer();
    }

    /**
     * Check if a command handler exists
     *
     * @param  mixed $handler An object that implements KCommandHandlerInterface, an KObjectIdentifier
     *                        or valid identifier string
     * @return  boolean TRUE if the behavior exists, FALSE otherwise
     */
    public function hasCommandHandler($handler)
    {
        if($handler instanceof CommandHandlerInterface) {
            $identifier = $handler->getIdentifier();
        } else {
            $identifier = $this->getIdentifier($handler);
        }

        return $this->getCommandChain()->getHandlers()->hasIdentifier($identifier);
    }

    /**
     * Gets the command handlers
     *
     * @return array An array of command handlers
     */
    public function getCommandHandlers()
    {
        return $this->getCommandChain()->getHandlers()->toArray();
    }

    /**
     * Get the methods that are available for mixin
     *
     * @param  array $exclude     An array of public methods to be exclude
     * @return array An array of methods
     */
    public function getMixableMethods($exclude = array())
    {
        $exclude = array_merge($exclude, array('execute', 'getPriority', 'setBreakCondition', 'getBreakCondition',
            'invokeCommandCallbacks', 'invokeCommandCallback'));

        return parent::getMixableMethods($exclude);
    }

    /**
     * Get the priority of the handler
     *
     * @return	integer The handler priority
     */
    public function getPriority()
    {
        return $this->_priority;
    }
}