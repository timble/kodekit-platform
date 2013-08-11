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
 * Object Set
 *
 * ObjectSet implements an associative container that stores objects, and in which the object themselves are the keys.
 * Objects are stored in the set in FIFO order.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 * @see     http://www.php.net/manual/en/class.splobjectstorage.php
 */
class ObjectSet extends Object implements \IteratorAggregate, \ArrayAccess, \Countable, \Serializable
{
    /**
     * Object set
     *
     * @var array
     */
    protected $_object_set = null;

    /**
     * Constructor
     *
     * @param ObjectConfig $config  A ObjectConfig object with configuration options
     * @return ObjectSet
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_object_set = new \ArrayObject();
    }

    /**
     * Inserts an object in the set
     *
     * @param   ObjectHandlable $object
     * @return  boolean TRUE on success FALSE on failure
     */
    public function insert(ObjectHandlable $object)
    {
        $result = false;

        if ($handle = $object->getHandle())
        {
            $this->_object_set->offsetSet($handle, $object);
            $result = true;
        }

        return $result;
    }

    /**
     * Removes an object from the set
     *
     * All numerical array keys will be modified to start counting from zero while literal keys won't be touched.
     *
     * @param   ObjectHandlable $object
     * @return  ObjectSet
     */
    public function extract(ObjectHandlable $object)
    {
        $handle = $object->getHandle();

        if ($this->_object_set->offsetExists($handle)) {
            $this->_object_set->offsetUnset($handle);
        }

        return $this;
    }

    /**
     * Checks if the set contains a specific object
     *
     * @param   ObjectHandlable $object
     * @return  bool Returns TRUE if the object is in the set, FALSE otherwise
     */
    public function contains(ObjectHandlable $object)
    {
        return $this->_object_set->offsetExists($object->getHandle());
    }

    /**
     * Merge-in another object set
     *
     * @param   ObjectSet  $set
     * @return  ObjectSet
     */
    public function merge(ObjectSet $set)
    {
        foreach ($set as $object) {
            $this->insert($object);
        }

        return $this;
    }

    /**
     * Check if the object exists in the queue
     *
     * Required by interface ArrayAccess
     *
     * @param   ObjectHandlable $object
     * @return  bool Returns TRUE if the object exists in the storage, and FALSE otherwise
     * @throws  \InvalidArgumentException if the object doesn't implement ObjectHandlable
     */
    public function offsetExists($object)
    {
        if (!$object instanceof ObjectHandlable) {
            throw new \InvalidArgumentException('Object needs to implement ObjectHandlable');
        }

        return $this->contains($object);
    }

    /**
     * Returns the object from the set
     *
     * Required by interface ArrayAccess
     *
     * @param   ObjectHandlable $object
     * @return  ObjectHandlable
     * @throws  \InvalidArgumentException if the object doesn't implement ObjectHandlable
     */
    public function offsetGet($object)
    {
        if (!$object instanceof ObjectHandlable) {
            throw new \InvalidArgumentException('Object needs to implement ObjectHandlable');
        }

        return $this->_object_set->offsetGet($object->getHandle());
    }

    /**
     * Store an object in the set
     *
     * Required by interface ArrayAccess
     *
     * @param   ObjectHandlable  $object
     * @param   mixed             $data The data to associate with the object [UNUSED]
     * @return  ObjectSet
     * @throws  \InvalidArgumentException if the object doesn't implement ObjectHandlable
     */
    public function offsetSet($object, $data)
    {
        if (!$object instanceof ObjectHandlable) {
            throw new \InvalidArgumentException('Object needs to implement ObjectHandlable');
        }

        $this->insert($object);
        return $this;
    }

    /**
     * Removes an object from the set
     *
     * Required by interface ArrayAccess
     *
     * @param   ObjectHandlable  $object
     * @return  ObjectSet
     * @throws  InvalidArgumentException if the object doesn't implement the ObjectHandlable interface
     */
    public function offsetUnset($object)
    {
        if (!$object instanceof ObjectHandlable) {
            throw new \InvalidArgumentException('Object needs to implement ObjectHandlable');
        }

        $this->extract($object);
        return $this;
    }

    /**
     * Return a string representation of the set
     *
     * Required by interface \Serializable
     *
     * @return  string  A serialized object
     */
    public function serialize()
    {
        return serialize($this->_object_set);
    }

    /**
     * Unserializes a set from its string representation
     *
     * Required by interface \Serializable
     *
     * @param   string  $serialized The serialized data
     */
    public function unserialize($serialized)
    {
        $this->_object_set = unserialize($serialized);
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
        return $this->_object_set->count();
    }

    /**
     * Return the first object in the set
     *
     * @return ObjectHandlable or NULL is queue is empty
     */
    public function top()
    {
        $objects = array_values($this->_object_set->getArrayCopy());

        $object = null;
        if (isset($objects[0])) {
            $object = $objects[0];
        }

        return $object;
    }

    /**
     * Defined by IteratorAggregate
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->_object_set->getIterator();
    }

    /**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_object_set->getArrayCopy();
    }

    /**
     * Preform a deep clone of the object
     *
     * @retun void
     */
    public function __clone()
    {
        parent::__clone();

        $this->_object_set = clone $this->_object_set;
    }
}