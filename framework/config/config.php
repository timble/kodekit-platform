<?php
/**
 * @package		Koowa_Config
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Config Class
 *
 * Config provides a property based interface to an array
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Config
 */
class Config implements ConfigInterface
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
     * @param Config|null $config  An optional Config object with configuration options
     * @return \Config
     */
    public function __construct( $config = array() )
    {
        if ($config instanceof Config) {
            $data = $config->toArray();
        } else {
            $data = $config;
        }

        $this->_data = array();
        if (is_array($data))
        {
            foreach ($data as $key => $value) {
                $this->__set($key, $value);
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
            $this->_data[$name] = new self($value);
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
     * @return  ModelStateInterface
     */
    public function remove( $name )
    {
        unset($this->_data[$name]);
    }

	/**
     * Unbox a Config object
     *
     * If the data being passed is an instance of Config the data will be transformed to an associative array.
     *
     * @param  Config|mxied $data
     * @return array|mixed
     */
    public static function unbox($data)
    {
        return ($data instanceof Config) ? $data->toArray() : $data;
    }

    /**
     * Append an array or Object recursively
     *
     * Merges the elements of an array or Config object recursively so that the values of one are appended.
     *
     * If the input arrays has string keys, then the value for that key will be not overwrite the previous one. Instead
     * the values for these keys are transformed into Objects and merged together, and this is done recursively, so
     * that if one of the values is an associative array itself, the function will merge it with a corresponding entry.
     *
     * If, the input arrays contain numeric keys, the later value will not overwrite the original value, but will be
     * appended. Values in the input array with numeric keys will be renumbered with incrementing keys starting from
     * zero in the result array.
     *
     * @param  Config|array $config  A Config object or an array of values to be appended
     * @return \Config
     */
    public function append($config)
    {
        $config = Config::unbox($config);

        if(is_array($config))
        {
            if(!is_numeric(key($config)))
            {
                foreach($config as $key => $value)
                {
                    if(array_key_exists($key, $this->_data))
                    {
                        if(!empty($value) && ($this->_data[$key] instanceof Config)) {
                            $this->_data[$key] = $this->_data[$key]->append($value);
                        }
                    }
                    else $this->__set($key, $value);
                }
            }
            else
            {
                foreach($config as $value)
                {
                    if (!in_array($value, $this->_data, true)) {
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
     * @return  ArrayIterator
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
            if($result instanceof Config) {
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
     * @return  Config
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
     * @return  Config
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
            if ($value instanceof Config) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

 	/**
     * Returns a string with the encapsulated data in JSON format
     *
     * @return string
     */
    public function toString()
    {
        return json_encode($this->toArray());
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
     * Deep clone of this instance to ensure that nested Config objects are also cloned.
     *
     * @return void
     */
    public function __clone()
    {
        $array = array();
        foreach ($this->_data as $key => $value)
        {
            if ($value instanceof Config || $value instanceof \stdClass) {
                $array[$key] = clone $value;
            } else {
                $array[$key] = $value;
            }
        }

        $this->_data = $array;
    }

    /**
     * Returns a string with the encapsulated data in JSON format
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}