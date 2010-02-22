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
		$this->_primary_key	= $options['primary_key'];
		$this->_database    = $options['database'];
			
		// Set the field filters
		if(!empty($options['filters'])) 
		{
			foreach($options['filters'] as $field => $filter) {
				$this->getField($field)->filter = $filter;
			}		
		}
		
		// Set the table behaviors
		if(!empty($options['behaviors'])) {
			$this->getInfo()->behaviors = $options['behaviors'];
		} 
		
		// Enqueue the table behaviors in the command chain
		foreach($this->getInfo()->behaviors as $behavior) {
			$options['command_chain']->enqueue($behavior);
		}
		
		 // Mixin the command chain
        $this->mixin(new KMixinCommandchain(array('mixer' => $this, 'command_chain' => $options['command_chain'])));
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
            'primary_key'   => empty($package) ? $name.'_id' : $package.'_'.KInflector::singularize($name).'_id',
            'name'   	    => empty($package) ? $name : $package.'_'.$name,
    		'base'     	    => empty($package) ? $name : $package.'_'.$name,
        	'identifier'    => null,
    		'command_chain' => new KCommandChain(),
    		'filters'       => array(),
    		'behaviors'		=> array('lockable', 'modifiable', 'creatable')
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
	 */
	public function setDatabase(KDatabaseAdapterAbstract $database)
    {
		$this->_database = $database;
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
        	$fields = $this->getFields();
        	
         	foreach ($fields as $field)
        	{
           		// Set the primary key (if not set)
           		if($field->primary) {
                	$this->_primary = $field->name;
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
        $fields = $this->getFields();
		
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
	 * @return  array	 Associative array of KDatabaseSchemaField objects
	 * @throws 	KDatabaseTableException
	 */
	public function getFields()
	{
		try {
			$fields = $this->_database->fetchTableFields($this->getBase());
		} catch(KDatabaseException $e) {
			throw new KDatabaseTableException($e->getMessage());
		}
	
		return $fields[$this->getBase()];
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
            $fields = $this->getFields();
        	
            foreach($fields as $name => $description)
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
     * Get a field by name
     *
     * @return KDatabaseField 	Returns a KDatabaseField object or NULL if the field does 
     *                          not exist
     */
     public function getField($fieldname)
     {
     	$fields = $this->getFields();
        return isset($fields[$fieldname]) ? $fields[$fieldname] : null;
     }

	/**
	 * Gets the columns of the table
	 *
	 * @return array
	 */
	public function getColumns()
	{
		return array_keys($this->getFields());
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
       if(is_numeric($query) || is_array($query))
       {
        	$key    = $this->getPrimaryKey();
           	$values = (array) $query;

         	//Create query object
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
        
		$context = new KCommandContext();
		$context['caller'] 	  = $this;
		$context['operation'] = KDatabase::OPERATION_SELECT;
		$context['data'] 	  = null;
		$context['query']	  = $query;
		$context['table']	  = $this->getBase();
		$context['options']	  = array('table' => $this);
		
		if($this->getCommandChain()->run('before.table.select', $context) === true) 
		{	
			//Only fetch the data if we have a valid query, otherwise create an empty rowset
			$context['options']['data']  = $this->_database->fetchAssocList($query);
    		$context['data'] = KFactory::tmp($this->getRowset(), $context['options']);
			
			$this->getCommandChain()->run('after.table.select', $context);
		}

		return $context['data'];
	}

	/**
	 * Table insert method
	 *
	 * @param  array	An associative array of data to be inserted
	 * @return array 	An associative array of the inserted data
	 */
	public function insert( array $data )
	{
		$context = new KCommandContext();
		$context['caller'] 	  = $this;
		$context['operation'] = KDatabase::OPERATION_INSERT;
		$context['result'] 	  = 0;
		$context['data']	  = $data;
		$context['table']	  = $this->getBase();
		
		if($this->getCommandChain()->run('before.table.insert', $context) === true) 
		{
			//Remove unwanted colums and filter data
			$context['data']   = $this->filter($context['data']);
			$context['result'] = $this->_database->insert($context['table'], $context['data']);
			
			//Set the primary key value from the insert id
			$context['data'][$this->getPrimaryKey()] = $context['result'];
			
			$this->getCommandChain()->run('after.table.insert', $context);
		}

		return $context['data'];
	}

	/**
	 * Table update method
	 *
	 * @param  array	An associative array of data to be updated
	 * @param  mixed	Can either be a row, an array of rows or a KDatabaseQuery object
	 * @return array    An associative array of the updated data
	 */
	public function update( array $data, $where = null)
	{
		//Create where statement
		if(!($where instanceof KDatabaseQuery))
		{
			$rows = (array) $where;

			//Create where statement
			if (count($rows))
			{
            	$where = $this->_database->getQuery()
            		->where($this->getPrimaryKey(), 'IN', $rows);
			}
		}

		$context = new KCommandContext();
		$context['caller'] 	  = $this;
		$context['operation'] = KDatabase::OPERATION_UPDATE;
		$context['result'] 	  = 0;
		$context['data']   	  = $data;
		$context['table']	  = $this->getBase();
		$context['where']	  = $where;
			
		if($this->getCommandChain()->run('before.table.update', $context) === true) 
		{
			//Remove unwanted colums and filter data
			$context['data']   = $this->filter($context['data']);
			$context['result'] = $this->_database->update($context['table'], $context['data'], $context['where']);
			
			$this->getCommandChain()->run('after.table.update', $context);
		}

		return $context['data'];
	}

	/**
	 * Table delete method
	 *
	 * @param  mixed	Can either be a row, an array of rows or a query object
	 * @return boolean True if successful otherwise returns false
	 */
	public function delete( $where )
	{
		//Create where statement
		if(!($where instanceof KDatabaseQuery))
		{
			$rows = (array) $where;

			//Create where statement
			if (count($rows))
			{
            	$where = $this->_database->getQuery()
            		->where($this->getPrimaryKey(), 'IN', $rows);
			}
		}

		$context = new KCommandContext();
		$context['caller']    = $this;
		$context['operation'] = KDatabase::OPERATION_DELETE;
		$context['result']    = false;
		$context['table']	  = $this->getBase();
		$context['where']	  = $where;
		
		if($this->getCommandChain()->run('before.table.delete', $context) === true) 
		{
			$context['result'] = $this->_database->delete($context['table'], $context['where']);
			$this->getCommandChain()->run('after.table.delete', $context);
		}

		return $context['result'];
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
		foreach($data as $key => $value) {
			$data[$key] = $this->getField($key)->filter->sanitize($value);
		}

		return $data;
	}
}