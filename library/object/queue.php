<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Queue
 *
 * A queue a data type or collection in which the entities in the collection are kept in order and the principal
 * (or only) operations on the collection are the addition of entities to the rear terminal position, known as
 * enqueue, and removal of entities from the front terminal position, known as dequeue. This makes the queue a
 * First-In-First-Out (FIFO) data structure.
 *
 * Additionally each element can have a "priority" associated with it prioritising the order of the element in the
 * queue. An element with high priority is served before an element with low priority. If two elements have the same
 * priority, they are served according to their order in the queue.
 *
 * @link http://en.wikipedia.org/wiki/Queue_(abstract_data_type)
 * @link http://en.wikipedia.org/wiki/Priority_queue
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
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
    private $__object_list = null;

    /**
     * Priority list
     *
     * @var array
     */
    private $__priority_list = null;

    /**
     * Identifier list
     *
     * @var array
     */
    private $__identifier_list = array();

    /**
     * Constructor
     *
     * @param ObjectConfig $config  A ObjectConfig object with configuration options
     * @return ObjectQueue
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->__object_list   = new \ArrayObject();
        $this->__priority_list = new \ArrayObject();

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
            $this->__object_list->offsetSet($handle, $object);

            $this->__priority_list->offsetSet($handle, $priority);
            $this->__priority_list->asort();

            if($object instanceof ObjectInterface) {
                $this->__identifier_list[$handle] = $object->getIdentifier();
            }

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
            if ($this->__object_list->offsetExists($handle))
            {
                $this->__object_list->offsetUnset($handle);
                $this->__priority_list->offsetUnSet($handle);

                if($object instanceof ObjectInterface) {
                    unset($this->__identifier_list[$handle]);
                }

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
            if ($this->__priority_list->offsetExists($handle))
            {
                $this->__priority_list->offsetSet($handle, $priority);
                $this->__priority_list->asort();
            }
        }

        return $this;
    }

    /**
     * Get the priority of an object in the queue
     *
     * @param   ObjectHandlable $object
     * @return  integer|false The command priority or FALSE if the command isn't enqueued
     */
    public function getPriority(ObjectHandlable $object)
    {
        $result = false;

        if ($handle = $object->getHandle())
        {
            if ($this->__priority_list->offsetExists($handle)) {
                $result = $this->__priority_list->offsetGet($handle);
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
        $result = array_search($priority, $this->__priority_list);
        return $result;
    }

    /**
     * Check if the queue has an item with the given identifier
     *
     * @param  mixed $identifier An KObjectIdentifier, identifier string or object implementing KObjectInterface
     * @return boolean
     */
    public function hasIdentifier($identifier)
    {
        if(!$identifier instanceof ObjectIdentifierInterface) {
            $identifier = $this->getIdentifier($identifier);
        }

        return in_array((string) $identifier, $this->__identifier_list);
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
            $result = $this->__object_list->offsetExists($handle);
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
        return count($this->__object_list);
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
        reset($this->__object_list);
        reset($this->__priority_list);

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
        return !is_null(key($this->__priority_list));
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
        return key($this->__priority_list);
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
        return $this->__object_list[$this->key()];
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
        next($this->__priority_list);
    }

    /**
     * Return the object from the top of the queue
     *
     * @return  Object or NULL is queue is empty
     */
    public function top()
    {
        $handles = array_keys((array)$this->__priority_list);

        $object = null;
        if (isset($handles[0])) {
            $object = $this->__object_list[$handles[0]];
        }

        return $object;
    }

    /**
     * Return an array representing the queue
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this as $item) {
            $array[] = $item;
        }

        return $array;
    }

    /**
     * Checks whether the queue is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return !count($this->__object_list);
    }

    /**
     * Preform a deep clone of the object
     *
     * @return void
     */
    public function __clone()
    {
        parent::__clone();

        $this->__object_list   = clone $this->__object_list;
        $this->__priority_list = clone $this->__priority_list;
    }
}