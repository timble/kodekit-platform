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
  * Object Stack
  *
  * A stack is a data type or collection in which the principal (or only) operations on the collection are the addition
  * of an object to the collection, known as push and removal of an entity, known as pop. The relation between the push
  * and pop operations is such that the stack is a Last-In-First-Out (LIFO) data structure.
  *
  * @link http://en.wikipedia.org/wiki/Stack_(abstract_data_type)
  *
  * @author  Johan Janssens <http://github.com/johanjanssens>
  * @package Nooku\Library\Object
  */
class ObjectStack extends Object implements \Iterator, \Countable, \Serializable
{ 
    /**
     * The object container
     *
     * @var array
     */
    private $__object_stack = array();
    
    /**
     * Peeks at the element from the end of the stack
     *
     * @return mixed The value of the top element
     */
    public function peek()
    {
        return end($this->__object_stack);
    }
      
    /**
     * Pushes an element at the end of the stack
     *
     * @param  Object $object
     * @throws \InvalidArgumentException if the object doesn't extend from Object
     * @return ObjectStack
     */
    public function push($object)
    {
        $this->__object_stack[] = $object;
        return $this;
    }
    
    /**
     * Pops an element from the end of the stack
     *
     * @return  mixed The value of the popped element
     */
    public function pop()
    {
        return array_pop($this->__object_stack);
    }

    /**
     * Counts the number of elements
     *
     * Required by the Countable interface
     *
     * @return integer	The number of elements
     */
    public function count()
    {
        return count($this->__object_stack);
    }

    /**
     * Rewind the Iterator to the top
     *
     * Required by the Iterator interface
     *
     * @return	object KObjectQueue
     */
    public function rewind()
    {
        reset($this->__object_stack);
        return $this;
    }

    /**
     * Check whether the stack contains more objects
     *
     * Required by the Iterator interface
     *
     * @return	boolean
     */
    public function valid()
    {
        return !is_null(key($this->__object_stack));
    }

    /**
     * Return current object index
     *
     * Required by the Iterator interface
     *
     * @return	mixed
     */
    public function key()
    {
        return key($this->__object_stack);
    }

    /**
     * Return current object pointed by the iterator
     *
     * Required by the Iterator interface
     *
     * @return	mixed
     */
    public function current()
    {
        return $this->__object_stack[$this->key()];
    }

    /**
     * Move to the next object
     *
     * Required by the Iterator interface
     *
     * @return	mixed
     */
    public function next()
    {
        return next($this->__object_stack);
    }

    /**
     * Serialize
     *
     * Required by the Serializable interface
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * Unserialize
     *
     * Required by the Serializable interface
     *
     * @param  string $data
     * @return void
     */
    public function unserialize($data)
    {
        $data = unserialize($data);

        if(is_array($data))
        {
            $data = array_reverse($data);

            foreach ($data as $item) {
                $this->push($item);
            }
        }
    }

    /**
     * Serialize to an array representing the stack
     *
     * @return array
     */
    public function toArray()
    {
        return $this->__object_stack;
    }

    /**
     * Check to see if the registry is empty
     * 
     * @return boolean	Return TRUE if the registry is empty, otherwise FALSE
     */
    public function isEmpty()
    {
        return empty($this->__object_stack);
    }  
}