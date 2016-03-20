<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Object Config
 *
 * ObjectConfig provides a property based interface to an array.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Object
 */
class ObjectConfig implements ObjectConfigInterface
{
    /**
     * The configuration options
     *
     * @var array
     */
    private $__options = array();

    /**
     * Constructor.
     *
     * @param  array|ObjectConfig $options An associative array of configuration options or a ObjectConfig instance.
     */
    public function __construct( $options = array())
    {
        $this->merge($options);
    }

    /**
     * Get a new instance
     *
     * @return ObjectConfigInterface
     */
    public static function getInstance()
    {
        $instance = new static(array());
        return $instance;
    }

    /**
     * Retrieve a configuration option
     *
     * If the option does not exist return the default.
     *
     * @param string  $name
     * @param mixed   $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $result = $default;
        if(isset($this->__options[$name]) || array_key_exists($name, $this->__options)) {
            $result = $this->__options[$name];
        }

        return $result;
    }

    /**
     * Set a configuration option
     *
     * @param  string $name
     * @param  mixed  $value
     * @throws \RuntimeException If the config is read only
     * @return ObjectConfig
     */
    public function set($name, $value)
    {
        if (is_array($value)) {
            $this->__options[$name] = $this->getInstance()->merge($value);
        } else {
            $this->__options[$name] = $value;
        }

        return $this;
    }

    /**
     * Check if a configuration option exists
     *
     * @param  	string 	$name The configuration option name.
     * @return  boolean
     */
    public function has($name)
    {
        return isset($this->__options[$name]);
    }

    /**
     * Remove a configuration option
     *
     * @param   string $name The configuration option name.
     * @throws \RuntimeException If the config is read only
     * @return  ObjectConfig
     */
    public function remove( $name )
    {
        unset($this->__options[$name]);

        return $this;
    }

    /**
     * Add options
     *
     * This method will overwrite keys that already exist, keys that don't exist yet will be added.
     *
     * For duplicate keys, the following will be performed:
     *
     * - Nested configs will be recursively merged.
     * - Items in $options with INTEGER keys will be appended.
     * - Items in $options with STRING keys will overwrite current values.
     *
     * @param  array|\Traversable|ObjectConfig  $options A ObjectConfig object an or array of options to be appended
     * @throws \RuntimeException If the config is read only
     * @return ObjectConfig
     */
    public function merge($options)
    {
        $options = self::unbox($options);

        if (is_array($options) || $options instanceof \Traversable)
        {
            foreach ($options as $key => $value) {
                $this->set($key, $value);
            }
        }
        else throw new \RuntimeException('Config is read only');

        return $this;
    }

    /**
     * Append values
     *
     * This method only adds keys that don't exist and it filters out any duplicate values
     *
     * @param  array|\Traversable|ObjectConfig    $config A ObjectConfig object an or array of options to be appended
     * @throws \RuntimeException If the config is read only
     * @return ObjectConfig
     */
    public function append($options)
    {
        $options = self::unbox($options);

        if(is_array($options) || $options instanceof \Traversable)
        {
            if(!is_numeric(key($options)))
            {
                foreach($options as $key => $value)
                {
                    if(array_key_exists($key, $this->__options))
                    {
                        if(!empty($value) && ($this->__options[$key] instanceof ObjectConfig)) {
                            $this->__options[$key] = $this->__options[$key]->append($value);
                        }
                    }
                    else $this->set($key, $value);
                }
            }
            else
            {
                foreach($options as $value)
                {
                    if (!in_array($value, $this->__options, true)) {
                        $this->__options[] = $value;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Returns the number of elements in the collection.
     *
     * Required by the Countable interface
     *
     * @param int $mode Either COUNT_NORMAL or COUNT_RECURSIVE. Default is COUNT_NORMAL
     * @return int
     */
    public function count($mode = COUNT_NORMAL)
    {
        if($mode == COUNT_RECURSIVE)
        {
            $count = 0;
            $data  = $this->__options;
            foreach ($data as $key => $value)
            {
                if(is_array($value) || $value instanceof ObjectConfig)
                {
                    if ($value instanceof ObjectConfig) {
                        $count += $value->count($mode);
                    } else {
                        $count += count($value);
                    }
                }
            }
        }
        else $count = count($this->__options);

        return $count;
    }

    /**
     * Return the data
     *
     * If the data being passed is an instance of ObjectConfig the data will be transformed to an associative array.
     *
     * @param mixed ObjectConfig $data
     * @return mixed|array
     */
    public static function unbox($data)
    {
        return ($data instanceof ObjectConfig) ? $data->toArray() : $data;
    }

    /**
     * Get a new iterator
     *
     * @return  \RecursiveArrayIterator
     */
    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->__options);
    }

    /**
     * Check if the offset exists
     *
     * Required by interface ArrayAccess
     *
     * @param   int  $offset   The offset
     * @return  bool
     */
    final public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Get an item from the array by offset
     *
     * Required by interface ArrayAccess
     *
     * @param   int  $offset   The offset
     * @return  mixed   The item from the array
     */
    final public function offsetGet($offset)
    {
        return self::unbox($this->get($offset));
    }

    /**
     * Set an item in the array
     *
     * Required by interface ArrayAccess
     *
     * @param   int    $offset   The offset
     * @param   mixed  $value    The item's value
     * @return  void
     */
    final public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Unset an item in the array
     *
     * All numerical array keys will be modified to start counting from zero while literal keys won't be touched.
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset The offset of the item
     * @return  void
     */
    final public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * Return an associative array of the config data.
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        $data  = $this->__options;
        foreach ($data as $key => $value)
        {
            if ($value instanceof ObjectConfig) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Return a ObjectConfig object from an array
     *
     * @param  array $array
     * @return ObjectConfig Returns a ObjectConfig object
     */
    public static function fromArray(array $array)
    {
        return new static($array);
    }

    /**
     * Retrieve a configuration element
     *
     * @param string $name
     * @return mixed
     */
    final public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Set a configuration element
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    final public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Test existence of a configuration element
     *
     * @param string $name
     * @return bool
     */
    final public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * Unset a configuration element
     *
     * @param  string $name
     * @return void
     */
    final public function __unset($name)
    {
        $this->remove($name);
    }

    /**
     * Deep clone of this instance to ensure that nested ObjectConfigs are also cloned.
     *
     * @return void
     */
    public function __clone()
    {
        $array = array();
        foreach ($this->__options as $key => $value)
        {
            if ($value instanceof ObjectConfig || $value instanceof \stdClass) {
                $array[$key] = clone $value;
            } else {
                $array[$key] = $value;
            }
        }

        $this->__options = $array;
    }
}