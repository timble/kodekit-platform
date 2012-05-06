<?php
/**
 * @version     $Id$
 * @category    Koowa
 * @package     Koowa_Template
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Object Stack Class
 *
 * Implements a simple stack collection (LIFO)
 *
 * @author     Johan Janssens <johan@nooku.org>
 * @category   Koowa
 * @package    Koowa_Object
 */
class KObjectStack extends KObject implements Countable
{
    /**
     * The object container
     *
     * @var array
     */
    protected $_object_stack = null;

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the contructor private
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_object_stack = array();
    }

    /**
     * Peeks at the element from the end of the stack
     *
     * @param mixed The value of the top element
     */
    public function top()
    {
        return end($this->_object_stack);
    }

    /**
     * Pushes an element at the end of the stack
     *
     * @param mixed  The object
     * @return KObjectStack
     */
    public function push(KObject $object)
    {
        $this->_object_stack[] = $object;
        return $this;
    }

    /**
     * Pops an element from the end of the stack
     *
     * @return  mixed The value of the popped element
     */
    public function pop()
    {
        return array_pop($this->_object_stack);
    }

    /**
     * Counts the number of elements
     *
     * @return integer  The number of elements
     */
    public function count()
    {
        return count($this->_object_stack);
    }

    /**
     * Check to see if the registry is empty
     *
     * @return boolean  Return TRUE if the registry is empty, otherwise FALSE
     */
    public function isEmpty()
    {
        return empty($this->_object_stack);
    }
}