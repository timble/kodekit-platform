<?php
/**
 * @version		$Id$
 * @package     Koowa_Database
 * @subpackage  Table
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Table Class
 *
 * Parent class to all tables.
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Database
 * @subpackage  Table
 * @uses		KPatternClass
 */
abstract class KDatabaseTableAbstract extends KObject
{
	/**
	 * The base path
	 *
	 * @var		string
	 */
	protected $_basePath;

	/**
	 * Name of the table in the db schema
	 *
	 * @var 	string
	 */
	protected $_table;

	/**
	 * Name of the primary key field in the table
	 *
	 * @var		string
	 */
	protected $_primary;

	/**
	 * Table field information
	 *
	 * @var 	array
	 */
	protected $_fields;

	/**
	 * Database connector
	 *
	 * @var		object
	 */
	protected $_db;

	/**
 	 * Map of native MySQL types to generic types used when reading
 	 * table column information.
 	 *
 	 * @var array
 	 */
 	protected $_typemap = array(

 	    // numeric
 	    'smallint'          => 'integer',
 	    'int'               => 'integer',
 	    'integer'           => 'integer',
 	    'bigint'            => 'integer',
 	    'numeric'			=> 'numeric',
 	    'dec'               => 'numeric',
 	   	'decimal'           => 'numeric',
 	   	'float'				=> 'float'  ,
		'double'            => 'float'  ,
		'real' 				=> 'float'  ,

 	   	// date & time
 	   	'date'              => 'date'     ,
 	   	'time'              => 'time'     ,
 	   	'datetime'          => 'timestamp',
 	   	'timestamp'         => 'integer'  ,
 	   	'year'				=> 'integer'  ,

 	   	// string
 	   	'national char'     => 'string',
 	   	'nchar'             => 'string',
 	   	'char'              => 'string',
 	   	'binary'            => 'string',
 	   	'national varchar'  => 'string',
 	   	'nvarchar'          => 'string',
 	   	'varchar'           => 'string',
 	   	'varbinary'         => 'string',

 	   	// blob
 	   	'longtext'          => 'blob',
 	 	'longblob'          => 'blob',
	);

