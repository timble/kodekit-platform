<?php
/**
 * @package     Koowa_Object
 * @subpackage  Decorator
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Decorator Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Decorator
 */
class ObjectDecorator implements ObjectDecoratorInterface
{
    /**
     * Class methods
     *
     * @var array
     */
    private $__methods = array();

    /**
     *  The object being decorated
     *
     * @var Object
     */
    protected $_delegate;

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  A ObjectConfig object with optional configuration options
     * @return ObjectDecorator
     */
    public function __construct(ObjectConfig $config)
    {
        //Initialise the object
        $this->_initialize($config);

        //Set the delegate
        if(isset($config->delegate)) {
            $this->setDelegate($config->delegate);
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
            'delegate' => null,
        ));
    }

    /**
     * Get the decorated object
     *
     * @return ObjectDecoratable The decorated object
     */
    public function getDelegate()
    {
        return $this->_delegate;
    }

    /**
     * Set the decorated object
     *
     * @param   ObjectDecoratable $delegate The decorated object
     * @return  ObjectDecorator
     */
    public function setDelegate(ObjectDecoratable $delegate)
    {
        $this->_delegate = $delegate;
        return $this;
    }

    /**
     * Decorate Notifier
     *
     * This function is called when an object is being decorated. It will get the object passed in.
     *
     * @param ObjectDecoratable $delegate The object being decorated
     * @return void
     */
    public function onDecorate(ObjectDecoratable $delegate)
    {
        $this->setDelegate($delegate);
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
        return $this->getDelegate()->getHandle();
    }

    /**
     * Get a list of all the available methods
     *
     * This function returns an array of all the methods, both native and mixed. It will also return the methods
     * exposed by the decorated object.
     *
     * @return array An array
     */
    public function getMethods()
    {
        if (!$this->__methods)
        {
            $methods = array();
            $object = $this->getDelegate();

            if (!($object instanceof ObjectMixable))
            {
                $reflection = new \ReflectionClass($object);
                foreach ($reflection->getMethods() as $method) {
                    $methods[] = $method->name;
                }
            }
            else $methods = $object->getMethods();

            $this->__methods = array_merge(parent::getMethods(), $methods);
        }

        return $this->__methods;
    }

    /**
     * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
     *
     * @param	string|object	$identifier The class identifier or identifier object
     * @param	array  			$config     An optional associative array of configuration settings.
     * @return	Object Return object on success, throws exception on failure
     */
    public function getObject($identifier = null, array $config = array())
    {
        return $this->getDelegate()->getObject($identifier, $config);
    }

    /**
     * Get an object identifier.
     *
     * @param	string|object	$identifier A valid identifier string or object implementing ObjectInterface
     * @return	ObjectIdentifier
     */
    public function getIdentifier($identifier = null)
    {
        return $this->getDelegate()->getIdentifier($identifier);
    }

    /**
     * Get the object configuration
     *
     * @param   string|object    $identifier A valid identifier string or object implementing ObjectInterface
     * @return ObjectConfig
     */
    public function getConfig($identifier = null)
    {
        return $this->getDelegate()->getObject($identifier);
    }

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed in objects, in a LIFO order.
     *
     * @@param   mixed  $mixin  An object that implements ObjectMixinInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return  ObjectInterface
     */
    public function mixin($mixin, $config = array())
    {
        $this->getDelegate()->mixin($mixin, $config);
        return $this;
    }

    /**
     * Decorate the object
     *
     * When using decorate(), the object will be decorated by the decorator
     *
     * @@param   mixed  $decorator  An object that implements ObjectDecorator, ObjectIdentifier object
     *                              or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return   ObjectDecorator
     */
    public function decorate($decorator, $config = array())
    {
        $decorator = $this->getDelegate($decorator, $config);

        //Notify the decorator and set the delegate
        $decorator->onDecorate($this);

        return $decorator;
    }

    /**
     * Checks if the decorated object or one of it's mixin's inherits from a class.
     *
     * @param   string|object $class  The class to check
     * @return  boolean  Returns TRUE if the object inherits from the class
     */
    public function inherits($class)
    {
        $result = false;
        $object = $this->getDelegate();

        if ($object instanceof ObjectMixable) {
            $result = $object->inherits($class);
        } else {
            $result = $object instanceof $class;
        }

        return $result;
    }

    /**
     * Overloaded set function
     *
     * @param  string $key   The variable name
     * @param  mixed  $value The variable value.
     * @return mixed
     */
    public function __set($key, $value)
    {
        $this->getDelegate()->$key = $value;
    }

    /**
     * Overloaded get function
     *
     * @param  string $key  The variable name.
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getDelegate()->$key;
    }

    /**
     * Overloaded isset function
     *
     * Allows testing with empty() and isset() functions
     *
     * @param  string $key The variable name
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->getDelegate()->$key);
    }

    /**
     * Overloaded isset function
     *
     * Allows unset() on object properties to work
     *
     * @param string $key The variable name.
     * @return void
     */
    public function __unset($key)
    {
        if (isset($this->getDelegate()->$key)) {
            unset($this->getDelegate()->$key);
        }
    }

    /**
     * Overloaded call function
     *
     * @param  string     $method    The function name
     * @param  array      $arguments The function arguments
     * @throws \BadMethodCallException     If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        $object = $this->getDelegate();

        //Check if the method exists
        if ($object instanceof ObjectMixable)
        {
            $methods = $object->getMethods();
            $exists = in_array($method, $methods);
        }
        else $exists = method_exists($object, $method);

        //Call the method if it exists
        if ($exists)
        {
            $result = null;

            // Call_user_func_array is ~3 times slower than direct method calls.
            switch (count($arguments))
            {
                case 0 :
                    $result = $object->$method();
                    break;
                case 1 :
                    $result = $object->$method($arguments[0]);
                    break;
                case 2:
                    $result = $object->$method($arguments[0], $arguments[1]);
                    break;
                case 3:
                    $result = $object->$method($arguments[0], $arguments[1], $arguments[2]);
                    break;
                default:
                    // Resort to using call_user_func_array for many segments
                    $result = call_user_func_array(array($object, $method), $arguments);
            }

            //Allow for method chaining through the decorator
            $class = get_class($object);
            if ($result instanceof $class) {
                return $this;
            }

            return $result;
        }

        return parent::__call($method, $arguments);
    }
}