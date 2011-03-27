<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Object
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Object Queue Class
 * 
 * KObjectQueue is a type of container adaptor implemeneted as a double linked list 
 * and specifically designed such that its first element is always the greatest of 
 * the elements it contains based on the priority of the element.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Object
 * @see 		http://www.php.net/manual/en/class.splpriorityqueue.php
 */
class KObjectQueue extends KObject implements Iterator, Countable
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
     * @return  void
     */
    public function __construct(KConfig $config = null)
    {
         //If no config is passed create it
        if(!isset($config)) $config = new KConfig();
        
        parent::__construct($config);
        
        $this->_object_list   = new ArrayObject();
        $this->_priority_list = new ArrayObject();

    }
    
    /**
     * Inserts an object to the queue.
     * 
     * @param   object      A KObject instance
     * @param   integer     The associated priority
     * @return  KObjectQueue
     */
    public function enqueue( KObjectHandlable $object, $priority)
    {
        if($handle = $object->getHandle()) 
        {
            $this->_object_list->offsetSet($handle, $object);
        
            $this->_priority_list->offsetSet($handle, $priority);
            $this->_priority_list->asort(); 
        }
        
        return $this;
    }
    
    /**
     * Removes an object from the queue
     * 
     * @param   object      A KObject instance
     * @return  KObjectQueue
     */
    public function dequeue( KObjectHandlable $object)
    {
        if($handle = $object->getHandle())
        {
            if($this->_object_list->offsetExists($handle)) 
            {
                $this->_object_list->offsetUnset($handle);
                $this->_priority_list->offsetUnSet($handle); 
            }
        }

        return $this;
    }
     
    /**
     * Set the priority of an object in the queue
     * 
     * @param object    A KCommand object 
     * @param integer   The priority
     * @return KCommandChain
     */
    public function setPriority(KObjectHandlable $object, $priority)
    {
        if($handle = $object->getHandle())
        {
            if($this->_priority->offsetExists($handle)) {
                $this->_priority->offsetSet($handle, $priority);
            }
        }
        
        return $this;
    }
    
    /**
     * Get the priority of an object in the queue
     * 
     * @param object   A KObject instance
     * @return  integer The command priority
     */
    public function getPriority(KObjectHandlable $object)
    {
        if($handle = $object->getHandle())
        {
            $result = null;
            if($this->_priority->offsetExist($handle)) {
                $result = $this->_priority->offsetGet($handle);
            }
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
     * @return	object KObjectQueue
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
     * @return	boolean
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
     * @return	scalar
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
     * @return	mixed
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
     * @return	void
     */
	public function next() 
	{
		return next($this->_priority_list); 
	}
	
	/**
     * Return the object from the top of the queue
     *
     * @return	KObject or NULL is queue is empty
     */
	public function top() 
	{
	    $handles = array_values($this->_priority_list);
	    
	    $object = null;
	    if(isset($handle[0])) {
	        $object  = $this->_object_list[$handles[0]];
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
}