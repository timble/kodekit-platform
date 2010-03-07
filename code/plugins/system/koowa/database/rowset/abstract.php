<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	(C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Rowset Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @uses 		KMixinClass
 */
abstract class KDatabaseRowsetAbstract extends KObjectArray implements KFactoryIdentifiable
{
	/** 
     * The column names of a row in the rowset
     *
     * @var array
     */
    protected $_columns = array();
    
    /**
     * Tracks columns where data has been updated. Allows more specific 
     * save operations.
     *
     * @var array
     */
    protected $_modified = array();
    
	/**
     * KDatabaseTableAbstract parent class or instance.
     *
     * @var object
     */
    protected $_table;

    /**
	 * The object identifier
	 *
	 * @var KIdentifierInterface
	 */
	protected $_identifier;

	 /**
     * Constructor
     *
     * @param 	array	Options containing 'table', 'name'
     */
    public function __construct(array $options = array())
    {
        // Allow the identifier to be used in the initalise function
        $this->_identifier = $options['identifier'];

  		parent::__construct($options);      
  
		// Set the table indentifier
    	if(isset($options['table'])) {
			$this->setTable($options['table']);
		}
		
		// Reset the rowset
		$this->reset();
		
		// Insert the data, if exists
		if(!empty($options['data'])) {
			$this->insert($options['data'], $options['new']);
		}
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
        $options = parent::_initialize($options);
    	
    	$defaults = array(
            'table'      => null,
        	'identifier' => null,
        	'data'		 => null,
    		'new'		 => true
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Get the identifier
	 *
	 * @return 	KIdentifierInterface
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Get the identifier for the table with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getTable()
	{
		if(!$this->_table)
		{
			$identifier 		= clone $this->_identifier;
			$identifier->name	= KInflector::tableize($identifier->name);
			$identifier->path	= array('table');
		
			$this->_table = $identifier;
		}
       	
		return $this->_table;
	}

	/**
	 * Method to set a table object attached to the rowset
	 *
	  * @param	mixed	An object that implements KFactoryIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a table identifier
	 * @return	KDatabaseRowsetAbstract
	 */
	public function setTable($table)
	{
		$identifier = KFactory::identify($table);

		if($identifier->path[0] != 'table') {
			throw new KDatabaseRowsetException('Identifier: '.$identifier.' is not a table identifier');
		}
		
		$this->_table = $identifier;
		return $this;
	}

	/**
     * Returns a KDatabaseRow from a known position or based on a key/value pair
     *
     * @param 	string 	The position or the key to search for
     * @param 	mixed  	The value to search for
     * @return KDatabaseRowAbstract
     */
    public function find($key, $value = null)
    {
    	if(!is_null($value))
    	{
    		$result = KFactory::tmp(KFactory::get($this->getTable())->getRow(), array('table' => $this->getTable()));

    		$this->rewind();

    		while($this->valid())
			{
				$row = $this->current();
				if($row->$key == $value) {
					$result = $row;
					break;
				}
    			$this->next();
			}
    	} 
    	else $result = $this[$key];
    	
		return $result;
    }
    
	/**
     * Saves all rows in the rowset to the database
     *
     * @return KDatabaseRowsetAbstract
     */
    public function save()
    {
    	foreach ($this as $i => $row) {
            $row->save();
        }
		
        return $this;
    }
    
	/**
     * Deletes all rows in the rowset from the database
     *
     * @return KDatabaseRowsetAbstract
     */
    public function delete()
    {
    	foreach ($this as $i => $row) {
            $row->delete();
        }
		
        return $this;
    }
    
	/**
     * Reset the rowset
     *
     * @return KDatabaseRowsetAbstract
     */
    public function reset()
    {
    	$this->_columns  = KFactory::get($this->getTable())->getColumns();
    	$this->_modified = array();
    	
    	$this->setArray(array());
    	  		
        return $this;
    }
    
	/**
     * Insert a new row, a list of rows or an empty row in the rowset
     *
     * @param   array|object 	Either and associative array an object or a KDatabaseRow object
     * @return KDatabaseRowsetAbstract
     */
    public function insert($data, $new = true)
    {
    	//Set the row options
    	$options = array(
    		'table' => $this->getTable(),
    		'new'   => $new
     	);
    	
    	$prototype = KFactory::tmp(KFactory::get($this->getTable())->getRow(), $options);
		$result = array();
		
		if(is_object($data))
		{
			if(!$row instanceof KDatabaseRowAbstract) 
			{
				$new = clone $prototype;
        		$new->setData($data, $new);
        		$result[] = $new;
			} 
			else $result[] = $data;
		}
		
		if(is_array($data))
		{
			foreach($data as $k => $row)
			{
				if(!$row instanceof KDatabaseRowAbstract) 
				{
					$new = clone $prototype;
        			$new->setData($row, $new);
        			$result[] = $new;
				
				} 
				else $result[] = $row;
			}
		}
		
		return parent::setArray($result);
    }
    
	/**
     * Retrieve rowset column value
     *
     * @param  	string 	The column name.
     * @return 	array 	An array of all the column values
     */
    public function __get($column)
    {
    	$result = array();
    	foreach ($this as $i => $row) {
            $result [] = $row->$column;
        }
    	
    	return $result;
    }

    /**
     * Set row field value
     *
     * @param  	string 	The column name.
     * @param  	mixed  	The value for the property.
     * @return 	void
     */
    public function __set($column, $value)
    {
    	//Set the value in each row
    	foreach ($this as $i => $row) {
            $row->$column = $value;
        }
        
        //Add the column 
        if(!in_array($column, $this->_columns)) {
        	$this->_columns[] = $column;
        }
        
        //Track the column as modified
        $this->_modified[$column] = true;
   }

	/**
     * Test existence of rowset field
     *
     * @param  string  The column name.
     * @return boolean
     */
    public function __isset($column)
    {
    	return in_array($column, $this->_columns);
    }

    /**
     * Unset a row field
     * 
     * This function will reset required column to their default value, not required
     * fields will be unset.
     * 
     * @param	string  The column key.
     * @return	void
     */
    public function __unset($column)
    {
    	foreach ($this as $i => $row) {
            unset($row->$column);
        }
        
        unset($this->_columns[array_search($column, $this->_columns)]);
    }

	/**
     * Returns all data as an array.
     *
     * @param   boolean 	If TRUE, only return the modified data. Default FALSE
     * @return array
     */
    public function getData($modified = false)
    {
    	$result = array();
    	foreach ($this as $i => $row)  {
    		$result[$i] = $row->getData($modified);
        }
        return $result;
    }
    
	/**
  	 * Set the row data based on a named array/hash
  	 *
  	 * @param   mixed 	Either and associative array, a KDatabaseRow object or object
  	 * @param   boolean If TRUE, update the modified information for each column being set. 
  	 * 					Default TRUE
 	 * @return 	KDatabaseRowsetAbstract
  	 */
  	 public function setData( $data, $modified = true )
  	 {
  		//Get the data
  	 	if($data instanceof KDatabaseRowAbstract) {
			$data = $data->getData();
		} else {
			$data = (array) $data;
		}
		
		//Set the data in the rows 
		if($modified) 
  	 	{
  	 		foreach($data as $column => $value) {
  	 			$this->$column = $value;
  	 		}
  	 	}
  	 	else
  	 	{
  	 		foreach ($this as $i => $row) {
  	 			$row->setData($data, false);
        	}
  	 	}
  	 	
  	 	//Track any new columbs being added
  	 	foreach ($this as $i => $row) 
  	 	{
  	 	 	if(!in_array($column, $this->_columns)) {
        		$this->_columns[] = $column;
        	}
       }

  		return $this;
	}
	
	/**
	 * Get a list of columns that have been modified
	 * 
	 * @return array	An array of column names that have been modified
	 */
	public function getModified()
	{
		return $this->_modified;
	}
	
	/**
     * Forward the call to each row
     *
   	 * @param  string 	The function name
	 * @param  array  	The function arguments
	 * @throws BadMethodCallException 	If method could not be found
	 * @return mixed The result of the function
     */
    public function __call($method, array $arguments)
    {
   	 	//If the method hasn't been mixed yet, load all the behaviors
    	if(!isset($this->_mixed_methods[$method])) 
        {
			foreach(KFactory::get($this->getTable())->getBehaviors() as $behavior) {
				$this->mixin(KFactory::get($behavior));
			}
        }
        
   	 	//If the method is of the formet is[Bahavior] handle it 
    	$parts = KInflector::explode($method);
    	
    	if($parts[0] == 'is' && isset($parts[1])) 
        {
			if(isset($this->_mixed_methods[$method])) {
				return true;
			}
			
			return false;
        }
        else
        {
       		 //If the mixed method exists call it for all the rows
        	if(isset($this->_mixed_methods[$method])) 
        	{
        		foreach ($this as $i => $row) {
            		$row->__call($method, $arguments);
        		}
        	}
        }
    	
        return parent::__call($method, $arguments);
    }
}