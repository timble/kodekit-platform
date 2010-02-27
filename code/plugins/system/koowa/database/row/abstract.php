<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	(C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
abstract class KDatabaseRowAbstract extends KObject implements KFactoryIdentifiable
{
	/**
     * The data container
     * 
     * The data for each column in the row (column_name => value).
     * The keys must match the physical names of columns in the
     * table for which this row is defined.
     *
     * @var array
     */
    protected $_data = array();
    
    /**
     * Array of column name mappings
     *
     * @var array
     */
    protected $_column_map = array();
    
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

    	// Initialize the options
        $options  = $this->_initialize($options);
         
  		// Set the table indentifier
    	if(isset($options['table'])) {
			$this->setTable($options['table']);
		}
		
		//Set the column mappings
		 $this->_column_map       = $options['column_map'];
		 $this->_column_map['id'] = KFactory::get($this->getTable())->getPrimaryKey();
		
		// Reset the row
		$this->reset();

		// Set the row data
		if(isset($options['data']))  {
			$this->setData($options['data']);
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
        $defaults = array(
            'table'      => null,
        	'identifier' => null,
        	'column_map' => array()
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
    	if(empty($this->id)) {
        	$result = KFactory::get($this->getTable())->insert($this); 
        } else {
        	$result = KFactory::get($this->getTable())->update($this);
        }
         
        $this->setData($result);
         
        return $this;
    }

	/**
     * Deletes the row form the database.
     *
     * @return KDatabaseRowAbstract
     */
    public function delete()
    {
    	KFactory::get($this->getTable())->delete($this);
        return $this;
    }

	/**
     * Resets to the default properties
     *
     * @return KDatabaseRowAbstract
     */
    public function reset()
    {
        $this->_data = KFactory::get($this->getTable())->getDefaults();
        return $this;
    }

	/**
     * Retrieve row field value
     *
     * @param  	string 	The user-specified column name.
     * @return 	string 	The corresponding column value.
     */
    public function __get($column)
    {
    	if(isset($this->_column_map[$column])) {
    		$column = $this->_column_map[$column];
    	}
    	
    	return $this->_data[$column];
    }

    /**
     * Set row field value
     *
     * @param  	string 	The column key.
     * @param  	mixed  	The value for the property.
     * @return 	void
     */
    public function __set($column, $value)
    {
    	if(isset($this->_column_map[$column])) {
    		$column = $this->_column_map[$column];
    	}

        $this->_data[$column] = $value;
   }

	/**
     * Test existence of row field
     *
     * @param  string  The column key.
     * @return boolean
     */
    public function __isset($column)
    {
   	 	if(isset($this->_column_map[$column])) {
    		$column = $this->_column_map[$column];
    	}

    	return array_key_exists($column, $this->_data);
    }

    /**
     * Unset a row field
     *
     * @param	string  The column key.
     * @return	void
     */
    public function __unset($column)
    {
    	if(isset($this->_column_map[$column])) {
    		$column = $this->_column_map[$column];
    	}

        unset($this->_data[$column]);
    }
 
   /**
 	* Returns an associative array of the raw data
  	*
  	* @return  array
  	*/
 	public function getData()
  	{
  		return $this->_data;
  	}
  
  	/**
  	 * Set the row data
  	 *
  	 * @param   mixed 	Either and associative array, a KDatabaseRow object or object
 	 * @return 	KDatabaseRowAbstract
  	 */
  	 public function setData( $data )
  	 {
  	 	if($data instanceof KDatabaseRowAbstract) {
			$data = $data->getData();
		} else {
			$data = (array) $data;
		}
	
 		foreach ($data as $k => $v) {
  			$this->$k = $v;
  		}
  		
  		return $this;
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
    	
    	
       return parent::__call($method, $arguments);
    }
}