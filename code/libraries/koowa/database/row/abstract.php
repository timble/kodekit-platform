<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Database Row Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 */
abstract class KDatabaseRowAbstract extends KObject implements KDatabaseRowInterface
{
	/** 
     * The data for each column in the row (column_name => value).
     *
     * @var array
     */
    protected $_data = array();
    
    /**
     * Tracks columns where data has been updated. Allows more specific 
     * save operations.
     *
     * @var array
     */
    protected $_modified = array();
    
    /**
     * Tracks the status the row
     * 
     * Status values are:
     * 
     * `deleted`
     * : This row has been deleted successfully
     * 
     * `inserted`
     * : The row was inserted successfully.
     * 
     * `updated`
     * : The row was updated successfully.
     * 
     * @var string
     * 
     */
    protected $_status = null;
    
    /**
     * The status message
     * 
     * @var string
     */
    protected $_status_message = '';
    
    /**
     * Tracks if row data is new (i.e., not in the database yet).
     * 
     * @var bool
     */
    protected $_new = true;
    
	/**
	 * Table object or identifier (APP::com.COMPONENT.table.NAME)
	 *
	 * @var	string|object
	 */
    protected $_table;

    /**
     * Constructor
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config = null)
    {
    	//If no config is passed create it
		if(!isset($config)) $config = new KConfig();
    	
    	parent::__construct($config);
         
  		// Set the table indentifier
    	if(isset($config->table)) {
			$this->setTable($config->table);
		}
		
		// Reset the row
		$this->reset();
		
		// Set the new state of the row
		$this->_new = $config->new;
		
		// Set the row data
		if(isset($config->data))  {
			$this->setData($config->data->toArray(), $this->_new);
		}
		
		//Set the status
		if(isset($config->status)) {
			$this->setStatus($config->status);
		}
		
		//Set the status message
		if(!empty($config->status_message)) {
			$this->setStatusMessage($config->status_message);
		}
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'table' 			=> null,
    		 'data'				=> null,
       		 'new'				=> true,
    		 'status' 		    => null,
    		 'status_message' 	=> '', 
        ));
        
        parent::_initialize($config);
    }
    
	/**
	 * Get the object identifier
	 * 
	 * @return	KIdentifier	
	 * @see 	KObjectIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
     * Returns the status
     * 
     * @return string The status
     */
    public function getStatus()
    {
        return $this->_status;
    }
    
	/**
     * Set the status
     * 
     * @param 	string|null 	The status value or NULL to reset the status
     * @return	KDatabaseRowAbstract
     */
    public function setStatus($status)
    {
        $this->_status 	 = $status;
        $this->_new 	 = ($status === NULL) ? true : false;
        $this->_modified = array();
    	
    	return $this;
    }
    
    /**
     * Returns the status message
     * 
     * @return string The status message
     */
	public function getStatusMessage()
   	{
     	return $this->_status_message;
  	}
  	
  	
	/**
     * Set the status message
     * 
     * @param 	string	 	The status message
     * @return	KDatabaseRowAbstract
     */
	public function setStatusMessage($message)
   	{
     	$this->_status_message = $message;
     	return $this;
  	}
	
	/**
	 * Get the identifier for the table with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getTable()
	{
		if(!$this->_table)
		{
			$identifier 		= clone $this->_identifier;
			$identifier->name	= KInflector::tableize($identifier->name);
			$identifier->path	= array('database', 'table');
		
			$this->_table = KFactory::get($identifier);
		}
       	
		return $this->_table;
	}

	/**
	 * Method to set a table object attached to the rowset
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowException	If the identifier is not a table identifier
	 * @return	KDatabaseRowsetAbstract
	 */
	public function setTable($table)
	{
		if(!($table instanceof KDatabaseTableAbstract))
		{
			$identifier = KFactory::identify($table);
	
			if($identifier->path[0] != 'table') {
				throw new KModelException('Identifier: '.$identifier.' is not a table identifier');
			}

			$table = KFactory::get($identifier);
		}
		
		$this->_table = $table;
		return $this;
	}
	
