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
 * Abstract Command Handler
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Command
 */
abstract class CommandCallbackAbstract extends ObjectMixinAbstract
{
    /**
     * Array of command callbacks
     *
     * $var array
     */
    private $__command_callbacks = array();

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
     * @param ObjectConfig  $config  An optional KObjectConfig object with configuration options
     * @return CommandChain
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the chain break condition
        $this->_break_condition = $config->break_condition;
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
        ));

        parent::_initialize($config);
    }

    /**
     * Invoke a command by calling all the registered callbacks
     *
     * @param  string|CommandInterface  $command    The command name or a KCommandInterface object
     * @param  array|\Traversable       $attributes An associative array or a Traversable object
     * @param  ObjectInterface          $subject    The command subject
     * @return mixed|null If a callback break, returns the break condition. NULL otherwise.
     */
    public function invokeCallbacks($command, $attributes = null, $subject = null)
    {
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

        foreach($this->getCommandCallbacks($command->getName()) as $handler)
        {
            $method = $handler['method'];
            $params = $handler['params'];

            if(is_string($method)) {
                $result = $this->invokeCommandCallback($method, $command->append($params));
            } else {
                $result = $method($command->append($params));
            }

            if($result !== null && $result === $this->getBreakCondition()) {
                return $result;
            }
        }
    }

    /**
     * Invoke a command callback
     *
     * @param string            $method    The name of the method to be executed
     * @param CommandInterface  $command   The command
     * @return mixed Return the result of the handler.
     */
    public function invokeCommandCallback($method, CommandInterface $command)
    {
        return $this->$method($command);
    }

    /**
     * Add a callback
     *
     * If the handler has already been added. It will not be re-added but parameters will be merged. This allows to
     * change or add parameters for existing handlers.
     *
     * @param  	string          $command  The command name to register the handler for
     * @param 	string|\Closure  $method   The name of a method or a Closure object
     * @param   array|object    $params   An associative array of config parameters or a KObjectConfig object
     * @throws  \InvalidArgumentException If the method does not exist
     * @return  CommandCallbackAbstract
     */
    public function addCommandCallback($command, $method, $params = array())
    {
        $params  = (array) ObjectConfig::unbox($params);
        $command = strtolower($command);

        if (!isset($this->__command_callbacks[$command]) ) {
            $this->__command_callbacks[$command] = array();
        }

        if($method instanceof \Closure) {
            $index = spl_object_hash($method);
        } else {
            $index = $method;
        }

        if(!isset($this->__command_callbacks[$command][$index]))
        {
            $this->__command_callbacks[$command][$index]['method'] = $method;
            $this->__command_callbacks[$command][$index]['params'] = $params;
        }
        else  $this->__command_callbacks[$command][$index]['params'] = array_merge($this->__command_callbacks[$command][$index]['params'], $params);

        return $this;
    }

    /**
     * Remove a callback
     *
     * @param  	string	        $command  The command to unregister the handler from
     * @param 	string|\Closure	$method   The name of the method or a Closure object to unregister
     * @return  CommandCallbackAbstract
     */
    public function removeCommandCallback($command, $method)
    {
        $command = strtolower($command);

        if (isset($this->__command_callbacks[$command]) )
        {
            if($method instanceof \Closure) {
                $index = spl_object_hash($method);
            } else {
                $index = $method;
            }

            unset($this->__command_callbacks[$command][$index]);
        }

        return $this;
    }

    /**
     * Get the command callbacks
     *
     * @return array
     */
    public function getCommandCallbacks($command = null)
    {
        $result = array();
        if($command)
        {
            if(isset($this->__command_callbacks[$command])) {
                $result = $this->__command_callbacks[$command];
            }
        }
        else $result = $this->__command_callbacks;

        return $result;
    }

    /**
     * Set the break condition
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
     * Get the break condition
     *
     * @return mixed|null   Returns the break condition, or NULL if not break condition is set.
     */
    public function getBreakCondition()
    {
        return $this->_break_condition;
    }
}