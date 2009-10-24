<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Table
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Table Class
 *
 * Parent class to all tables.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Table
 * @uses		KMixinClass
 * @uses        KFactory
 * @uses 		KFilter
 */
abstract class KDatabaseTableAbstract extends KObject implements KFactoryIdentifiable
{
	/**
	 * Name of the table in the db schema
	 *
	 * @var 	string
	 */
	protected $_table_name;

	/**
	 * Name of the primary key field in the table
	 *
	 * @var		string
	 */
	protected $_primary;

	/**
	 * Field metadata information
	 *
	 * @var 	array
	 */
	protected $_fields;

	/**
	 * Database adapter
	 *
	 * @var		object
	 */
	protected $_db;

	/**
	 * Default values for this table
	 *
	 * @var 	array
	 */
	protected $_defaults;
	
	/**
	 * The object identifier
	 *
	 * @var KFactoryIdentifierInterface 
	 */
	protected $_identifier;

	/**
	 * Object constructor to set table and key field
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @param	array 	An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'table', 'primary' and 'dbo' (this
	 * list is not meant to be comprehensive).
	 */
	public function __construct( array $options = array() )
	{
        // Set the objects identifier
        $this->_identifier = $options['identifier'];
		
		// Initialize the options
        $options  = $this->_initialize($options);
        
		$this->_table_name	= $options['table_name'];
		$this->_primary	    = $options['primary'];
		$this->_db          = isset($options['database']) ? $options['database'] : KFactory::get('lib.koowa.database');
		
		// Set the table fields
		$this->_fields = $this->getFields();
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
        $package = $this->_identifier->package;
        $name    = $this->_identifier->name;
        
    	$defaults = array(
            'db'       		=> null,
            'primary'       => empty($package) ? $name.'_id' : $package.'_'.KInflector::singularize($name).'_id',
            'table_name'    => empty($package) ? $name : $package.'_'.$name,
        	'identifier'	=> null
        );

        return array_merge($defaults, $options);
    }
    
	/**
	 * Get the identifier
	 *
	 * @return 	KFactoryIdentifierInterface A KFactoryIdentifier object
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Get the database adapter
	 *
	 * @return KDatabaseAdapterAbstract
	 */
	public function getDatabase()
    {
		return $this->_db;
	}

	/**
	 * Set the database adapter
	 *
	 * @param	object A KDatabaseAdapterAbstract
	 */
	public function setDatabase(KDatabaseAdapterAbstract $database)
    {
		$this->_db = $database;
	}


	/**
	 * Gets the table schema name
	 *
	 * @return string
	 */
	public function getTableName()
    {
		return $this->_table_name;
	}

	/**
	 * Gets the primary key of the table
	 *
	 * @return string
	 */
	public function getPrimaryKey()
	{
        if(!isset($this->_primary)) {
        	$this->getFields();
        }

		return $this->_primary;
	}

	/**
	 * Get the highest ordering
	 *
	 * Requires an ordering field to be present in the table
	 *
	 * @return int
	 */
	public function getMaxOrder()
	{
		if (!in_array('ordering', $this->getColumns())) {
			throw new KDatabaseTableException("The table '".$this->getTableName()."' doesn't have a 'ordering' column.");
		}

		$query = 'SELECT MAX(ordering) FROM `#__'.$this->getTableName();
		return (int) $this->_db->fetchResult($query) + 1;
	}

	/**
	 * Gets the fields for the table
	 *
	 * @return  array
	 * @throws 	KDatabaseTableException
	 */
	public function getFields()
	{
		if(!isset($this->_fields))
		{
			try {
				$fields = $this->_db->fetchTableFields($this->getTableName());
			} catch(KDatabaseException $e) {
				throw new KDatabaseTableException($e->getMessage());
			}
			
        	$fields = $fields[$this->getTableName()];

        	foreach ($fields as $field)
        	{
				//Parse the field raw data
        		$description = $this->_db->parseField($field);

                // Set the primary key (if not set)
                if(!isset($this->_primary) && $description->primary) {
                	$this->_primary = $description->name;
                }

 	            $this->_fields[$description->name] = $description;
 	        }
        }

		return $this->_fields;
	}

    /**
     * Get default values for all fields
     * 
     * @return  array
     */
    public function getDefaults()
    {
        if(!isset($this->_defaults))
        {
            $this->_defaults = array();
        	foreach($this->getFields() as $name => $description)
        	{
        	    $this->_defaults[$name] = $description->default;
        	    if($name == $this->getPrimaryKey()) {
        	  		$this->_defaults['id'] = $description->default;
        	  	}

            }
        }
    	return $this->_defaults;
    }

    /**
     * Get the description of a field
     *
     * @return string
     */
     public function getField($fieldname)
     {
     	$fields = $this->getFields();
        return $fields[$fieldname];
     }

	/**
	 * Gets the columns of the table
	 *
	 * @return string
	 */
	public function getColumns()
	{
		$fields = $this->getFields();
		return array_keys($fields);
	}