	/**
     * Load the row from the database.
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
	public function load()
	{
		$result = false;
		
		$table = $this->getTable();
		
		//Filter the data
		$data  = $table->filter($this->getData(true), true);
		
		//Select the row
		$row = $table->select($data, KDatabase::FETCH_ROW);
		
		//Set the data if the row was found
		if(!$row->isNew()) 
		{
			$this->setData($row->getData(), false);
			$this->_modified = array();
    		$this->_new      = false;
    		
    		$result = true;
		}
		
		return $result;
	}
	
    /**
     * Saves the row to the database.
     *
     * This performs an intelligent insert/update and reloads the properties 
     * with fresh data from the table on success.
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
    public function save()
    {
    	$result = false;
    	
    	if($this->_new) {
    		$result = $this->getTable()->insert($this);
       	} else {
        	$result = $this->getTable()->update($this);
        }
    	    
        return $result;
    }

	/**
     * Deletes the row form the database.
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
    public function delete()
    {
    	$result = false;
    	
    	if(!$this->_new) {
    		$result = $this->getTable()->delete($this);
    	}
    	
        return $result;
    }

	/**
     * Resets to the default properties
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
    public function reset()
    {
    	$this->_data = $this->getTable()->getDefaults();
    	$this->setStatus(NULL);
        
    	return true;
    }
    
	/**
     * Count the rows in the database based on the data in the row
     *
     * @return integer
     */
	public function count()
	{
		$data  = $this->getTable()->filter($this->getData(true), true);
		$count =  $this->getTable()->count($data);
		
		return $count;
	}

	/**
     * Retrieve row field value
     *
     * @param  	string 	The column name.
     * @return 	string 	The corresponding column value.
     */
    public function __get($column)
    {
    	$result = null;
    	if(isset($this->_data[$column])) {
    		$result = $this->_data[$column];
    	} 
    	
    	return $result;
    }

    /**
     * Set row field value
     * 
     * If the value is the same as the current value it will not be set
     *
     * @param  	string 	The column name.
     * @param  	mixed  	The value for the property.
     * @return 	void
     */
    public function __set($column, $value)
    {
        //If data is unchanged return
    	if(isset($this->_data[$column]) && $this->_data[$column] == $value) {
        	return;
        } 
        
        $this->_data[$column]     = $value;
       	$this->_modified[$column] = true;
       	$this->_status            = null;
   }

	/**
     * Test existence of row field
     *
     * @param  string  The column name.
     * @return boolean
     */
    public function __isset($column)
    {
    	return array_key_exists($column, $this->_data);
    }

    /**
     * Unset a row field
     * 
     * This function will reset required column to their default value, not required
     * fields will be unset.
     * 
     * @param	string  The column name.
     * @return	void
     */
    public function __unset($column)
    {
    	$field = $this->getTable()->getColumn($column);
    	
    	if(isset($field) && $field->required) {
    		$this->_data[$column] = $field->default;
    	} 
    	else 
    	{
    		unset($this->_data[$column]);
    		unset($this->_modified[$column]);
    	}
    }
 
   /**
 	* Returns an associative array of the raw data
  	*
  	* @param   boolean 	If TRUE, only return the modified data. Default FALSE
  	* @return  array
  	*/
 	public function getData($modified = false)
  	{
  		if($modified) {
  			$result = array_intersect_key($this->_data, $this->_modified);	
  		} else {
  			$result = $this->_data;
  		}
  			
  		return $result;
  	}
  
  	/**
  	 * Set the row data
  	 *
  	 * @param   mixed 	Either and associative array, an object or a KDatabaseRow
  	 * @param   boolean If TRUE, update the modified information for each column being set. 
  	 * 					Default TRUE
 	 * @return 	KDatabaseRowAbstract
  	 */
  	 public function setData( $data, $modified = true )
  	 {
  	 	if($data instanceof KDatabaseRowInterface) {
			$data = $data->getData();
		} else {
			$data = (array) $data;
		}
		
		if($modified) 
  	 	{
  	 		foreach($data as $column => $value) {
  	 			$this->$column = $value;
  	 		}
  	 	}
  	 	else
  	 	{
  	 		$this->_data = array_merge($this->_data, $data);
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
		return array_keys($this->_modified);
	}
	
	/**
     * Checks if the row is new or not
     * 
     * @return bool 
     */
    public function isNew()
    {
        return (bool) $this->_new;
    }

 	/**
     * Search the mixin method map and call the method or trigger an error
     * 
     * This functions overloads KObject::__call and implements a just in time
     *  mixin strategy. Available table behaviors are only mixed when needed.
     *  
     * It's also capable of checking is a behavior has been mixed succesfully
     * using is[Behavior] function. If the behavior exists the function will
     * return TRUE, otherwise FALSE.
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
        	foreach($this->getTable()->getBehaviors() as $behavior) {
				$this->mixin($behavior);
			}
        }
    	
        //If the method is of the form is[Bahavior] handle it 
    	$parts = KInflector::explode($method);
    	
    	if($parts[0] == 'is' && isset($parts[1])) 
        {
			if(isset($this->_mixed_methods[$method])) {
				return true;
			}
			
			return false;
        }
    	
    	
       return parent::__call($method, $arguments);
    }
}