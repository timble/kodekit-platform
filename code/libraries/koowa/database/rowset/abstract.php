<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
abstract class KDatabaseRowsetAbstract extends KObjectArray implements KDatabaseRowsetInterface
{
	/**
	 * Table object or identifier (APP::com.COMPONENT.table.NAME)
	 *
	 * @var	string|object
	 */
    protected $_table;
    
    /**
	 * Name of the identity column in the rowset
	 *
	 * @var	string
	 */
	protected $_identity_column;

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
		
    	// Set the table indentifier
    	if(isset($config->identity_column)) {
			$this->_identity_column = $config->identity_column;
		}
		
		// Reset the rowset
		$this->reset();
		
		// Insert the data, if exists
		if(!empty($config->data)) 
		{
			//Create a row prototype and clone it this is faster then instanciating a new row
			$prototype = KFactory::tmp(KFactory::get($this->getTable())->getRow(), array(
    			'table' => $this->getTable(),
    			'new'   => $config->new
     		));
     		
     		//Set the data in the row object and insert the row
			foreach($config->data as $k => $row)
			{
				$clone = clone $prototype;
        		$clone->setData($row, $config->new);
        		
        		$this->addRow($clone);
			}
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
            'table'      		=> null,
        	'data'		 		=> null,
    		'new'		 		=> true,
    		'identity_column'	=> null 
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
     * Returns all data as an array.
     *
     * @param   boolean 	If TRUE, only return the modified data. Default FALSE
     * @return array
     */
    public function getData($modified = false)
    {
    	$result = array();
    	foreach ($this->_data as $i => $row)  {
    		$result[$i] = $row->getData($modified);
        }
        return $result;
    }

	/**
  	 * Set the rowset data based on a named array/hash
  	 *
  	 * @param   mixed 	Either and associative array, a KDatabaseRow object or object
  	 * @param   boolean If TRUE, update the modified information for each column being set.
  	 * 					Default TRUE
 	 * @return 	KDatabaseRowsetAbstract
  	 */
  	 public function setData( $data, $modified = true )
  	 {
  		//Get the data
  	 	if($data instanceof KDatabaseRowInterface) {
			$data = $data->getData();
		} else {
			$data = (array) $data;
		}
		
		//Prevent changing the identity column
		if(isset($this->_identity_column)) {
    		unset($data[$this->_identity_column]);
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
  	 		foreach ($this->_data as $i => $row) {
  	 			$row->setData($data, false);
        	}
  	 	}

  	 	//Track any new colums being added
  	 	foreach ($data as $column => $value)
  	 	{
  	 	 	if(!in_array($column, $this->_columns)) {
        		$this->_columns[] = $column;
        	}
       }

  		return $this;
	}
	
	/**
	 * Gets the identitiy column of the rowset
	 *
	 * @return string
	 */
	public function getIdentityColumn()
	{
		return $this->_identity_column;
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

    		foreach ($this as $i => $row) 
    		{
				if($row->$key == $value) 
				{
					$result = $row;
					break;
				}	
			}
    	}
    	else $result = $this->_data[$key];

		return $result;
    }

	/**
     * Saves all rows in the rowset to the database
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
    public function save()
    {
    	foreach ($this as $i => $row) {
            $row->save();
        }

        return true;
    }

	/**
     * Deletes all rows in the rowset from the database
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
    public function delete()
    {
    	foreach ($this as $i => $row) {
            $row->delete();
        }

        return true;
    }

	/**
     * Reset the rowset
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
    public function reset()
    {
    	$this->_columns  = array_keys(KFactory::get($this->getTable())->getColumns());

    	$this->_data = array();

        return true;
    }

	/**
     * Add a row in the rowset
     * 
     * The row will be stored by i'ts identity_column if set or otherwise by
     * it's object handle.
     *
     * @param  object 	A KDatabaseRow object to be inserted
     * @return KDatabaseRowsetAbstract
     */
    public function addRow(KDatabaseRowInterface $row)
    {
    	if(isset($this->_identity_column)) {
    		$this->_data[$row->{$this->_identity_column}] = $row;
    	} else {
    		$this->_data[$row->getHandle()] = $row;
    	}
    
    	//Add the columns, only if they don't exist yet
    	$columns = array_keys($row->getData());
    	foreach($columns as $column)
    	{
        	if(!in_array($column, $this->_columns)) {
        		$this->_columns[] = $column;
        	}
    	}
    	
		return $this;
    }
    
	/**
     * Removes a row
     * 
     * The row will be removed based on it's identity_column if set or otherwise by
     * it's object handle.
     *
     * @param  object 	A KDatabaseRow object to be removed
     * @return KDatabaseRowsetAbstract
     */
    public function removeRow(KDatabaseRowInterface $row)
    {
    	if(isset($this->_identity_column)) {
    		unset($this->_data[$row->{$this->_identity_column}]);
    	} else {
    		unset($this->_data[$row->getHandle()]);
    	}
    
		return $this;
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
				$this->mixin($behavior);
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
        		
        		return $this;
        	}
        }

        //If the method cannot be found throw an exception
        throw new BadMethodCallException('Call to undefined method :'.$method);
    }
}