	/**
	 * Object constructor to set table and key field
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @param	array 	An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'table', 'primary' and 'dbo' (this
	 * list is not meant to be comprehensive).
	 */
	public function __construct( $options = array() )
	{
        // Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KPatternClass
        $this->mixin(new KPatternClass($this, 'Table'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Set a base path for use by the view
		$this->_basePath = $options['base_path'];

		// Set the tablename
		if ($options['table']) {
			$this->_table	= $options['table'];
		} else {
            $prefix         = $this->getClassName('prefix');
            $suffix         = $this->getClassName('suffix');
			$this->_table	= empty($prefix) ? $suffix : $prefix.'_'.$suffix;
		}

		// Set a primary key
		$this->_primary	= $options['primary'];

		//set the dbo
		$this->_db = $options['dbo'] ? $options['dbo'] : KFactory::get('Database');
	}

    /**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize($options)
    {
        $defaults = array(
            'base_path'     => null,
            'dbo'           => null,
            'name'          => array(
                        'prefix'    => 'k',
                        'base'      => 'table',
                        'suffix'    => 'default'
                        ),
            'primary'       => null,
            'table'         => null
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Get the internal database object
	 *
	 * @return object A JDatabase based object
	 */
	public function getDBO()
    {
		return $this->_db;
	}

	/**
	 * Set the internal database object
	 *
	 * @param	object	$db	A JDatabase based object
	 * @return	void
	 */
	public function setDBO(&$db)
    {
		$this->_db =& $db;
	}


	/**
	 * Gets the table schema name
	 *
	 * @return string
	 */
	public function getTableName()
    {
		return $this->_table;
	}

	/**
	 * Gets the primary key of the table
	 *
	 * @return string
	 */
	public function getPrimaryKey()
	{
        if(!isset($this->_primary))
        {
        	$this->getFields();
        }

		return $this->_primary;
	}

	/**
	 * Gets the fields for the table
	 *
	 * @return string
	 */
	public function getFields()
	{
		if(!isset($this->_fields))
		{
			$fields = $this->_db->getTableFields($this->getTableName());
        	$fields = $fields[$this->getTableName()];

        	foreach ($fields as $field)
        	{
 	            $name = $field->Field;

 	            // override $type to find tinyint(1) as boolean
 	            if (strtolower($field->Type) == 'tinyint(1)') {
 	                $type 	= 'bool';
 	                $size 	= null;
 	                $scope 	= null;
 	            } else {
 	                list($type, $size, $scope) = $this->_parseRawType($field->Type);
 	            }

 	            // save the column description
 	            $description = new stdClass();
 	            $description->name    = $name;
 	            $description->type    = $type;
 	            $description->size    = ($size  ? (int) $size  : null);
 	            $description->scope   = ($scope ? (int) $scope : null);
 	            $description->default = $field->Default;
 	            $description->require = (bool) ($field->Null != 'YES');
 	            $description->primary = (bool) ($field->Key == 'PRI');
 	            $description->autoinc = (bool) (strpos($field->Extra, 'auto_increment') !== false);

 	            // don't keep "size" for integers
 	            if (substr($type, -3) == 'int') {
 	                $description->size = null;
 	            }

                // Set the primary key (if not set)
                if(!isset($this->_primary) AND $description->primary) {
                	$this->_primary = $description->name;
                }

 	            $this->_fields[$name] = $description;
 	        }
        }

		return $this->_fields;
	}

    /**
     * Get default values for all fields
     */
    public function getDefaults()
    {
        static $defaults;

        if(!isset($defaults))
        {
            $defaults = array();
        	foreach($this->getFields() as $name => $description) {
        	   $defaults[$name] = $description->default;
            }
        }
    	return $defaults;
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
     * Fetch a set of rows
     *
     * @param	object	Query object or null for an empty set
     * @param 	int		Offset
     * @param	int		Limit
     * @param 	array	Config
     * @return	object	KDatabaseRowset object
     */
    public function fetchAll($query = null, $offset = 0, $limit = 0, $options = array())
    {
	   	// fetch an empty rowset
        $options['table']     = $this;
		$options['base_path'] = array_key_exists('path', $options) ? $options['path'] : null;
		
		$object = array(
			'type' 		=> 'rowset'  ,
			'component'	=> $this->getClassName('prefix'),
			'name'		=> $this->getClassName('suffix')
		);

        // Get the data
		$query = $query->select('*')
        		->from('#__'.$this->getTableName())
                ->toString();
        $this->_db->select($query, $offset, $limit);
		$options['data'] = $this->_db->loadAssocList();

		return KFactory::getInstance($object, $options);
    }

    /**
     * Find a row by the table's primary key
     *
     * @param	int		Id of the primary key
     * @return	object	KDatabaseRow object
     */
    public function find($id = 0)
    {
        $key = $this->getPrimaryKey();

        $query = null;
        if($id)
        {
            $query = new KDatabaseQuery();
            $query->where($key, '=', $id);
        }

        return $this->fetchRow($query);
    }

    /**
     * Fetch a DatabaseRow object
     *
     * The name of the resulting class is based on the table class name
     * eg <Mycomp>Table<Tablename> -> <Mycomp>Row<Tablename>
     *
     * @param	object	Query object
     * @param	array	Config
     * @return	object 	KDatabaseRow object
     */
    public function fetchRow($query = null, $options = array())
    {
        $row = $this->fetchNew($options);

        $data = array();
        if($query)
        {
            // Get the row
            $query = $query->select('*')
                                ->from('#__'.$this->getTableName())
                                ->toString();
            $this->_db->select($query, 0, 1);
            $data = $this->_db->loadAssoc();
        }

        if(!empty($data)) {
        	$row->setProperties($data);
        }

        return $row;
    }

    /**
     * Fetch a new row
     *
     * @param	array	Options
     * @return	object 	KDatabaseRow object
     */
    public function fetchNew($options = array())
    {
        // Options
        $options['table']     = $this;
		$options['base_path'] = array_key_exists('path', $options) ? $options['path'] : null;

		$object = array(
			'type' 		=> 'row'  ,
			'component'	=> $this->getClassName('prefix'),
			'name'		=> $this->getClassName('suffix')
		);

        return KFactory::getInstance($object, $options);
    }

	/**
	 * Table select method
	 *
	 * @return boolean True if successful otherwise returns false
	 */
	public function select( $where = '', $order = '', $count = '', $offset = '' )
	{

	}

	/**
	 * Table insert method
	 *
	 * @param  array	An associative array of data to be inserted
	 * @return integer The new object's primary key value, or throw an exception if any errors occur.
	 */
	public function insert( $data )
	{
		$data  = $this->filter($data);
		$table = $this->getTableName();

		$result = $this->_db->insert($table, $data);
		if($err = $this->_db->getError()) {
        	$this->setError($err);
        }
		return $result;
	}

	/**
	 * Table update method
	 *
	 * @param  array	An associative array of data to be updated
	 * @param  mixed	Can either be a row, an array of rows or a query object
	 * @return boolean True if successful otherwise returns false
	 */
	public function update( $data, $where = null)
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
            	$rows = "'".implode("','", $rows)."'";

            	$where = $this->_db->getQuery()
            		->where($this->getPrimaryKey(), 'IN', '( '.$rows.' )');
			}
		}

		$result = $this->_db->update($table, $data, $where);
		if($err = $this->_db->getError()) {
        	$this->setError($err);
        }
        return $result;
	}

