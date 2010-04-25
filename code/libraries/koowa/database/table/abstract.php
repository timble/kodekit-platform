<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Table
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
abstract class KDatabaseTableAbstract extends KObject implements KObjectIdentifiable
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
	 * Name of the identity column in the table
	 *
	 * @var	string
	 */
	protected $_identity_column;
	
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
	 * Object constructor 
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config = null)
	{
		//If no config is passed create it
		if(!isset($config)) $config = new KConfig();
		
		parent::__construct($config);
        
		$this->_name 	    = $config->name;
		$this->_base 	    = $config->base;
		$this->_database    = $config->database;
			
		// Set the identity column
    	if(!isset($config->identity_column)) 
    	{
         	foreach ($this->getColumns(true) as $column)
        	{
        		if($column->autoinc) {
                	$this->_identity_column = $column->name;
                	break;
           		}
        	}
		}
		else $this->_identity_column = $config->identity_column;
		
		//Set the default column mappings
		 $this->_column_map = $config->column_map;
		 if(!isset( $this->_column_map['id']) && isset($this->_identity_column)) {
		 	$this->_column_map['id'] = $this->_identity_column;
		 }
		   
		// Set the column filters
		if(!empty($config->filters)) 
		{
			foreach($config->filters as $column => $filter) {
				$this->getColumn($column)->filter = $filter;
			}		
		}
		
		 // Mixin the command chain
        $this->mixin(new KMixinCommandchain(new KConfig(
        	array('mixer' => $this, 'command_chain' => $config->command_chain)
        )));
           
        // Set the table behaviors
		if(!empty($config->behaviors)) {
			$this->addBehaviors($config->behaviors);
		} 
	}

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
    	$package = $this->_identifier->package;
        $name    = $this->_identifier->name;
        
    	$config->append(array(
            'database'        => KFactory::get('lib.koowa.database'),
            'row'   		  => null,
    		'rowset'   	 	  => null,
            'name'   	      => empty($package) ? $name : $package.'_'.$name,
    		'base'     	      => empty($package) ? $name : $package.'_'.$name,
    		'command_chain'   => new KCommandChain(),
    		'column_map'	  => array(),
    		'filters'         => array(),
    		'behaviors'		  => array(),
    		'identity_column' => null
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
	 * Gets the primary key(s) of the table
	 *
	 * @return array	An asscociate array of fields defined in the primary key
	 */
	public function getPrimaryKey()
	{
        $key = array();
		$columns = $this->getColumns(true);
        	
     	foreach ($columns as $name => $description)
       	{
       		if($description->primary) {
       			$keys[$name] = $description;
       		}
        }

		return $keys;
	}
	
 	/**
	 * Add a behavior to the table
	 *
	 * @return	KDatabaseTableAbstract
	 */
 	public function addBehaviors(array $behaviors)
 	{
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
     * Get a column by name
     *
     * @param  boolean  If TRUE, get the column information from the base table. Default is FALSE.
     * @return KDatabaseColumn 	Returns a KDatabaseSchemaColumn object or NULL if the 
     * 							column does not exist
     */
     public function getColumn($columnname, $base = false)
     {
     	$columns = $this->getColumns($base);
        return isset($columns[$columnname]) ? $columns[$columnname] : null;
     }

	/**
	 * Gets the columns for the table
	 *
	 * @param	boolean  If TRUE, get the column information from the base table. Default is FALSE.
	 * @return  array	 Associative array of KDatabaseSchemaColumn objects
	 * @throws 	KDatabaseTableException
	 */
	public function getColumns($base = false)
	{
		//Get the table name
		$name = $base ? $this->getBase() : $this->getName();
		
		try {
			$columns = $this->_database->fetchTableColumns($name);
		} catch(KDatabaseException $e) {
			throw new KDatabaseTableException($e->getMessage());
		}
	
		return $this->mapColumns($columns[$name], true);
	}
	
	/**
	 * Table map method
	 * 
	 * This functions maps the column names to those in the table schema 
	 *
	 * @param  array|string	An associative array of data to be mapped, or a column name
	 * @param  boolean		If TRUE, perform a reverse mapping
	 * @return array|string The mapped data or column name
	 */
	public function mapColumns($data, $reverse = false)
	{
		$map = $reverse ? array_flip($this->_column_map) : $this->_column_map;

		$result = null;
		if(is_array($data))
		{
			$result = array();
			foreach($data as $column => $value)
			{
				if(isset($map[$column])) {
    				$column = $map[$column];
    			}
    		
    			$result[$column] = $value;
			}
		} 
		
		if(is_string($data))
		{
			$result = '';
			if(isset($map[$data])) {
    			$result = $map[$data];
    		}
		}
			
		return $result;
	}
	    
	/**
	 * Gets the identitiy column of the table.
	 *
	 * @return string
	 */
	public function getIdentityColumn()
	{
		$result = '';
		if(isset($this->_identity_column)) {
			$result = $this->mapColumns($this->_identity_column, true);
		}
		
		return $result;
	}
	
	/**
	 * Gets the unqiue key(s) of the table
	 *
	 * @return array	An asscociate array of unique table columns by column name
	 */
	public function getUniqueColumns()
	{
		$keys   = array();
        $columns = $this->getColumns(true);
		
		foreach($columns as $name => $description)
        {
       		if($description->unique) {
       			$keys[$name] = $description;
       		}
     	}
     	
		return $keys;
 	}
     
    /**
     * Get default values for all columns
     * 
     * @return  array
     */
    public function getDefaults()
    {
        if(!isset($this->_defaults))
        {
            $defaults = array();
            $columns  = $this->getColumns();
            
            foreach($columns as $name => $description) {
        	    $defaults[$name] = $description->default;
            }
            
           	$this->_defaults = $defaults;
        }
        
    	return $this->_defaults;
    }
    
	/**
     * Get a default by name
     *
     * @return mixed 	Returns the column default value or NULL if the 
     * 				    column does not exist
     */
     public function getDefault($columnname)
     {
     	$defaults = $this->getDefaults();
        return isset($defaults[$columnname]) ? $defaults[$columnname] : null;
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
     * The name of the resulting row(set) class is based on the table class name
     * eg <Mycomp>Table<Tablename> -> <Mycomp>Row(set)<Tablename>
     * 
     * This function will return an empty rowset if called without a parameter.
     *
     * @param	mixed	KDatabaseQuery, query string, array of row id's, or an id or null
     * @param 	integer	The database fetch mode. Default FETCH_ROWSET.
     * @return	KDatabaseRow or KDatabaseRowset depending on the mode. By default will 
     * 			return a KDatabaseRowset 
     */
	public function select( $query = null, $mode = KDatabase::FETCH_ROWSET)
	{
       //Create query object
		if(is_numeric($query) || is_array($query))
       	{
        	$key    = $this->mapColumns($this->getIdentityColumn());
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
		$context->caller 	= $this;
		$context->operation = KDatabase::OPERATION_SELECT;
		$context->query	  	= $query;
		$context->table	  	= $this->getBase();
		$context->mode      = $mode;
		
		if($this->getCommandChain()->run('before.table.select', $context) === true) 
		{	
			//The row(set) default options
			$options  = array(
				'table' 			=> $this, 
				'new'   			=> true,
				'identity_column'	=> $this->getIdentityColumn()
			);
				
			//Fetch the data based on the fecthmode
			if($context->query)
			{
				//Fetch the raw data and applye reverse column mapping
				if($context->mode == KDatabase::FETCH_ROWSET) 
				{
					$data = $this->_database->fetchArrayList($query);
				
					foreach($data as $key => $value) {
						$data[$key] = $this->mapColumns($value, true);
					}
				} 
				else $data = $this->mapColumns($this->_database->fetchArray($query), true);
				
				$options['data'] = $data;
				$options['new']  = empty($data) ? true : false;	
			}
			
			//Create the row(set) object
 			if($context->mode == KDatabase::FETCH_ROWSET) {
 				$context->data = KFactory::tmp($this->getRowset(), $options);
 			} else {
 				$context->data = KFactory::tmp($this->getRow(), $options);
 			}
    		
			$this->getCommandChain()->run('after.table.select', $context);
		}
		
		return $context->data;
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
		$context->caller 	= $this;
		$context->operation = KDatabase::OPERATION_INSERT;
		$context->data	  	= $row;
		$context->table	  	= $this->getBase();
		
		if($this->getCommandChain()->run('before.table.insert', $context) === true) 
		{
			//Filter the data and remove unwanted columns
			$data = $this->filter($context->data->getData(), true);
			
			//Get the data and apply the column mappings
			$data = $this->mapColumns($data);
			
			//Execute the insert query
			if($result = $this->_database->insert($context->table, $data)) {
				$data[$this->getIdentityColumn()] = $result;
			}
				
			//Reverse apply the column mappings and set the data in the row
			$context->data->setData($this->mapColumns($data, true), false);
			
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
		$context->caller 	= $this;
		$context->operation = KDatabase::OPERATION_UPDATE;
		$context->data  	= $row;
		$context->table	  	= $this->getBase();
			
		if($this->getCommandChain()->run('before.table.update', $context) === true) 
		{
			//Create where statement
			$where = $this->_database->getQuery()
						->where($this->mapColumns($this->getIdentityColumn()), 'IN', (array) $context->data->id);
			
			//Filter the data and remove unwanted columns
			$data = $this->filter($context->data->getData(true), true);
			
			//Cast to array in case $data is empty
			$data = $this->mapColumns($data);
			
			//Execute the update query
			$context->affected = $this->_database->update($context->table, $data, $where);
			
			//Reverse apply the column mappings and set the data in the row
			$context->data->setData($this->mapColumns($data, true), false);
			
			$this->getCommandChain()->run('after.table.update', $context);
		}

		return (bool) $context->affected;
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
		$context->caller    = $this;
		$context->operation = KDatabase::OPERATION_DELETE;
		$context->table	  	= $this->getBase();
		$context->data   	= $row;
		
		if($this->getCommandChain()->run('before.table.delete', $context) === true) 
		{
			//Create where statement
			$where = $this->_database->getQuery()
						->where($this->mapColumns($this->getIdentityColumn()), 'IN', (array) $context->data->id);
							
			//Execute the delete query
			$context->affected = $this->_database->delete($context->table, $where);
			
			$this->getCommandChain()->run('after.table.delete', $context);
		}

		return (bool) $context->affected;
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

       	$result = (int) $this->_database->fetchField($query);
    	return $result;
    }

	/**
	 * Table filter method
	 *
	 * This function removes extra columns based on the table columns taking any table mappings into
	 * account and filters the data based on each column type.
	 *
	 * @param	boolean  If TRUE, get the column information from the base table. Default is TRUE.
	 * @param  array	An associative array of data to be filtered
	 * @return array 	The filtered data array
	 */
	public function filter($data, $base = true)
	{
		settype($data, 'array'); //force to array
			
		// Filter out any extra columns.
		$data = array_intersect_key($data, $this->getColumns($base));

		// Filter data based on column type
		foreach($data as $key => $value) {
			$data[$key] = $this->getColumn($key)->filter->sanitize($value);
		}

		return $data;
	}
}