  	/**
     * Fetch a row
     *
     * The name of the resulting class is based on the table class name
     * eg <Mycomp>Table<Tablename> -> <Mycomp>Row<Tablename>
     *
     * @param	mixed	KDatabaseQuery object or query string, a row id or null for an empty row
     * @param	array	Options
     * @return	object 	KDatabaseRow object
     */
    public function fetchRow($query = null, array $options = array())
    {
        $options['table']     = $this;

        $app   	 = $this->_identifier->application;
		$package = $this->_identifier->package;
		$row     = KInflector::singularize($this->_identifier->name);

        //Get the data and push it in the row
		if(isset($query))
        {
            if(is_numeric($query))
            {
             	$key   = $this->getPrimaryKey();
             	$value = $query;

             	//Create query object
       	 		$query = $this->_db->getQuery()
        			->where($key, '=', $value);
            }

        	if($query instanceof KDatabaseQuery)
            {
            	if(!count($query->columns)) {
        			$query->select('*');
        		}

        		if(!count($query->from)) {
        			$query->from($this->getTableName().' AS tbl');
        		}
            }

            $options['data'] = (array) $this->_db->fetchAssoc($query);
        }

        $row = KFactory::tmp($app.'::com.'.$package.'.row.'.$row, $options);
        return $row;
    }

	/**
     * Fetch a rowset
     *
     * The name of the resulting class is based on the table class name
     * eg <Mycomp>Table<Tablename> -> <Mycomp>Rowset<Tablename>
     *
     * @param	mixed	KDatabaseQuery object or query string, array of row id's or null for an empty row
     * @param 	array	Options
     * @return	object	KDatabaseRowset object
     */
    public function fetchRowset($query = null, $options = array())
    {
        $options['table']     = $this;

    	$package = $this->_identifier->package;
   		$rowset  = $this->_identifier->name;
   	 	$app     = $this->_identifier->application;

        // Get the data
        if(isset($query))
        {
         	if(is_array($query))
            {
             	$key    = $this->getPrimaryKey();
             	$values = $query;

             	//Create query object
       	 		$query = $this->_db->getQuery()
        			->where($key, 'IN', $values);
            }

        	if($query instanceof KDatabaseQuery)
            {
        		if(!count($query->columns)) {
        			$query->select('*');
        		}

        		if(!count($query->from)) {
        			$query->from($this->getTableName().' AS tbl');
        		}
            }

			$result = (array) $this->_db->fetchAssocList($query);

   			$options['data'] = $result;
        }

        //return a row set
    	$rowset = KFactory::tmp($app.'::com.'.$package.'.rowset.'.$rowset, $options);
    	return $rowset;
    }

	/**
	 * Table insert method
	 *
	 * @param  array	An associative array of data to be inserted
	 * @throws KDatabaseTableException
	 * @return integer The new object's primary key value, or throw an exception if any errors occur.
	 */
	public function insert( array $data )
	{
		$data  = $this->filter($data);
		$table = $this->getTableName();

		$result = $this->_db->insert($table, $data);
		return $result;
	}

	/**
	 * Table update method
	 *
	 * @param  array	An associative array of data to be updated
	 * @param  mixed	Can either be a row, an array of rows or a KDatabaseQuery object
	 * @throws KDatabaseTableException
	 * @return boolean True if successful otherwise returns false
	 */
	public function update( array $data, $where = null)
	{
		$data  = $this->filter($data);
		$table = $this->getTableName();

		//Create where statement
		if(!($where instanceof KDatabaseQuery))
		{
			$rows = (array) $where;

			//Create where statement
			if (count($rows))
			{
            	$where = $this->_db->getQuery()
            		->where($this->getPrimaryKey(), 'IN', $rows);
			}
		}

		$result = $this->_db->update($table, $data, $where);
		return $result;
	}

	/**
	 * Table delete method
	 *
	 * @param  mixed	Can either be a row, an array of rows or a query object
	 * @throws KDatabaseTableException
	 * @return boolean True if successful otherwise returns false
	 */
	public function delete( $where )
	{
		$table = $this->getTableName();

		//Create where statement
		if(!($where instanceof KDatabaseQuery))
		{
			$rows = (array) $where;

			//Create where statement
			if (count($rows))
			{
            	$where = $this->_db->getQuery()
            		->where($this->getPrimaryKey(), 'IN', $rows);
			}
		}

		$result = $this->_db->delete($table, $where);
		return $result;
	}

	/**
	 * Resets the order of all rows
	 *
	 * @return	KDatabaseTableAbstract
	 */
	public function order()
	{
		if (!in_array('ordering', $this->getColumns())) {
			throw new KDatabaseTableException("The table ".$this->getTableName()." doesn't have a 'ordering' column.");
		}

		$this->_db->execute("SET @order = 0");
		$this->_db->execute(
			 'UPDATE #__'.$this->getTableName().' '
			.'SET ordering = (@order := @order + 1) '
			.'ORDER BY ordering ASC'
		);

		return $this;
	}

	/**
     * Count tahbe rows
     *
     * @param	mixed	KDatabaseQuery object or query string or null to count all rows
     * @return	int		Number of rows
     */
    public function count($query = null)
    {
        //Get the data and push it in the row
		if(!isset($query)) {
        	$query = $this->_db->getQuery();
        }

       	if($query instanceof KDatabaseQuery)
        {
          	$query->count();

           	if(!count($query->from)) {
        		$query->from($this->getTableName().' AS tbl');
        	}
       	}

       	$result = $this->_db->fetchResult($query);
    	return $result;
    }

	/**
	 * Table filter method
	 *
	 * This function removes extra columns and filters the data based on the field type
	 *
	 * @param  array
	 * @return array The filtered data
	 */
	public function filter($data)
	{
		settype($data, 'array'); //force to array

		// Filter out any extra columns.
		$data = array_intersect_key($data, array_flip($this->getColumns()));

		// Filter data based on column type
		foreach($data as $key => $value)
		{
			$type = $this->getField($key)->type;
			$data[$key] = KFactory::tmp('lib.koowa.filter.'.$type)->sanitize($value);
		}

		return $data;
	}
}