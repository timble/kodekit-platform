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
 * Command Invoker
 *
 * The command invoker will translate the command name to a method name, format and call it for the object class to
 * handle it if the method exists.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
 */
abstract class CommandInvokerAbstract extends Object implements CommandInvokerInterface
{
    /**
     * Array of command handlers
     *
     * $var array
     */
    private $__command_handlers = array();

    /**
     * The command priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Object constructor
     *
     * @param ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the command priority
        $this->_priority = $config->priority;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Command handler
     *
     * @param  CommandInterface $command    The command
     * @param  mixed            $condition  The break condition
     * @return array|mixed Returns an array of the handler results in FIFO order. If a handler breaks and the break
     *                     condition is not NULL returns the break condition.
     */
    public function executeCommand(CommandInterface $command, $condition = null)
    {
        $result = array();

        if(isset($this->__command_handlers[$command->getName()]))
        {
            foreach($this->__command_handlers[$command->getName()] as $handler)
            {
                $method = $handler['method'];
                $params = $handler['params'];

                try
                {
                    if($method instanceof \Closure) {
                        $result[] = $method($command->append($params));
                    } else {
                        $result[$method] = $this->$method($command->append($params));
                    }
                }
                catch (CommandExceptionHandler $e) {
                    $result[] = $e;
                }

                if($condition !== null && current($result) === $condition)
                {
                    $result = current($result);
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Add a command handler
     *
     * If the handler has already been added. It will not be re-added but parameters will be merged. This allows to
     * change or add parameters for existing handlers.
     *
     * @param  	string           $command  The command name to register the handler for
     * @param 	string|\Closure  $method   The name of the method or a Closure object
     * @param   array|object     $params    An associative array of config parameters or a KObjectConfig object
     * @throws  \InvalidArgumentException If the callback is not a callable
     * @return  CommandInvokerAbstract
     */
    public function addCommandHandler($command, $method, $params = array())
    {
        if (is_string($method) && !method_exists($this, $method))
        {
            throw new \InvalidArgumentException(
                'Method does not exist '.__CLASS__.'::'.$method
            );
        }

        $params  = (array) ObjectConfig::unbox($params);
        $command = strtolower($command);

        if (!isset($this->__command_handlers[$command]) ) {
            $this->__command_handlers[$command] = array();
        }

        if($method instanceof \Closure) {
            $index = spl_object_hash($method);
        } else {
            $index = $method;
        }

        if(!isset($this->__command_handlers[$command][$index]))
        {
            $this->__command_handlers[$command][$index]['method'] = $method;
            $this->__command_handlers[$command][$index]['params'] = $params;
        }
        else  $this->__command_handlers[$command][$index]['params'] = array_merge($this->__command_handlers[$command][$index]['params'], $params);

        return $this;
    }

    /**
     * Remove a command handler
     *
     * @param  	string	        $command  The command to unregister the handler from
     * @param 	string|\Closure	$method   The name of the method or a Closure object to unregister
     * @return  CommandInvokerAbstract
     */
    public function removeCommandHandler($command, $method)
    {
        $command = strtolower($command);

        if (isset($this->__command_handlers[$command]) )
        {
            if($method instanceof \Closure) {
                $index = spl_object_hash($method);
            } else {
                $index = $method;
            }

            unset($this->__command_handlers[$command][$index]);
        }

        return $this;
    }

    /**
     * Get the handlers for a command
     *
     * @param string $command   The command
     * @return  array An array of command handlers
     */
    public function getCommandHandlers($command)
    {
        $result = array();
        if (isset($this->__command_handlers[$command]) ) {
            $result = array_values($this->__command_handlers[$command]);
        }

        return $result;
    }

    /**
     * Get the priority of the command
     *
     * @return  integer The command priority
     */
    public function getPriority()
    {
        return $this->_priority;
    }
}