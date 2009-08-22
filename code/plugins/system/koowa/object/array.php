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
 * Object Array class
 *
 * Allows objects to be handled as arrays
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Object
 */
class KObjectArray extends KObject implements ArrayAccess, SeekableIterator, Countable
{
	/**
     * Array data
     *
     * @var array
     */
    private $__data = array();

    /**
     * Array count
     *
     * @var int
     */
    private $__count = 0;

	/**
     * Iterator pointer
     *
     * @var integer
     */
    private $__pointer = 0;

	/**
     * Rewind the Iterator to the first element.
     *
     * Similar to the reset() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return this
     */
    public function rewind()
    {
        $this->setKey(0);
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
    	if ($this->valid() === false) {
            return null;
        }

        return $this[$this->key()];
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
    	return $this->__pointer;
    }

	/**
	 * Set the identifying key of the current element.
	 *
	 * @param 	int	Pointer
	 * @return 	object KObjectArray
	 */
	public function setKey($pointer)
	{
		$this->__pointer = $pointer;
		return $this;
	}

	/**
     * Move forward to next element.
     *
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return 	object KObjectArray
     */
    public function next()
    {
    	++$this->__pointer;
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
        return $this->key() < $this->count();
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
        return $this->__count;
    }

    /**
     * Set the count of the array
     *
     * @param 	int	Count
     * @return 	object KObjectArray
     */
    public function setCount($count)
    {
    	$this->__count = $count;
    	return $this;
    }

	/**
     * Reset the count of the array
     *
     * @return 	object KObjectArray
     */
    public function resetCount()
    {
    	$this->__count	= count($this->__data);
    	return $this;
    }

	/**
     * Take the Iterator to position $position
     *
     * Required by interface SeekableIterator.
     *
     * @param 	int $position The position to seek to
     * @throws 	KObjectException
     * @return 	object KObjectArray
     */
    public function seek($position)
    {
        settype($position, 'int');
        if ($position < 0 || $position > $this->count()) {
            throw new KObjectException("Illegal index $position");
        }
        $this->__pointer = $position;
        return $this;
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
        return isset($this->__data[$offset]);
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
        return $this->__data[$offset];
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
		if(empty($offset)) {
			$this->__data[] = $value;
		} else {
			$this->__data[$offset] = $value;
		}

		$this->resetCount();
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
		//We need to use array_splice instead of unset to reset the keys
		array_splice($this->__data, $offset, 1);
		$this->resetCount();
        return $this;
	}

	/**
	 * Get the array
	 *
	 * @return 	array
	 */
	public function getArray()
	{
		return $this->__data;
	}

	/**
	 * Set the array
	 *
	 * @param 	array 	$array
	 * @return 	object KObjectArray
	 */
	public function setArray($array)
	{
		$this->__data 	= $array;
		$this->resetCount();
		return $this;
	}
}