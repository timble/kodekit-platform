<?php
/**
 * @package     Koowa_Object
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Object
 */
class Object implements ObjectInterface, ObjectHandlable, ObjectMixable, ObjectDecoratable
{
    /**
     * Class methods
     *
     * @var array
     */
    private $__methods = array();

    /**
     * The object identifier
     *
     * @var ObjectIdentifier
     */
    private $__object_identifier;

    /**
     * The object manager
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
     * Mixed in methods
     *
     * @var array
     */
    protected $_mixed_methods = array();

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  A ObjectConfig object with optional configuration options
     * @return Object
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

        //Initialise the object
        $this->_initialize($config);

        //Set the object config
        $this->__object_config = $config;

        //Add the mixins
        $mixins = (array) ObjectConfig::unbox($config->mixins);

        foreach ($mixins as $key => $value)
        {
            if (is_numeric($key)) {
                $this->mixin($value);
            } else {
                $this->mixin($key, $value);
            }
        }
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
            'mixins' => array(),
        ));
    }

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed in objects, in a LIFO order.
     *
     * @@param   mixed  $mixin  An object that implements ObjectMixinInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return  ObjectMixinInterface
     */
    public function mixin($mixin, $config = array())
    {
        if (!($mixin instanceof ObjectMixinInterface))
        {
            if (!($mixin instanceof ObjectIdentifier))
            {
                //Create the complete identifier if a partial identifier was passed
                if (is_string($mixin) && strpos($mixin, '.') === false)
                {
                    $identifier = clone $this->getIdentifier();
                    $identifier->path = 'mixin';
                    $identifier->name = $mixin;
                }
                else $identifier = $this->getIdentifier($mixin);
            }
            else $identifier = $mixin;

            $config = new ObjectConfig($config);
            $config->mixer = $this;

            $mixin = new $identifier->classname($config);

            if(!$mixin instanceof ObjectMixinInterface)
            {
                throw new \UnexpectedValueException(
                    'Mixin: '.get_class($mixin).' does not implement ObjectMixinInterface'
                );
            }
        }

        $mixed_methods =  $mixin->getMixableMethods($this);

        //Set the mixed methods
        $this->_mixed_methods = array_merge($this->_mixed_methods, $mixed_methods);

        //Set the object methods
        $this->__methods = array_unique(array_merge($this->__methods, array_keys($mixed_methods)));

        //Notify the mixin
        $mixin->onMixin($this);

        return $mixin;
    }

    /**
     * Decorate the object
     *
     * When using decorate(), the object will be decorated by the decorator
     *
     * @@param   mixed  $decorator  An object that implements ObjectDecorator, ObjectIdentifier object
     *                              or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return   ObjectDecoratorInterface
     */
    public function decorate($decorator, $config = array())
    {
        if (!($decorator instanceof ObjectDecoratorInterface))
        {
            if (!($decorator instanceof ObjectIdentifier))
            {
                //Create the complete identifier if a partial identifier was passed
                if (is_string($decorator) && strpos($decorator, '.') === false)
                {
                    $identifier = clone $this->getIdentifier();
                    $identifier->path = 'decorator';
                    $identifier->name = $decorator;
                }
                else $identifier = $this->getIdentifier($decorator);
            }
            else $identifier = $decorator;

            $config = new ObjectConfig($config);
            $config->delegate = $this;

            $decorator = new $identifier->classname($config);

            if(!$decorator instanceof ObjectDecoratorInterface)
            {
                throw new \UnexpectedValueException(
                    'Decorator: '.get_class($decorator).' does not implement ObjectDecoratorInterface'
                );
            }
        }

        //Notify the decorator
        $decorator->onDecorate($this);

        return $decorator;
    }

    /**
     * Checks if the object or one of it's mixin's inherits from a class.
     *
     * @param   string|object   $class The class to check
     * @return  bool Returns TRUE if the object inherits from the class
     */
    public function inherits($class)
    {
        if ($this instanceof $class) {
            return true;
        }

        $objects = array_values($this->_mixed_methods);

        foreach ($objects as $object)
        {
            if ($object instanceof $class) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a handle for this object
     *
     * This function returns an unique identifier for the object. This id can be used as a hash key for storing objects
     * or for identifying an object
     *
     * @return string A string that is unique
     */
    public function getHandle()
    {
        return spl_object_hash($this);
    }

    /**
     * Get a list of all the available methods
     *
     * This function returns an array of all the methods, both native and mixed in
     *
     * @return array An array
     */
    public function getMethods()
    {
        if (!$this->__methods)
        {
            $methods = array();

            $reflection = new \ReflectionClass($this);
            foreach ($reflection->getMethods() as $method) {
                $methods[] = $method->name;
            }

            $this->__methods = $methods;
        }

        return $this->__methods;
    }

    /**
     * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
     *
     * @param    string|object    $identifier A valid identifier string or object implementing ObjectInterface
     * @param    array            $config     An optional associative array of configuration settings.
     * @throws   \RuntimeException If the service manager has not been defined.
     * @return   ObjectInterface  Return object on success, throws exception on failure
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
     * Preform a deep clone of the object.
     *
     * @return void
     */
    public function __clone()
    {
        foreach ($this->_mixed_methods as $method => $object)
        {
            if (!$object instanceof \Closure) {
                $this->_mixed_methods[$method] = clone $object;
            }
        }
    }

    /**
     * Search the mixin method map and call the method or trigger an error
     *
     * @param  string $method    The function name
     * @param  array  $arguments The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        if (isset($this->_mixed_methods[$method]))
        {
            $result = null;

            if ($this->_mixed_methods[$method] instanceof \Closure)
            {
                $closure = $this->_mixed_methods[$method];

                switch (count($arguments)) {
                    case 0 :
                        $result = $closure();
                        break;
                    case 1 :
                        $result = $closure($arguments[0]);
                        break;
                    case 2 :
                        $result = $closure($arguments[0], $arguments[1]);
                        break;
                    case 3 :
                        $result = $closure($arguments[0], $arguments[1], $arguments[2]);
                        break;
                    default:
                        // Resort to using call_user_func_array for many segments
                        $result = call_user_func_array($closure, $arguments);
                }
            }
            else
            {
                $object = $this->_mixed_methods[$method];

                //Switch the mixin's attached mixer
                $object->setMixer($this);

                // Call_user_func_array is ~3 times slower than direct method calls.
                switch (count($arguments))
                {
                    case 0 :
                        $result = $object->$method();
                        break;
                    case 1 :
                        $result = $object->$method($arguments[0]);
                        break;
                    case 2 :
                        $result = $object->$method($arguments[0], $arguments[1]);
                        break;
                    case 3 :
                        $result = $object->$method($arguments[0], $arguments[1], $arguments[2]);
                        break;
                    default:
                        // Resort to using call_user_func_array for many segments
                        $result = call_user_func_array(array($object, $method), $arguments);
                }
            }

            return $result;
        }

        throw new \BadMethodCallException('Call to undefined method :' . $method);
    }
}