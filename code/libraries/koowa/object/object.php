<?php
/**
 * @version        $Id$
 * @package        Koowa_Object
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Object class
 *
 * Provides getters and setters, mixin, object handles
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Object
 */
class KObject implements KObjectHandlable, KObjectServiceable
{
    /**
     * Class methods
     *
     * @var array
     */
    private $__methods = array();

    /**
     * Mixed in methods
     *
     * @var array
     */
    protected $_mixed_methods = array();

    /**
     * The service identifier
     *
     * @var KServiceIdentifier
     */
    private $__service_identifier;

    /**
     * The service manager
     *
     * @var KServiceManager
     */
    private $__service_manager;

    /**
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KObjectDecorator
     */
    public function __construct(KConfig $config)
    {
        //Set the service container
        if (isset($config->service_manager)) {
            $this->__service_manager = $config->service_manager;
        }

        //Set the service identifier
        if (isset($config->service_identifier)) {
            $this->__service_identifier = $config->service_identifier;
        }

        //Initialise the object
        $this->_initialize($config);

        //Add the mixins
        $mixins = (array)KConfig::unbox($config->mixins);

        foreach ($mixins as $key => $value) {
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
     * @param   KConfig $object An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'mixins' => array(),
        ));
    }

    /**
     * Set the object properties
     *
     * @param   string|array|object $property The name of the property, an associative array or an object
     * @param   mixed               $value    The value of the property
     * @throws  KObjectException If trying to access protected or private properties
     * @return  KObject
     */
    public function set($property, $value = null)
    {
        if (is_object($property)) {
            $property = get_object_vars($property);
        }

        if (is_array($property)) {
            foreach ($property as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            if ('_' == substr($property, 0, 1)) {
                throw new KObjectException(
                    "Protected or private properties can't be set outside of object scope in " . get_class($this)
                );
            }

            $this->$property = $value;
        }

        return $this;
    }

    /**
     * Get the object properties
     *
     * If no property name is given then the function will return an associative array of all properties.
     * If the property does not exist and a  default value is specified this is returned, otherwise the
     * function return NULL.
     *
     * @param   string  $property The name of the property
     * @param   mixed   $default  The default value
     * @return  mixed   The value of the property, an associative array or NULL
     */
    public function get($property = null, $default = null)
    {
        $result = $default;

        if (is_null($property)) {
            $result = get_object_vars($this);

            foreach ($result as $key => $value) {
                if ('_' == substr($key, 0, 1)) {
                    unset($result[$key]);
                }
            }
        } else {
            //PHP bug 22917 : Isset is not allowed on virtual properties
            $r = $this->$property;
            if (isset($r)) {
                $result = $this->$property;
            }
        }

        return $result;
    }

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed in objects, in a LIFO order.
     *
     * @@param   mixed    An object that implements KMixinInterface, KServiceIdentifier object
     *                     or valid identifier string
     * @param    array An optional associative array of configuration options
     * @return  KObject
     */
    public function mixin($mixin, $config = array())
    {
        if (!($mixin instanceof KMixinInterface)) {
            if (!($mixin instanceof KServiceIdentifier)) {
                //Create the complete identifier if a partial identifier was passed
                if (is_string($mixin) && strpos($mixin, '.') === false) {
                    $identifier = clone $this->getIdentifier();
                    $identifier->path = 'mixin';
                    $identifier->name = $mixin;
                } else $identifier = $this->getIdentifier($mixin);
            }

            $mixin = new $identifier->classname(new KConfig($config));
        }

        //Set the mixed methods and overwrite existing methods
        $this->_mixed_methods = array_merge($this->_mixed_methods, $mixin->getMixableMethods($this));

        //Set the mixer
        $mixin->setMixer($this);

        return $this;
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

        foreach ($objects as $object) {
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
        if (!$this->__methods) {
            $methods = array();

            $reflection = new ReflectionClass($this);
            foreach ($reflection->getMethods() as $method) {
                $methods[] = $method->name;
            }

            $this->__methods = array_merge($methods, array_keys($this->_mixed_methods));
        }

        return $this->__methods;
    }

    /**
     * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
     *
     * @param    string|object    $identifier The class identifier or identifier object
     * @param    array              $config     An optional associative array of configuration settings.
     * @throws    \RuntimeException If the service manager has not been defined.
     * @return    object          Return object on success, throws exception on failure
     * @see     KObjectServiceable
     */
    final public function getService($identifier = null, array $config = array())
    {
        if (isset($identifier)) {
            if (!isset($this->__service_manager)) {
                throw new RuntimeException(
                    "Failed to call " . get_class($this) . "::getService(). No service_manager object defined."
                );
            }

            $result = $this->__service_manager->get($identifier, $config);
        } else $result = $this->__service_manager;

        return $result;
    }

    /**
     * Gets the service identifier.
     *
     * @param    string|object    $identifier The class identifier or identifier object
     * @throws    \RuntimeException If the service manager has not been defined.
     * @return    KServiceIdentifier
     * @see     KObjectServiceable
     */
    final public function getIdentifier($identifier = null)
    {
        if (isset($identifier)) {
            if (!isset($this->__service_manager)) {
                throw new RuntimeException(
                    "Failed to call " . get_class($this) . "::getIdentifier(). No service_manager object defined."
                );
            }

            $result = $this->__service_manager->getIdentifier($identifier);
        } else  $result = $this->__service_identifier;

        return $result;
    }

    /**
     * Preform a deep clone of the object.
     *
     * @return void
     */
    public function __clone()
    {
        foreach ($this->_mixed_methods as $method => $object) {
            if (!$object instanceof Closure) {
                $this->_mixed_methods[$method] = clone $object;
            }
        }
    }

    /**
     * Search the mixin method map and call the method or trigger an error
     *
     * @param  string $method    The function name
     * @param  array  $arguments The function arguments
     * @throws BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        if (isset($this->_mixed_methods[$method])) {
            $result = null;

            if ($this->_mixed_methods[$method] instanceof Closure) {
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
            } else {
                $object = $this->_mixed_methods[$method];

                //Switch the mixin's attached mixer
                $object->setMixer($this);

                // Call_user_func_array is ~3 times slower than direct method calls.
                switch (count($arguments)) {
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

        throw new BadMethodCallException('Call to undefined method :' . $method);
    }
}