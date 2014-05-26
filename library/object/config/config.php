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
 * Object Config
 *
 * ObjectConfig provides a property based interface to an array
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
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
     * @param   array|ObjectConfig $options An associative array of configuration options or a ObjectConfig instance.
     */
    public function __construct( $options = array() )
    {
        $this->add($options);
    }

    /**
     * Retrieve a configuration option
     *
     * If the option does not exist return the default
     *
     * @param string
     * @param mixed
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $result = $default;
        if(isset($this->__options[$name])) {
            $result = $this->__options[$name];
        }

        return $result;
    }

    /**
     * Set a configuration option
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function set($name, $value)
    {
        if (is_array($value))
        {
            $class = get_class($this);
            $this->__options[$name] = new $class($value);
        }
        else $this->__options[$name] = $value;
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
     * @param  array|ObjectConfig  $options A ObjectConfig object an or array of options to be appended
     * @return ObjectConfig
     */
    public function add($options)
    {
        $options = self::unbox($options);

        if (is_array($options))
        {
            foreach ($options as $key => $value) {
                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * Append values
     *
     * This method only adds keys that don't exist and it filters out any duplicate values
     *
     * @param  array|ObjectConfig    $config A ObjectConfig object an or array of options to be appended
     * @return ObjectConfig
     */
    public function append($options)
    {
        $options = self::unbox($options);

        if(is_array($options))
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
                    else $this->__set($key, $value);
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
     * Returns the number of elements in the collection.
     *
     * Required by the Countable interface
     *
     * @return int
     */
    public function count()
    {
        return count($this->__options);
    }

    /**
     * Check if the offset exists
     *
     * Required by interface ArrayAccess
     *
     * @param   int  $offset   The offset
     * @return  bool
     */
    public function offsetExists($offset)
    {
        return isset($this->__options[$offset]);
    }

    /**
     * Get an item from the array by offset
     *
     * Required by interface ArrayAccess
     *
     * @param   int  $offset   The offset
     * @return  mixed   The item from the array
     */
    public function offsetGet($offset)
    {
        $result = null;
        if(isset($this->__options[$offset]))
        {
            $result = $this->__options[$offset];
            if($result instanceof ObjectConfig) {
                $result = $result->toArray();
            }
        }

        return $result;
    }

    /**
     * Set an item in the array
     *
     * Required by interface ArrayAccess
     *
     * @param   int    $offset   The offset
     * @param   mixed  $value    The item's value
     *
     * @return  ObjectConfig
     */
    public function offsetSet($offset, $value)
    {
        $this->__options[$offset] = $value;
        return $this;
    }

    /**
     * Unset an item in the array
     *
     * All numerical array keys will be modified to start counting from zero while literal keys won't be touched.
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset The offset of the item
     * @return  ObjectConfig
     */
    public function offsetUnset($offset)
    {
        unset($this->__options[$offset]);
        return $this;
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
    public function __get($name)
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
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Test existence of a configuration element
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * Unset a configuration element
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        $this->remove($name);
    }

    /**
     * Deep clone of this instance to ensure that nested KObjectConfigs
     * are also cloned.
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