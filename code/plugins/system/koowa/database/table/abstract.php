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
	 * Real name of the table in the db schema
	 *
	 * @var string
	 */
	protected $_name;
	
	/**
	 * Base name of the table in the db schema
	 *
	 * @var string
	 */
	protected $_base;
	
	/**
	 * Name of the primary key field in the table
	 *
	 * @var	string
	 */
	protected $_primary_key;
	
	/**
     * Array of column mappings by column name
     *
     * @var array
     */
    protected $_column_map = array();
	
	/**
	 * Database adapter
	 *
	 * @var	object
	 */
	protected $_database;
	
	/**
	 * Row object or identifier (APP::com.COMPONENT.row.ROWNAME)
	 *
	 * @var	string|object
	 */
	protected $_row;

	/**
	 * Rowet object or identifier (APP::com.COMPONENT.rowset.ROWSETNAME)
	 *
	 * @var	string|object
	 */
	protected $_rowset;

	/**
	 * Default values for this table
	 *
	 * @var array
	 */
	protected $_defaults;
	
	/**
	 * The object identifier
	 *
	 * @var KIdentifierInterface 
	 */
	protected $_identifier;

	/**
	 * Object constructor to set table and key field
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @param	array 	An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'base', 'primary_key', 'database'
	 * (this list is not meant to be comprehensive).
	 */
	public function __construct( array $options = array() )
	{
        // Allow the identifier to be used in the initalise function
        $this->_identifier = $options['identifier'];
		
		// Initialize the options
        $options  = $this->_initialize($options);
        
		$this->_name 	    = $options['name'];
		$this->_base 	    = $options['base'];
		$this->_database    = $options['database'];
			
		//Set the column mappings
		 $this->_column_map = $options['column_map'];
		 if(!isset( $this->_column_map['id'])) {
		 	$this->_column_map['id'] = $this->getPrimaryKey();
		 }
		 
		// Set the field filters
		if(!empty($options['filters'])) 
		{
			foreach($options['filters'] as $field => $filter) {
				$this->getField($field)->filter = $filter;
			}		
		}
		
		 // Mixin the command chain
        $this->mixin(new KMixinCommandchain(array('mixer' => $this, 'command_chain' => $options['command_chain'])));
	
		// Set the table behaviors
		if(!empty($options['behaviors'])) {
			$this->addBehaviors($options['behaviors']);
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
        $package = $this->_identifier->package;
        $name    = $this->_identifier->name;
        
    	$defaults = array(
            'database'      => KFactory::get('lib.koowa.database'),
            'row'   		=> null,
    		'rowset'   		=> null,
            'name'   	    => empty($package) ? $name : $package.'_'.$name,
    		'base'     	    => empty($package) ? $name : $package.'_'.$name,
        	'identifier'    => null,
    		'command_chain' => new KCommandChain(),
    		'column_map'	=> array(),
    		'filters'       => array(),
    		'behaviors'		=> array()
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
	 * Get the database adapter
	 *
	 * @return KDatabaseAdapterAbstract
	 */
	public function getDatabase()
    {
		return $this->_database;
	}

	/**
	 * Set the database adapter
	 *
	 * @param	object A KDatabaseAdapterAbstract
	 * @return	KDatabaseTableAbstract
	 */
	public function setDatabase(KDatabaseAdapterAbstract $database)
    {
		$this->_database = $database;
		return $this;
	}

	/**
	 * Gets the table schema name without the table prefix
	 *
	 * @return string
	 */
	public function getName()
    {
		return $this->_name;
	}
	
	/**
	 * Gets the base table name without the table prefix
	 * 
	 * If the table type is 'VIEW' the base name will be the name of the base 
	 * table that is connected to the view. If the table type is 'BASE' this
	 * function will return the same as {@link getName}
	 *
	 * @return string
	 */
	public function getBase()
    {
		return $this->_base;
	}
	
	/**
	 * Gets the primary key of the table
	 *
	 * @return string
	 */
	public function getPrimaryKey()
	{
        if(!isset($this->_primary_key)) 
        {
        	$fields = $this->getFields(true);
        	
         	foreach ($fields as $field)
        	{
           		// Set the primary key (if not set)
           		if($field->primary) {
                	$this->_primary_key = $field->name;
                	break;
           		}
 	  		}
        }

		return $this->_primary_key;
	}
	
	/**
	 * Gets the unqiue key(s) of the table
	 *
	 * @return array	An asscociate array of unique table fields by field name
	 */
	public function getUniqueKeys()
	{
		$keys   = array();
        $fields = $this->getFields(true);
		
		foreach($fields as $name => $description)
        {
       		if($description->unique) {
       			$keys[$name] = $description;
       		}
     	}
     	
		return $keys;
 	}
 	
	/**
	 * Gets the foreign key(s) of the table
	 *
	 * @return array	An asscociate array of unique table fields by field name
	 */
	public function getForeignKeys()
	{
		$keys = array();
		return $keys;
 	}
 	
 	/**
	 * Add a behavior to the table
	 *
	 * @return	KDatabaseTableAbstract
	 */
 	public function addBehaviors($behaviors)
 	{
 		$behaviors = (array) $behaviors;
 		
 		$result = array();
 		foreach($behaviors as $identifier)
		{
			if(!($identifier instanceof KDatabaseBehaviorInterface)) 
			{
				if(is_string($identifier) && strpos($identifier, '.') === false ) {
					$identifier = 'lib.koowa.database.behavior.'.trim($identifier);
				}
			}
			
			//Get the actual identifier, take mappings into account
			$identifier = KFactory::identify($identifier);
			$name       = (string) $identifier;
			
			$result[$name] = $identifier; 
			
			//Enqueue the behavior in the command chain
			$this->getCommandChain()->enqueue(KFactory::get($identifier));
		}
		
		//Set the behaviors in the database schema
		$this->getInfo()->behaviors = array_merge($this->getInfo()->behaviors, $result);
		
		return $this;
 	}
 	
	/**
	 * Gets the behaviors of the table
	 *
	 * @return array	An asscociate array of table behaviors, keys are the behavior names
	 */
	public function getBehaviors()
	{
		return $this->getInfo()->behaviors;
 	}
	
	/**
	 * Gets the schema of the table
	 *
	 * @return  KDatabaseSchemaTable
	 * @throws 	KDatabaseTableException
	 */
	public function getInfo()
	{
		try {
			$info = $this->_database->fetchTableInfo($this->getBase());
		} catch(KDatabaseException $e) {
			throw new KDatabaseTableException($e->getMessage());
		}
			
        return $info[$this->getBase()];
	}

	/**
	 * Gets the fields for the table
	 *
	 * @param	boolean  If TRUE, get the field information from the base table. Default is FALSE.
	 * @return  array	 Associative array of KDatabaseSchemaField objects
	 * @throws 	KDatabaseTableException
	 */
	public function getFields($base = false)
	{
		//Get the table name
		$name = $base ? $this->getBase() : $this->getName();
		
		try {
			$fields = $this->_database->fetchTableFields($name);
		} catch(KDatabaseException $e) {
			throw new KDatabaseTableException($e->getMessage());
		}
	
		return $this->map($fields[$name], true);
	}
	
 	/**
     * Get a field by name
     *
     * @return KDatabaseField 	Returns a KDatabaseField object or NULL if the 
     * 							field does not exist
     */
     public function getField($fieldname)
     {
     	$fields = $this->getFields();
        return isset($fields[$fieldname]) ? $fields[$fieldname] : null;
     }
     
	/**
	 * Gets the columns of the table
	 *
	 * @param	boolean  If TRUE, get the field information from the base table. Default is FALSE.
	 * @return array
	 */
	public function getColumns($base = false)
	{
		return array_keys($this->getFields($base));
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
            $defaults = array();
            $fields  = $this->getFields();
            
            foreach($fields as $name => $description) {
        	    $defaults[$name] = $description->default;
            }
            
           	$this->_defaults = $defaults;
        }
        
    	return $this->_defaults;
    }
    
	/**
     * Get a default by name
     *
     * @return mixed 	Returns the field default value or NULL if the 
     * 				    field does not exist
     */
     public function getDefault($fieldname)
     {
     	$defaults = $this->getDefaults();
        return isset($defaults[$fieldname]) ? $defaults[$fieldname] : null;
     }
    
	/**
	 * Get the identifier for a row with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getRow()
	{
		if(!$this->_row)
		{
			$identifier			= clone $this->_identifier;
			$identifier->path	= array('row');
			$identifier->name	= KInflector::singularize($this->_identifier->name);
		
			$this->_row = $identifier;	
		}
		
		return $this->_row;
	}
	
	/**
	 * Get the identifier for the rowset with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getRowset()
	{
		if(!$this->_rowset)
		{
			$identifier			= clone $this->_identifier;
			$identifier->path	= array('rowset');
		
			$this->_rowset = $identifier;	
		}
		
		return $this->_rowset;
	}

    /**
     * Table select method
     *
     * The name of the resulting rowset class is based on the table class name
     * eg <Mycomp>Table<Tablename> -> <Mycomp>Rowset<Tablename>
     * 
     * This function will return an empty rowset if called without a parameter.
     *
     * @param	mixed	KDatabaseQuery, query string, array of row id's, or an id or null
     * @param 	array	Options
     * @return	KDatabaseRowset 
     */
	public function select( $query = null)
	{
       //Create query object
		if(is_numeric($query) || is_array($query))
       	{
        	$key    = $this->getPrimaryKey();
           	$values = (array) $query;

       		$query = $this->_database->getQuery()
        				->where($key, 'IN', $values);
     	}
         	
      	if($query instanceof KDatabaseQuery)
       	{
       		if(!count($query->columns)) {
        		$query->select('*');
        	}

        	if(!count($query->from)) {
        		$query->from($this->getName().' AS tbl');
        	}
      	}
        
      	//Create commandchain context
		$context = $this->getCommandChain()->getContext();
		$context['caller'] 	  = $this;
		$context['operation'] = KDatabase::OPERATION_SELECT;
		$context['query']	  = $query;
		$context['table']	  = $this->getBase();
		
		if($this->getCommandChain()->run('before.table.select', $context) === true) 
		{	
			//Fetch the raw data
			$data = $this->_database->fetchAssocList($query);
			
			//Reverse apply the column mappings
			foreach($data as $key => $value) {
				$data[$key] = $this->map($value, true);
			}
			
			//Build the rowset object
			$options  = array(
				'table' => $this, 
				'data' => $data,
			    'new'  => false
 			);
			
    		$context['data'] = KFactory::tmp($this->getRowset(), $options);
    		
			$this->getCommandChain()->run('after.table.select', $context);
		}
		
		return $context['data'];
	}

	/**
	 * Table insert method
	 *
	 * @param  object	A KDatabaseRow object
	 * @return boolean  TRUE if successfull, otherwise false
	 */
	public function insert( KDatabaseRowAbstract $row )
	{
		//Create commandchain context
		$context = $this->getCommandChain()->getContext();
		$context['caller'] 	  = $this;
		$context['operation'] = KDatabase::OPERATION_INSERT;
		$context['data']	  = $row;
		$context['table']	  = $this->getBase();
		
		if($this->getCommandChain()->run('before.table.insert', $context) === true) 
		{
			//Filter the data and remove unwanted fields
			$data = $this->filter($context['data']->getData(), true);
			
			//Get the data and apply the column mappings
			$data = $this->map($data);
			
			//Execute the insert query
			if($result = $this->_database->insert($context['table'], $data)) {
				$data[$this->getPrimaryKey()] = $result;
			}
				
			//Reverse apply the column mappings and set the data in the row
			$context['data']->setData($this->map($data, true), false);
			
			$this->getCommandChain()->run('after.table.insert', $context);
		}

		return true;
	}

	/**
	 * Table update method
	 *
	 * @param  object	A KDatabaseRow object
	 * @return boolean  TRUE if successfull, otherwise false
	 */
	public function update( KDatabaseRowAbstract $row)
	{
		//Create commandchain context
		$context = $this->getCommandChain()->getContext();
		$context['caller'] 	  = $this;
		$context['operation'] = KDatabase::OPERATION_UPDATE;
		$context['data']   	  = $row;
		$context['table']	  = $this->getBase();
			
		if($this->getCommandChain()->run('before.table.update', $context) === true) 
		{
			//Create where statement
			$where = $this->_database->getQuery()->where($this->getPrimaryKey(), 'IN', (array) $context['data']->id);
			
			//Filter the data and remove unwanted fields
			$data = $this->filter($context['data']->getData(true), true);
			
			//Get the data and apply the column mappings
			$data = $this->map($data);
			
			//Execute the update query
			$context['affected'] = $this->_database->update($context['table'], $data, $where);
			
			//Reverse apply the column mappings and set the data in the row
			$context['data']->setData($this->map($data, true), false);
			
			$this->getCommandChain()->run('after.table.update', $context);
		}

		return (bool) $context['affected'];
	}

	/**
	 * Table delete method
	 *
	 * @param  object	A KDatabaseRow object
	 * @return boolean  TRUE if successfull, otherwise false
	 */
	public function delete( KDatabaseRowAbstract $row )
	{
		//Create commandchain context
		$context = $this->getCommandChain()->getContext();
		$context['caller']    = $this;
		$context['operation'] = KDatabase::OPERATION_DELETE;
		$context['table']	  = $this->getBase();
		$context['data']   	  = $row;
		
		if($this->getCommandChain()->run('before.table.delete', $context) === true) 
		{
			//Create where statement
			$where = $this->_database->getQuery()->where($this->getPrimaryKey(), 'IN', (array) $context['data']->id);
			
			//Execute the delete query
			$context['affected'] = $this->_database->delete($context['table'], $where);
			
			$this->getCommandChain()->run('after.table.delete', $context);
		}

		return (bool) $context['affected'];
	}

	/**
     * Count table rows
     *
     * @param	mixed	KDatabaseQuery object or query string or null to count all rows
     * @return	int		Number of rows
     */
    public function count($query = null)
    {
        //Get the data and push it in the row
		if(!isset($query)) {
        	$query = $this->_database->getQuery();
        }

       	if($query instanceof KDatabaseQuery)
        {
          	$query->count();

           	if(!count($query->from)) {
        		$query->from($this->getName().' AS tbl');
        	}
       	}

       	$result = (int) $this->_database->fetchResult($query);
    	return $result;
    }

	/**
	 * Table filter method
	 *
	 * This function removes extra columns based on the table fields taking any table mappings into
	 * account and filters the data based on each field type.
	 *
	 * @param	boolean  If TRUE, get the field information from the base table. Default is TRUE.
	 * @param  array	An associative array of data to be filtered
	 * @return array 	The filtered data array
	 */
	public function filter($data, $base = true)
	{
		settype($data, 'array'); //force to array
		
		// Filter out any extra columns.
		$data = array_intersect_key($data, array_flip($this->getColumns($base)));

		// Filter data based on column type
		foreach($data as $key => $value) {
			$data[$key] = $this->getField($key)->filter->sanitize($value);
		}

		return $data;
	}
	
	/**
	 * Table map method
	 * 
	 * This functions maps the data column names to those in the table schema 
	 *
	 * @param  array	An associative array of data to be mapped
	 * @param  boolean	If TRUE, perform a reverse mapping
	 * @return array The mapped data array
	 */
	public function map($data, $reverse = false)
	{
		settype($data, 'array'); //force to array
		
		$map = $reverse ? array_flip($this->_column_map) : $this->_column_map;
		
		$result = array();
		foreach($data as $column => $value)
		{
			if(isset($map[$column])) {
    			$column = $map[$column];
    		}
    		
    		$result[$column] = $value;
		}
		
		return $result;
	}
}