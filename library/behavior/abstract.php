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
 * Abstract Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Behavior
 */
abstract class BehaviorAbstract extends ObjectMixinAbstract implements BehaviorInterface
{
    /**
     * The service identifier
     *
     * @var ObjectIdentifier
     */
    private $__object_identifier;

    /**
     * The service manager
     *
     * @var ObjectManager
     */
    private $__object_manager;

    /**
     * The object config
     *
     * @var ObjectConfig
     */
    private $__object_config;

    /**
     * Array of command handlers
     *
     * $var array
     */
    private $__command_handlers = array();

    /**
     * The behavior priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Constructor.
     *
     * @param  ObjectConfig $config  A ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        //Set the object manager
        if (!$config->object_manager instanceof ObjectManagerInterface)
        {
            throw new \InvalidArgumentException(
                'object_manager [ObjectManagerInterface] config option is required, "'.gettype($config->object_manager).'" given.'
            );
        }
        else $this->__object_manager = $config->object_manager;

        //Set the object identifier
        if (!$config->object_identifier instanceof ObjectIdentifierInterface)
        {
            throw new \InvalidArgumentException(
                'object_identifier [ObjectIdentifierInterface] config option is required, "'.gettype($config->object_identifier).'" given.'
            );
        }
        else $this->__object_identifier = $config->object_identifier;

        parent::__construct($config);

        //Set the object config
        $this->__object_config = $config;

        //Set the command priority
        $this->_priority = $config->priority;

        //Automatically mixin the behavior
        if ($config->auto_mixin) {
            $this->mixin($this);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config A ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => self::PRIORITY_NORMAL,
            'auto_mixin' => false
        ));

        parent::_initialize($config);
    }

    /**
     * Get the priority of a behavior
     *
     * @return  integer The command priority
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * Get the behavior name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getIdentifier()->name;
    }

    /**
     * Command handler
     *
     * @param  CommandInterface $command    The command
     * @param  mixed            $condition  The break condition
     * @return array|mixed Returns an array of the callback results in FIFO order. If a handler breaks and the break
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
     * @param  	string          $command  The command name to register the handler for
     * @param 	string|\Closure  $method   The name of the method or a Closure object
     * @param   array|object    An associative array of config parameters or a KObjectConfig object
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
     * Get an object handle
     *
     * This function only returns a valid handle if one or more command handler functions are defined. A commend handler
     * function needs to follow the following format : '_afterX[Event]' or '_beforeX[Event]' to be recognised.
     *
     * @return string A string that is unique, or NULL
     * @see execute()
     */
    public function getHandle()
    {
        $methods = $this->getMethods();

        foreach ($methods as $method)
        {
            if (substr($method, 0, 7) == '_before' || substr($method, 0, 6) == '_after') {
                return parent::getHandle();
            }
        }

        return null;
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
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a function of format is[Behavior] to allow client code to check if the
     * behavior is callable.
     *
     * @param  ObjectInterface $mixer The mixer requesting the mixable methods.
     * @return array An array of methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);
        $methods['is' . ucfirst($this->getIdentifier()->name)] = function() { return true; };

        unset($methods['executeCommand']);
        unset($methods['getIdentifier']);
        unset($methods['getPriority']);
        unset($methods['getHandle']);
        unset($methods['getObject']);
        unset($methods['getConfig']);
        unset($methods['getName']);
        unset($methods['addCommandHandler']);
        unset($methods['removeCommandHandler']);
        unset($methods['getCommandHandlers']);


        return $methods;
    }

    /**
     * Get an instance of an object identifier
     *
     * @param ObjectIdentifier|string $identifier An ObjectIdentifier or valid identifier string
     * @param array  			      $config     An optional associative array of configuration settings.
     * @return ObjectInterface  Return object on success, throws exception on failure.
     */
    final public function getObject($identifier, array $config = array())
    {
        $result = $this->__object_manager->getObject($identifier, $config);
        return $result;
    }

    /**
     * Gets the service identifier.
     *
     * If no identifier is passed the object identifier of this object will be returned. Function recursively
     * resolves identifier aliases and returns the aliased identifier.
     *
     * @param   string|object    $identifier The class identifier or identifier object
     * @return  ObjectIdentifier
     */
    final public function getIdentifier($identifier = null)
    {
        if (isset($identifier)) {
            $result = $this->__object_manager->getIdentifier($identifier);
        } else {
            $result = $this->__object_identifier;
        }

        return $result;
    }

    /**
     * Get the object configuration
     *
     * If no identifier is passed the object config of this object will be returned. Function recursively
     * resolves identifier aliases and returns the aliased identifier.
     *
     *  @param   string|object    $identifier A valid identifier string or object implementing ObjectInterface
     * @return ObjectConfig
     */
    public function getConfig($identifier = null)
    {
        if (isset($identifier)) {
            $result = $this->__object_manager->getIdentifier($identifier)->getConfig();
        } else {
            $result = $this->__object_config;
        }

        return $result;
    }
}