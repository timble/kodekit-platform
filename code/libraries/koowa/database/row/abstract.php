<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Row Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 */
abstract class KDatabaseRowAbstract extends KObject implements KObjectIdentifiable
{
	/**
	 * Row states
	 */
	const STATUS_DELETED    = 'deleted';
    const STATUS_INSERTED   = 'inserted';
    const STATUS_UPDATED    = 'updated';
	
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
     * Tracks the the status the row
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
     * Tracks if row data is new (i.e., not in the database yet).
     * 
     * @var bool
     */
    protected $_new = true;
    
	/**
     * KDatabaseTableAbstract parent class or instance.
     *
     * @var object
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
            'table' => null,
    		 'data'	=> null,
       		 'new'	=> true
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
     * Returns the status of this row.
     * 
     * @return string The status value.
     */
    public function getStatus()
    {
        return $this->_status;
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
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowException	If the identifier is not a table identifier
	 * @return	KDatabaseRowsetAbstract
	 */
	public function setTable($table)
	{
		$identifier = KFactory::identify($table);

		if($identifier->path[0] != 'table') {
			throw new KDatabaseRowException('Identifier: '.$identifier.' is not a table identifier');
		}
		
		$this->_table = $identifier;
		return $this;
	}

    /**
     * Saves the row to the database.
     *
     * This performs an intelligent insert/update and reloads the properties 
     * with fresh data from the table on success.
     *
     * @return KDatabaseRowAbstract
     */
    public function save()
    {
    	if($this->_new) 
    	{
    		if(KFactory::get($this->getTable())->insert($this)) 
    		{
        		$this->_status   = self::STATUS_INSERTED;
        		$this->_modified = array();
        	}
       	} 
       	else 
       	{
        	if(KFactory::get($this->getTable())->update($this)) 
        	{
        		$this->_status   = self::STATUS_UPDATED;
       			$this->_modified = array();
        	}
        }
    	    
        return $this;
    }

	/**
     * Deletes the row form the database.
     *
     * @return KDatabaseRowAbstract
     */
    public function delete()
    {
    	if(!$this->_new) 
    	{
    		if(KFactory::get($this->getTable())->delete($this)) 
    		{
    			$this->_status   = self::STATUS_DELETED;
    			$this->_modified = array();
    			$this->_new      = false;
    		}
    	}
    	
        return $this;
    }

	/**
     * Resets to the default properties
     *
     * @return KDatabaseRowAbstract
     */
    public function reset()
    {
    	$this->_data     = KFactory::get($this->getTable())->getDefaults();
        $this->_modified = array();
        $this->_status   = null;
        $this->_new      = true;
        
        return $this;
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
    	$field = KFactory::get($this->getTable())->getColumn($column);
    	
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
  	 	if($data instanceof KDatabaseRowAbstract) {
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
		return $this->_modified;
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
    	
    	
       return parent::__call($method, $arguments);
    }
}