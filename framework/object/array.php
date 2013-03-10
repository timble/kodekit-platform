<?php
/**
 * @package        Koowa_Object
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * An Object Array Class
 *
 * The ObjectArray class provides provides the main functionality of an array and at the same time implement the
 * features of Object
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Object
 */
class ObjectArray extends Object implements \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * The data for each key in the array (key => value).
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Constructor
     *
     * @param Config $config  An optional Config object with configuration options
     * @return ObjectArray
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->_data = Config::unbox($config->data);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Config $object An optional Config object with configuration options
     * @return  void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'data' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Get a value by key
     *
     * @param   string  $key The key name.
     * @return  string  The corresponding value.
     */
    public function get($key)
    {
        $result = null;
        if (isset($this->_data[$key])) {
            $result = $this->_data[$key];
        }

        return $result;
    }

    /**
     * Set a value by key
     *
     * @param   string  $key   The key name
     * @param   mixed   $value The value for the key
     * @return  ObjectArray
     */
    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * Test existence of a key
     *
     * @param  string  $key The key name
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->_data);
    }

    /**
     * Unset a key
     *
     * @param   string  $key The key name
     * @return  ObjectArray
     */
    public function remove($key)
    {
        unset($this->_data[$key]);
        return $this;
    }

    /**
     * Check if the offset exists
     *
     * Required by interface ArrayAccess
     *
     * @param   int   $offset
     * @return  bool
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * Get an item from the array by offset
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset
     * @return  mixed The item from the array
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Set an item in the array
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset
     * @param   mixed   $value
     * @return  ObjectArray
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_data[] = $value;
        } else {
            $this->__set($offset, $value);
        }

        return $this;
    }

    /**
     * Unset an item in the array
     *
     * All numerical array keys will be modified to start counting from zero while literal keys won't be touched.
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset
     * @return  ObjectArray
     */
    public function offsetUnset($offset)
    {
        $this->__unset($offset);
        return $this;
    }

    /**
     * Get a new iterator
     *
     * @return  ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_data);
    }

    /**
     * Serialize
     *
     * Required by interface \Serializable
     *
     * @return  string
     */
    public function serialize()
    {
        return serialize($this->_data);
    }

    /**
     * Unserialize
     *
     * Required by interface \Serializable
     *
     * @param   string  $data
     */
    public function unserialize($data)
    {
        $this->_data = unserialize($data);
    }

    /**
     * Returns the number of items
     *
     * Required by interface Countable
     *
     * @return int The number of items
     */
    public function count()
    {
        return count($this->_data);
    }

    /**
     * Set the data from an array
     *
     * @param array An associative array of data
     * @return ObjectArray
     */
    public function fromArray(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * Return an associative array of the data
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }

    /**
     * Get a value by key
     *
     * @param   string  $key The key name.
     * @return  string  The corresponding value.
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Set a value by key
     *
     * @param   string  $key   The key name
     * @param   mixed   $value The value for the key
     * @return  void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Test existence of a key
     *
     * @param  string  $key The key name
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->has($key) && !is_null($this->_data[$key]);
    }

    /**
     * Unset a key
     *
     * @param   string  $key The key name
     * @return  void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }
}