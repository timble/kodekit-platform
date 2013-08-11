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
 * Object Queue
 *
 * ObjectQueue is a type of container adaptor implemented as a double linked list and specifically designed such that
 * its first element is always the greatest of the elements it contains based on the priority of the element.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 * @see     http://www.php.net/manual/en/class.splpriorityqueue.php
 */
class ObjectQueue extends Object implements \Iterator, \Countable
{
    /**
     * Object list
     *
     * @var array
     */
    protected $_object_list = null;

    /**
     * Priority list
     *
     * @var array
     */
    protected $_priority_list = null;

    /**
     * Constructor
     *
     * @param ObjectConfig $config  A ObjectConfig object with configuration options
     * @return ObjectQueue
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_object_list   = new \ArrayObject();
        $this->_priority_list = new \ArrayObject();

    }

    /**
     * Inserts an object to the queue.
     *
     * @param   ObjectHandlable  $object
     * @param   integer           $priority
     * @return  boolean        TRUE on success FALSE on failure
     */
    public function enqueue(ObjectHandlable $object, $priority)
    {
        $result = false;

        if ($handle = $object->getHandle())
        {
            $this->_object_list->offsetSet($handle, $object);

            $this->_priority_list->offsetSet($handle, $priority);
            $this->_priority_list->asort();

            $result = true;
        }

        return $result;
    }

    /**
     * Removes an object from the queue
     *
     * @param   ObjectHandlable $object
     * @return  boolean    TRUE on success FALSE on failure
     */
    public function dequeue(ObjectHandlable $object)
    {
        $result = false;

        if ($handle = $object->getHandle())
        {
            if ($this->_object_list->offsetExists($handle))
            {
                $this->_object_list->offsetUnset($handle);
                $this->_priority_list->offsetUnSet($handle);

                $result = true;
            }
        }

        return $result;
    }

    /**
     * Set the priority of an object in the queue
     *
     * @param   ObjectHandlable  $object
     * @param   integer           $priority
     * @return  CommandChain
     */
    public function setPriority(ObjectHandlable $object, $priority)
    {
        if ($handle = $object->getHandle())
        {
            if ($this->_priority_list->offsetExists($handle))
            {
                $this->_priority_list->offsetSet($handle, $priority);
                $this->_priority_list->asort();
            }
        }

        return $this;
    }

    /**
     * Get the priority of an object in the queue
     *
     * @param   ObjectHandlable $object
     * @return  integer|false The command priority or FALSE if the commnand isn't enqueued
     */
    public function getPriority(ObjectHandlable $object)
    {
        $result = false;

        if ($handle = $object->getHandle())
        {
            if ($this->_priority_list->offsetExists($handle)) {
                $result = $this->_priority_list->offsetGet($handle);
            }
        }

        return $result;
    }

    /**
     * Check if the queue has an item with the given priority
     *
     * @param  integer  $priority   The priority to search for
     * @return boolean
     */
    public function hasPriority($priority)
    {
        $result = array_search($priority, $this->_priority_list);
        return $result;
    }

    /**
     * Check if the queue does contain a given object
     *
     * @param  ObjectHandlable $object
     * @return bool
     */
    public function contains(ObjectHandlable $object)
    {
        $result = false;

        if ($handle = $object->getHandle()) {
            $result = $this->_object_list->offsetExists($handle);
        }

        return $result;
    }

    /**
     * Returns the number of elements in the queue
     *
     * Required by the Countable interface
     *
     * @return int
     */
    public function count()
    {
        return count($this->_object_list);
    }

    /**
     * Rewind the Iterator to the top
     *
     * Required by the Iterator interface
     *
     * @return ObjectQueue
     */
    public function rewind()
    {
        reset($this->_object_list);
        reset($this->_priority_list);

        return $this;
    }

    /**
     * Check whether the queue contains more object
     *
     * Required by the Iterator interface
     *
     * @return  boolean
     */
    public function valid()
    {
        return !is_null(key($this->_priority_list));
    }

    /**
     * Return current object index
     *
     * Required by the Iterator interface
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->_priority_list);
    }

    /**
     * Return current object pointed by the iterator
     *
     * Required by the Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_object_list[$this->key()];
    }

    /**
     * Move to the next object
     *
     * Required by the Iterator interface
     *
     * @return void
     */
    public function next()
    {
        next($this->_priority_list);
    }

    /**
     * Return the object from the top of the queue
     *
     * @return  Object or NULL is queue is empty
     */
    public function top()
    {
        $handles = array_keys((array)$this->_priority_list);

        $object = null;
        if (isset($handles[0])) {
            $object = $this->_object_list[$handles[0]];
        }

        return $object;
    }

    /**
     * Checks whether the queue is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return !count($this->_object_list);
    }

    /**
     * Preform a deep clone of the object
     *
     * @return void
     */
    public function __clone()
    {
        parent::__clone();

        $this->_object_list   = clone $this->_object_list;
        $this->_priority_list = clone $this->_priority_list;
    }
}