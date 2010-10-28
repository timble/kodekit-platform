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
 * ObjectArray class
 *
 * Allows objects to be handled as arrays, and at the same time implement the features of KObject
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package		Koowa_Object
 */
class KObjectArray extends KObject implements Iterator, ArrayAccess, Countable, Serializable
{
	/**
     * The data container
     *
     * @var array
     */
    protected $_data;
    
    /**
     * The column names
     *
     * @var array
     */
    protected $_columns = array();

    /**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
    public function __construct(KConfig $config = null)
    {
    	//If no config is passed create it
		if(!isset($config)) $config = new KConfig();
    	
    	parent::__construct($config);
    		
    	$this->_data = $config->data;
    }
    
   /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'data'  => array(),
        ));

        parent::_initialize($config);
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
		return $this->__isset($offset);
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
		return $this->__get($offset);
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
		$this->__set($offset, $value);
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
		$this->__unset($offset);
        return $this;
	}
        
	/**
	 * Get a new iterator
	 * 
	 * @return	ArrayIterator
	 */
	public function getIterator() 
	{
        return new ArrayIterator($this->_data);
    }
    
    /**
	 * Get a list of the columns
	 * 
	 * @return	array
	 */
    public function getColumns()
    {
    	return $this->_columns;
    }
	
	/**
	 * Serialize
	 *
	 * @return	string 	A serialized object
	 */
	public function serialize()
	{
		return serialize($this->_data);
	}
	
	/**
	 * Unserialize
	 * 
	 * @param	string	An serialized data
	 */
	public function unserialize($data)
	{
		$this->data = unserialize($data);
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
    	return count($this->_data);
    }
    
 /**
     * Rewind the Iterator to the first element
     *
     * @return	void
     */
	public function rewind() 
	{
		reset($this->_data); 
	} 
	
	/**
     * Checks if current position is valid
     *
     * @return	boolean
     */
	public function valid() 
	{
		return !is_null(key($this->_data)); 
	} 
	
	/**
     * Return the key of the current element
     *
     * @return	scalar
     */
	public function key() 
	{
		return key($this->_data); 
	} 
	
	/**
     * Return the current element
     *
     * @return	mixed
     */
	public function current() 
	{
		return current($this->_data); 
	} 
	
	/**
     * Move forward to next element
     *
     * @return	void
     */
	public function next() 
	{
		next($this->_data); 
	}

	/**
     * Retrieve an array of column values
     *
     * @param  	string 	The column name.
     * @return 	array 	An array of all the column values
     */
    public function __get($column)
    {
    	$result = array();
    	foreach($this->_data as $key => $item)
        {
            if(is_object($item)) {
                $result[$key] = $item->$column;
            } else {
                $result[$key] = $item[$column];
            }
        }

    	return $result;
    }

    /**
     * Set the value of all the columns
     *
     * @param  	string 	The column name.
     * @param  	mixed  	The value for the property.
     * @return 	void
     */
    public function __set($column, $value)
    {
    	foreach($this->_data as $key => $item)
        {
            if(is_object($item)) {
                $item->$column = $value;
            } else {
                $item[$column] = $value;
            }
        }
        
     	//Add the column
        if(!in_array($column, $this->_columns)) {
        	$this->_columns[] = $column;
        }
   }
   
	/**
     * Test existence of a column
     *
     * @param  string  The column name.
     * @return boolean
     */
    public function __isset($column)
    {
    	return in_array($column, $this->_columns);
    }

    /**
     * Unset a column
     *
     * @param	string  The column key.
     * @return	void
     */
    public function __unset($column)
    {
    	foreach($this->_data as $key => $item)
        {
            if(is_object($item)) {
                unset($item->$column);
            } else {
                unset($item[$column]);
            }
        }

        unset($this->_columns[array_search($column, $this->_columns)]);
    }
}