	/**
	 * Table delete method
	 *
	 * @param  mixed	Can either be a row, an array of rows or a query object
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
            	// Is the primary key an integer?
            	$rows = "'".implode("','", $rows)."'";

            	$where = $this->_db->getQuery()
            		->where($this->getPrimaryKey(), 'IN', '( '.$rows.' )');
			}
		}

		$result = $this->_db->delete($table, $where);
		if($err = $this->_db->getError()) {
        	$this->setError($err);
        }
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
			switch ($type)
			{
				case 'bool':
					$data[$key] = (bool) $value;
					break;

 	      		case 'integer':
					preg_match('/-?[0-9]+/', (string) $value, $matches);
					$data[$key] = @ (int) $matches[0];
 	      			break;

 	      		case 'numeric':
 	      		case 'float'  :
 	        		preg_match('/-?[0-9]+(\.[0-9]+)?/', (string) $value, $matches);
					$data[$key] = @ (float) $matches[0];
 	       			break;

 	      		case 'date'     :
 	       		case 'time'     :
 	      		case 'timestamp':
                    $data[$key] = date('Y-m-d H:i:s', strtotime($value));
 	      			break;

 	        	case 'string':
	    			$filter	= JFilterInput::getInstance();
					$data[$key] = (string) $filter->_remove($filter->_decode((string) $value));
	    			break;

 	        	case 'blob':
 	          		// no filters, blobs are pretty generic
 	          		break;
 	    	}
		}

		return $data;
	}

	/**
	 * Given a column specification, parse into datatype, size, and
	 * decimal scope.
	 *
	 * @param string $spec The column specification; for example,
 	 * "VARCHAR(255)" or "NUMERIC(10,2)".
 	 *
 	 * @return array A sequential array of the column type, size, and scope.
 	 *
 	 */
	protected function _parseRawType($spec)
 	{
 	 	$spec  = strtolower($spec);
 	  	$type  = null;
 	   	$size  = null;
 	   	$scope = null;

 	   	// find the parens, if any
 	   	$pos = strpos($spec, '(');
 	   	if ($pos === false)
 	   	{
 	     	// no parens, so no size or scope
 	      	$type = $spec;
 	   	}
 	   	else
 	   	{
 	     	// find the type first.
 	      	$type = substr($spec, 0, $pos);

 	      	// there were parens, so there's at least a size.
 	       	// remove parens to get the size.
 	      	$size = trim(substr($spec, $pos), '()');

 	      	// a comma in the size indicates a scope.
 	      	$pos = strpos($size, ',');
 	      	if ($pos !== false) {
 	        	$scope = substr($size, $pos + 1);
 	           	$size  = substr($size, 0, $pos);
 	       	}
 	   	}

 	   	foreach ($this->_typemap as $native => $system)
 	   	{
 	      	// $type is already lowered
 	       	if ($type == strtolower($native)) {
 	         	$type = strtolower($system);
 	           	break;
 	       	}
 	   	}

 	  	return array($type, $size, $scope);
 	}
}