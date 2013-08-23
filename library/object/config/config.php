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
     * The data container
     *
     * @var array
     */
    protected $_data;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return ObjectConfig
     */
    public function __construct( $config = array() )
    {
        if ($config instanceof ObjectConfig) {
            $data = $config->toArray();
        } else {
            $data = $config;
        }

        $this->_data = array();
        if (is_array($data))
        {
            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    /**
     * Retrieve a configuration item and return $default if there is no element set.
     *
     * @param  string $name
     * @param  mixed  $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $result = $default;
        if(isset($this->_data[$name])) {
            $result = $this->_data[$name];
        }

        return $result;
    }

    /**
     * Set a configuration item
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function set($name, $value)
    {
        if (is_array($value)) {
            $this->_data[$name] = new static($value);
        } else {
            $this->_data[$name] = $value;
        }
    }

    /**
     * Check if a configuration item exists
     *
     * @param  	string 	$name The configuration item name.
     * @return  boolean
     */
    public function has($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * Remove a configuration item
     *
     * @param   string $name The configuration item name.
     * @return  ObjectConfig
     */
    public function remove( $name )
    {
        unset($this->_data[$name]);
        return $this;
    }

	/**
     * Unbox a ObjectConfig object
     *
     * If the data being passed is an instance of ObjectConfig the data will be transformed to an associative array.
     *
     * @param  ObjectConfig|mxied $data
     * @return array|mixed
     */
    public static function unbox($data)
    {
        return ($data instanceof ObjectConfig) ? $data->toArray() : $data;
    }

    /**
     * Append an array or Object recursively
     *
     * Merges the elements of an array or ObjectConfig object recursively so that the values of one are appended.
     *
     * If the input arrays has string keys, then the value for that key will be not overwrite the previous one. Instead
     * the values for these keys are transformed into Objects and merged together, and this is done recursively, so
     * that if one of the values is an associative array itself, the function will merge it with a corresponding entry.
     *
     * If, the input arrays contain numeric keys, the later value will not overwrite the original value, but will be
     * appended. Values in the input array with numeric keys will be renumbered with incrementing keys starting from
     * zero in the result array.
     *
     * @param  ObjectConfig|array $config  A ObjectConfig object or an array of values to be appended
     * @return ObjectConfig
     */
    public function append($config)
    {
        $config = ObjectConfig::unbox($config);

        if(is_array($config))
        {
            foreach($config as $key => $value)
            {
                if(!is_numeric($key))
                {
                    if(array_key_exists($key, $this->_data))
                    {
                        if(!empty($value) && ($this->_data[$key] instanceof ObjectConfig)) {
                            $this->_data[$key] = $this->_data[$key]->append($value);
                        }
                    }
                    else $this->__set($key, $value);
                }
                else
                {
                    if(!in_array($value, $this->_data, true)) {
                        $this->_data[] = $value;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Get a new iterator
     *
     * @return  \ArrayIterator
     */
    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->_data);
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
        return count($this->_data);
    }

    /**
     * Check if the offset exists
     *
     * Required by interface ArrayAccess
     *
     * @param   int  $offset
     * @return  bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * Get an item from the array by offset
     *
     * Required by interface ArrayAccess
     *
     * @param   int   $offset
     * @return  mixed
     */
    public function offsetGet($offset)
    {
        $result = null;
        if(isset($this->_data[$offset]))
        {
            $result = $this->_data[$offset];
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
     * @param   int     $offset
     * @param   mixed   $value
     * @return  ObjectConfig
     */
    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
        return $this;
    }

    /**
     * Unset an item in the array
     *
     * All numerical array keys will be modified to start counting from zero while
     * literal keys won't be touched.
     *
     * Required by interface ArrayAccess
     *
     * @param   int  $offset
     * @return  ObjectConfig
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
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
        $data  = $this->_data;
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
     * Deep clone of this instance to ensure that nested ObjectConfig objects are also cloned.
     *
     * @return void
     */
    public function __clone()
    {
        $array = array();
        foreach ($this->_data as $key => $value)
        {
            if ($value instanceof ObjectConfig || $value instanceof \stdClass) {
                $array[$key] = clone $value;
            } else {
                $array[$key] = $value;
            }
        }

        $this->_data = $array;
    }
}