<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Object
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * ArrayObject
 *
 * Allows objects to be handled as arrays, and at the same time implement the features of KObject
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Object
 */
class KObjectArray extends KObject implements IteratorAggregate, ArrayAccess, Serializable, Countable
{
	/**
     * Array object
     *
     * @var ArrayObject
     */
    private $__array;

    public function __construct(array $options = array())
    {
    	$options  = $this->_initialize($options);
    	
    	$this->__array = new ArrayObject((array)$options['data']);
    }
    
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(array $options)
    {
    	$defaults = array(
            'data'  => array(),
        );

        return array_merge($defaults, $options);
    }
    
	/**
     * Rewind the Iterator to the first element.
     *
     * Similar to the reset() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return KObjectArray
     */
    public function rewind()
    {
        reset($this->__array);
        return $this;
    }

	/**
     * Return the current element.
     *
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     * @return Current element from the collection
     */
    public function current()
    {
        return current($this->__array);
    }

	/**
     * Return the identifying key of the current element.
     *
     * Similar to the key() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return int
     */
    public function key()
    {
    	return key($this->__array);
    }

	/**
     * Move forward to next element.
     *
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return 	KObjectArray
     */
    public function next()
    {
    	next($this->__array);
    	return $this;
    }

	/**
     * Check if there is a current element after calls to rewind() or next().
     *
     * Used to check if we've iterated to the end of the collection.
     * Required by interface Iterator.
     *
     * @return bool False if there's nothing more to iterate over
     */
    public function valid()
    {
        return (bool) current($this->__array);
    }

	/**
     * Returns the number of elements in the collection.
     *
     * Implements Countable::count()
     *
     * @return int
     */
    public function count()
    {
        return count($this->__array);
    }

    /**
     * Check if the offset exists
     *
     * Required by interface ArrayAccess
     *
     * @param 	int 	The offset
     * @return  bool
     */
	public function offsetExists($offset)
	{
        return $this->__array->offsetExists($offset);
	}

    /**
     * Get an item from the array by offset
     *
     * Required by interface ArrayAccess
     *
     * @param 	int 	The offset
     * @return  mixed	The item from the array
     */
	public function offsetGet($offset)
	{
        return $this->__array->offsetGet($offset);
	}

    /**
     * Set an item in the array
     *
     * Required by interface ArrayAccess
     *
     * @param 	int 	The offset of the item
     * @param 	mixed	The item's value
     * @return 	object KObjectArray
     */
	public function offsetSet($offset, $value)
	{
		$this->__array->offsetSet($offset, $value);
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
     * @param 	int 	The offset of the item
     * @return 	object KObjectArray
     */
	public function offsetUnset($offset)
	{
		$this->__array->offsetUnset($offset);
        return $this;
	}
	
	/**
	 * Get a new iterator
	 * 
	 * @return	ArrayIterator
	 */
	public function getIterator()
	{
		return $this->__array->getIterator();
	}
	
	/**
	 * Serialize
	 *
	 * @return	ArrayObject 	A serialized object
	 */
	public function serialize()
	{
		return serialize($this->__array);
	}
	
	/**
	 * Unserialize
	 * 
	 * @param	string			An serialized ArrayObject
	 * @return	ArrayObject 	The unserialized object
	 */
	public function unserialize($serialized)
	{
		return unserialize($this->__array);
	}

	/**
	 * Get the array
	 *
	 * @return 	array
	 */
	public function getArray()
	{
		return $this->__array->getArrayCopy();
	}

	/**
	 * Set the array
	 *
	 * @param 	array 	$array
	 * @return 	object KObjectArray
	 */
	public function setArray($array)
	{
		$this->__array->exchangeArray($array);
		return $this;
	}
	
    /**
     * Extracts a column from the array
     *
     * @param 	string	Column name
     * @return  array   Column of values from the source array
     */
	public function getColumn($column)
	{
		$result = new KObjectArray();

        foreach($this as $key => $elem)
        {
            if(is_object($elem)) {
                $result[$key] = $elem->$column;
            } else {
                $result[$key] = $elem[$column];
            }
        }

        return $result;
	}
	
	/**
     * Extracts columns from the array
     *
     * @param 	array			List of column names
     * @return  KObjectArray   	Array with the columns
     */
	public function getColumns($columns)
	{
		settype($columns, 'array');
		
		$result = array();
	  	foreach($this as $key => $elem)
        {
        	$result[$key] = array();
        	foreach($columns as $column)
        	{
	        	if(is_object($elem)) {
	                $result[$key][$column] = $elem->$column;
	            } else {
	                $result[$key][$column] = $elem[$column];
	            }
        	}
            
        }

        $arr = new KObjectArray();
		$arr->setArray($result);
        return $arr;
	}
	
	/**
	 * Return an KObjectArray with only unique items. 
	 * 
	 * @return KObjectArray
	 */
	public function unique()
	{
		$tmp = array(); 
		
		// array_unique doesn't work with nested arrays in all php 5.2 versions
		foreach($this as $elem) {
			$tmp[serialize($elem)] = $elem;
		}

		$result = new KObjectArray();
		$result->setArray(array_values($tmp));
		return $result;
	}
}