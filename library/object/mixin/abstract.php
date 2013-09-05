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
 * Abstract Object Mixin
 *
 * This class does not extend from Object and acts as a special core class that is intended to offer semi-multiple
 * inheritance features to Object derived classes.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
abstract class ObjectMixinAbstract implements ObjectMixinInterface
{
    /**
     * The object doing the mixin
     *
     * @var Object
     */
    private $__mixer;

    /**
     * Class methods
     *
     * @var array
     */
    private $__methods = array();

    /**
     * List of mixable methods
     *
     * @var array
     */
    private $__mixable_methods;

    /**
     * Object constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        //Initialise
        $this->_initialize($config);

        //Set the mixer
        if(isset($config->mixer)) {
            $this->setMixer($config->mixer);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'mixer' => null,
        ));
    }

    /**
     * Get the mixer object
     *
     * @return ObjectMixable The mixer object
     */
    public function getMixer()
    {
        return $this->__mixer;
    }

    /**
     * Set the mixer object
     *
     * @param ObjectMixable $mixer The mixer object
     * @return ObjectMixinAbstract
     */
    public function setMixer(ObjectMixable $mixer)
    {
        $this->__mixer = $mixer;
        return $this;
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
     * Get the methods that are available for mixin.
     *
     * Only public methods can be mixed
     *
     * @param ObjectMixable $mixer The mixer requesting the mixable methods.
     * @return array An array of public methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null)
    {
        if (!$this->__mixable_methods)
        {
            $methods = array();

            //Get all the public methods
            $reflection = new \ReflectionClass($this);
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $methods[$method->name] = $this;
            }

            //Remove the base class methods
            $reflection = new \ReflectionClass(__CLASS__);
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method)
            {
                if (isset($methods[$method->name])) {
                    unset($methods[$method->name]);
                }
            }

            $this->__mixable_methods = $methods;
        }

        return $this->__mixable_methods;
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
        $this->setMixer($mixer);
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
        $this->getMixer()->$key = $value;
    }

    /**
     * Overloaded get function
     *
     * @param  string $key The variable name.
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getMixer()->$key;
    }

    /**
     * Overloaded isset function
     *
     * Allows testing with empty() and isset() functions
     *
     * @param  string  $key The variable name
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->getMixer()->$key);
    }

    /**
     * Overloaded isset function
     *
     * Allows unset() on object properties to work
     *
     * @param string    $key The variable name.
     * @return void
     */
    public function __unset($key)
    {
        if (isset($this->getMixer()->$key)) {
            unset($this->getMixer()->$key);
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
        $mixer = $this->getMixer();

        //Make sure we don't end up in a recursive loop
        if (isset($mixer) && !($mixer instanceof $this))
        {
            // Call_user_func_array is ~3 times slower than direct method calls.
            switch (count($arguments))
            {
                case 0 : $result = $mixer->$method(); break;
                case 1 : $result = $mixer->$method($arguments[0]); break;
                case 2 : $result = $mixer->$method($arguments[0], $arguments[1]); break;
                case 3 : $result = $mixer->$method($arguments[0], $arguments[1], $arguments[2]); break;
                default:
                    // Resort to using call_user_func_array for many segments
                    $result = call_user_func_array(array($mixer, $method), $arguments);
            }

            return $result;
        }

        throw new \BadMethodCallException('Call to undefined method :' . $method);
    }
}