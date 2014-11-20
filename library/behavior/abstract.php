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
 * Abstract Behavior
 *
 * The abstract behavior will translate the command name to a method name format (eg, _before[Command] or _after[Command])
 * and add execute the method. Command handlers should be declared protected.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Behavior
 */
abstract class BehaviorAbstract extends CommandCallbackAbstract implements BehaviorInterface
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

        //Add the command callbacks
        foreach($this->getMethods() as $method)
        {
            $matches = array();
            if (preg_match('/_(after|before)([A-Z]\S*)/', $method, $matches)) {
                $this->addCommandCallback($matches[1].'.'.strtolower($matches[2]), $method);
            }
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
        ));

        parent::_initialize($config);
    }

    /**
     * Command handler
     *
     * @param CommandInterface         $command    The command
     * @param CommandChainInterface    $chain      The chain executing the command
     * @return mixed If a handler breaks, returns the break condition. Returns the result of the handler otherwise.
     */
    public function execute(CommandInterface $command, CommandChainInterface $chain)
    {
        return parent::invokeCallbacks($command);
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
     * Get an object handle
     *
     * @return string A string that is unique, or NULL
     * @see execute()
     */
    public function getHandle()
    {
        $handle = null;

        if($this->isSupported())
        {
            $callbacks = $this->getCommandCallbacks();

            if(!empty($callbacks)) {
                $handle = parent::getHandle();
            }
        }

        return $handle;
    }

    /**
     * Add a command callback
     *
     * If the handler has already been added. It will not be re-added but parameters will be merged. This allows to
     * change or add parameters for existing handlers.
     *
     * @param  	string          $command  The command name to register the handler for
     * @param 	string|\Closure $method   The name of the method or a Closure object
     * @param   array|object    $params   An associative array of config parameters or a KObjectConfig object
     * @throws  \InvalidArgumentException If the method does not exist
     * @return  CommandHandlerAbstract
     */
    public function addCommandCallback($command, $method, $params = array())
    {
        if (is_string($method) && !is_callable(array($this, $method)))
        {
            throw new \InvalidArgumentException(
                'Method does not exist '.__CLASS__.'::'.$method
            );
        }

        return parent::addCommandCallback($command, $method, $params);
    }

    /**
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a lamda function with function name 'is[Behavior]' to allow client code to
     * check if the behavior is supported.
     *
     * Function will check if the behavior is supported by calling {@link isSupported()}. Is the behavior is not
     * supported on the mixer no mixable methods will be returned, only an 'is[Behavior]' method will be added which
     * return FALSE when called.
     *
     * @param  array           $exclude     An array of public methods to be exclude
     * @return array An array of methods
     */
    public function getMixableMethods($exclude = array())
    {
        $methods = array();
        if($this->isSupported())
        {
            $exclude = array_merge($exclude, array('execute', 'invokeCallbacks', 'getIdentifier', 'getPriority',
                'getHandle', 'getName', 'getObject', 'getConfig', 'setBreakCondition', 'getBreakCondition',
                'addCommandCallback', 'removeCommandCallback', 'getCommandCallbacks', 'invokeCommandCallback',
                'isSupported'));

            $methods = parent::getMixableMethods($exclude);
        }

        if(!isset($exclude['is' . ucfirst($this->getName())])) {
            $methods['is' . ucfirst($this->getName())] = $this->isSupported();
        }

        return $methods;
    }

    /**
     * Get an instance of an object identifier
     *
     * @param ObjectIdentifier|string $identifier An ObjectIdentifier or valid identifier string
     * @param array  			      $config     An optional associative array of configuration settings.
     * @return ObjectInterface|Callable  Return object on success, throws exception on failure.
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

    /**
     * Check if the behavior is supported
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        return true;
    }
}