<?php
/**
 * @package     Koowa_Object
 * @subpackage  Decorator
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Decorator Iterator
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Decorator
 */
class ObjectDecoratorIterator extends ObjectDecorator implements  \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Check if the offset exists
     *
     * Required by \ArrayAccess interface
     *
     * @param   int   $offset
     * @return  bool
     */
    public function offsetExists($offset)
    {
        return $this->getDelegate()->offsetExists($offset);
    }

    /**
     * Get an item from the array by offset
     *
     * Required by \ArrayAccess interface
     *
     * @param   int     $offset
     * @return  mixed The item from the array
     */
    public function offsetGet($offset)
    {
        return $this->getDelegate()->offsetGet($offset);
    }

    /**
     * Set an item in the array
     *
     * Required by \ArrayAccess interface
     *
     * @param   int     $offset
     * @param   mixed   $value
     * @return  void
     */
    public function offsetSet($offset, $value)
    {
        $this->getDelegate()->offsetSet($offset, $value);
    }

    /**
     * Unset an item in the array
     *
     * Required by \ArrayAccess interface
     *
     * @param   int     $offset
     * @return  void
     */
    public function offsetUnset($offset)
    {
        $this->getDelegate()->offsetUnset($offset);
    }

    /**
     * Get a new iterator
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return  $this->getDelegate()->getIterator();
    }

    /**
     * Serialize
     *
     * Required by \Serializable interface
     *
     * @return  string
     */
    public function serialize()
    {
        return  $this->getDelegate()->serialize();
    }

    /**
     * Unserialize
     *
     * Required by \Serializable interface
     *
     * @param   string  $data
     */
    public function unserialize($data)
    {
        return  $this->getDelegate()->serialize($data);
    }

    /**
     * Returns the number of items
     *
     * Required by \Countable interface
     *
     * @return int The number of items
     */
    public function count()
    {
        return  $this->getDelegate()->count();
    }
}