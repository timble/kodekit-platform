<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Mysqli Database Adapter
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 */
class KDatabaseAdapterMysqli extends KDatabaseAdapterAbstract
{
	/**
	 * Quote for named objects
	 *
	 * @var string
	 */
	protected $_name_quote = '`';
	
	/**
 	 * Map of native MySQL types to generic types used when reading
 	 * table column information.
 	 *
 	 * @var array
 	 */
 	protected $_typemap = array(

 	    // numeric
 	    'smallint'          => 'int',
 	    'int'               => 'int',
 	    'integer'           => 'int',
 	    'bigint'            => 'int',
 		'mediumint'			=> 'int',
 		'smallint'			=> 'int',
 		'tinyint'			=> 'int',
 	    'numeric'			=> 'numeric',
 	    'dec'               => 'numeric',
 	   	'decimal'           => 'numeric',
 	   	'float'				=> 'float'  ,
		'double'            => 'float'  ,
		'real' 				=> 'float'  ,
 	
 		// boolean
 		'bool'				=> 'boolean',
 		'boolean' 			=> 'boolean',

 	   	// date & time
 	   	'date'              => 'date'     ,
 	   	'time'              => 'time'     ,
 	   	'datetime'          => 'timestamp',
 	   	'timestamp'         => 'int'  ,
 	   	'year'				=> 'int'  ,

 	   	// string
 	   	'national char'     => 'string',
 	   	'nchar'             => 'string',
 	   	'char'              => 'string',
 	   	'binary'            => 'string',
 	   	'national varchar'  => 'string',
 	   	'nvarchar'          => 'string',
 	   	'varchar'           => 'string',
 	   	'varbinary'         => 'string',
 		'text'				=> 'string',
 		'mediumtext'		=> 'string',
 		'tinytext'			=> 'string',
 		'longtext'			=> 'string',

 	   	// blob
 	   	'blob'				=> 'raw',
 		'tinyblob'			=> 'raw',
 		'mediumblob'		=> 'raw',
 	   	'longtext'          => 'raw',
 	 	'longblob'          => 'raw',
 	
 		//other
 		'set'				=> 'string',
 		'enum'				=> 'string', 	
	);
	
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
    	$config->append(array(
    		'options'	=> array(
    			'host'		=> ini_get('mysqli.default_host'), 
    			'username'	=> ini_get('mysqli.default_user'),
    			'password'  => ini_get('mysqli.default_pw'),
    			'database'	=> '',
    			'port'		=> ini_get("mysqli.default_port"),
    			'socket'	=> ini_get("mysqli.default_socket")
    		)
        ));
        
        parent::_initialize($config);
    }

	/**
	 * Connect to the db
	 * 
	 * @return KDatabaseAdapterMysqli
	 */
	 public function connect()
	 {
		$oldErrorReporting = error_reporting(0);
			
		$mysqli = new mysqli(
			$this->_options->host, 
			$this->_options->username, 
			$this->_options->password,
			$this->_options->database, 
			$this->_options->port, 
			$this->_options->socket
		);
			
		error_reporting($oldErrorReporting);
		
		if (mysqli_connect_errno()) {
			throw new KDatabaseAdapterException('Connect failed: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error(), mysqli_connect_errno());
		}
		  
		// If supported, request real datatypes from MySQL instead of returning everything as a string.
		if (defined('MYSQLI_OPT_INT_AND_FLOAT_NATIVE')) {
			$mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
		}
 
		$this->_connection = $mysqli;
		$this->_active = true;	
		
		return $this;
 	}
 	
	/**
	 * Disconnect from db
	 * 
	 * @return KDatabaseAdapterMysqli
	 */
	public function disconnect()
	{
		if ($this->active()) 
		{
			$this->_connection->close();
			$this->_connection = null;
			$this->_active = false;
		}
		
		return $this;
	}
 
	/**
	 * Check if the connection is active
	 *
	 * @return boolean
	 */
	public function active() 
	{		
		return is_object($this->_connection) && @$this->_connection->ping();
	}
	
	/**
	 * Retrieves the column schema information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array|false 	An associative array of columns by table
	 */
	public function getTableColumns($tables)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $table)
		{
			if(!isset($this->_table_schema[$table]['columns']))
			{
				if($columns = $this->show( 'SHOW FULL COLUMNS FROM ' . $this->quoteName($this->getTablePrefix().$table), KDatabase::FETCH_OBJECT_LIST))
				{
					foreach($columns as $column) 
					{
						//Set the table name in the raw info (MySQL doesn't add this)
						$column->Table = $table;
					
						//Parse the column raw schema data
        				$column = $this->_parseColumnInfo($column, $table);
        			
              			//Cache the column schame data	
						$this->_table_schema[$table]['columns'][$column->name] = $column;
					}
				} 
				else $this->_table_schema[$table]['columns'] = false;
			}

			//Add the requested table to the result
			if($this->_table_schema[$table]['columns'] !== false) {
				$result[$table] = $this->_table_schema[$table]['columns'];
			}
		}
			
		return $result;
	}

	/**
	 * Retrieves the table schema information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array			An associative array of table information by table
	 */
	public function getTableInfo($tables)
	{
		settype($tables, 'array'); //force to array
		$result = array();
		
		foreach ($tables as $table)
		{
			if(!isset($this->_table_schema[$table]['info']))
			{
				$table = $this->replaceTablePrefix($table);
				if($info  = $this->show( 'SHOW TABLE STATUS LIKE '.$this->quoteValue($this->getTablePrefix().$table), KDatabase::FETCH_OBJECT ))
				{
					//Parse the table raw schema data
        			$info = $this->_parseTableInfo($info);
				
        			//Cache the table schame data
					$this->_table_schema[$table]['info'] = $info;
				}
				else $this->_table_schema[$table]['info'] = false;
			}

			//Add the requested table to the result
			if($this->_table_schema[$table]['info']) {
				$result[$table] = $this->_table_schema[$table]['info'];
			}
		}
	
		return $result;
	}
	
	/**
	 * Retrieves the index information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 			An associative array of indexes by table
	 */
	public function getTableIndexes($tables)
	{
		settype($tables, 'array');
		$result = array();
		
		foreach($tables as $table)
		{
			if(!isset($this->_table_schema[$table]['indexes']))
			{
				if($indexes = $this->show('SHOW INDEX FROM ' . $this->quoteName($this->getTablePrefix().$table), KDatabase::FETCH_OBJECT_LIST))
				{
					foreach ($indexes as $index) {
						$this->_table_schema[$table]['indexes'][$index->Key_name][$index->Seq_in_index] = $index;
					}
				}
				else $this->_table_schema[$table]['indexes'] = false;
			}
			
			if($this->_table_schema[$table]['indexes'] !== false) {
				$result[$table] = $this->_table_schema[$table]['indexes'];
			}
		}
		
		return $result;
	}
	
	/**
	 * Fetch the first field of the first row
	 *
	 * @param	mysqli_result  	The result object. A result set identifier returned by the select() function
	 * @return The value returned in the query or null if the query failed.
	 */
	protected function _fetchField($result)
	{
		$return = null;
		if($row = $result->fetch_row( )) {
			$return = $row[0];
		}
		
		$result->free();
		
		return $return;
	}

	/**
	 * Fetch an array of single field results
	 * 
	 *
	 * @param	mysqli_result  	The result object. A result set identifier returned by the select() function
	 * @param 	string 			The column name of the index to use
	 * @return 	array 			A sequential array of returned rows.
	 */
	protected function _fetchFieldList($result)
	{
		$array = array();
		
		while ($row = $result->fetch_row( )) {
			$array[] = $row[0];
		}
		
		$result->free();
	
		return $array;
	}
	
	/**
     * Fetch the first row of a result set as an associative array
     * 
     * @param 	mysqli_result 	The result object. A result set identifier returned by the select() function
     * @return array
     */
	protected function _fetchArray($result)
	{
		$array = $result->fetch_assoc( );
		$result->free();
		
		return $array;
	}

	/**
	 * Fetch all result rows of a result set as an array of associative arrays
	 * 
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param 	mysqli_result  	The result object. A result set identifier returned by the select() function
	 * @param 	string 			The column name of the index to use
	 * @return 	array 	If key is empty as sequential list of returned records.
	 */
	protected function _fetchArrayList($result, $key = '')
	{
		$array = array();
		while ($row = $result->fetch_assoc( )) 
		{
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		
		$result->free();
		
		return $array;
	}

	/**
	 * Fetch the first row of a result set as an object
	 *
	 * @param	mysqli_result  The result object. A result set identifier returned by the select() function
	 * @param object
	 */
	protected function _fetchObject($result)
	{
		$object = $result->fetch_object( );
		$result->free();
		
		return $object;
	}

	/**
	 * Fetch all rows of a result set as an array of objects
	 * 
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	mysqli_result  The result object. A result set identifier returned by the select() function
	 * @param 	string 		   The column name of the index to use
	 * @return 	array 	If <var>key</var> is empty as sequential array of returned rows.
	 */
	protected function _fetchObjectList($result, $key='')
	{
		$array = array();
		while ($row = $result->fetch_object( )) 
		{
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		
		$result->free();
		
		return $array;
	}
	
	/**
     * Safely quotes a value for an SQL statement.
     * 
     * @param 	mixed 	The value to quote
     * @return string An SQL-safe quoted value
     */
    protected function _quoteValue($value)
    {
        $value =  '\''.mysqli_real_escape_string( $this->_connection, $value ).'\'';	
        return $value;
    }
    
	/**
	 * Parse the raw table schema information
	 *
	 * @param  	object 	The raw table schema information
	 * @return KDatabaseSchemaTable
	 */
	protected function _parseTableInfo($info)
	{		
		$table = new KDatabaseSchemaTable;
 	   	$table->name        = $info->Name;
 	   	$table->engine      = $info->Engine;
 	   	$table->type        = $info->Comment == 'VIEW' ? 'VIEW' : 'BASE';
 	    $table->length      = $info->Data_length;
 	    $table->autoinc     = $info->Auto_increment;
 	    $table->collation   = $info->Collation;
 	    $table->behaviors   = array();
 	    $table->description = $info->Comment != 'VIEW' ? $info->Comment : '';
 	    
 	    return $table;
	}
    
	/**
	 * Parse the raw column schema information
	 *
	 * @param  	object 	The raw column schema information
	 * @return KDatabaseSchemaColumn
	 */
	protected function _parseColumnInfo($info)
	{		
		//Parse the filter information from the comment
		$filter = array();
		preg_match('#@Filter\("(.*)"\)#Ui', $info->Comment, $filter);
		
		list($type, $length, $scope) = $this->_parseColumnType($info->Type);
		
 	   	$column = new KDatabaseSchemaColumn;
 	   	$column->name     = $info->Field;
 	   	$column->type     = $type;
 	   	$column->length   = ($length  ? $length  : null);
 	   	$column->scope    = ($scope ? (int) $scope : null);
 	   	$column->default  = $info->Default;
 	   	$column->required = (bool) ($info->Null != 'YES');
 	    $column->primary  = (bool) ($info->Key == 'PRI');
 	    $column->unique   = (bool) ($info->Key == 'UNI' || $info->Key == 'PRI');
 	    $column->autoinc  = (bool) (strpos($info->Extra, 'auto_increment') !== false);
 	    $column->filter   =  isset($filter[1]) ? explode(',', $filter[1]) : $this->_typemap[$type];
 	    
 	 	// Don't keep "size" for integers
 	    if (substr($type, -3) == 'int') {
 	       	$column->length = null;
 	   	}
 	   		
 	   	// Get the related fields if the column is primary key or part of a unqiue multi column index
 	  	if(!empty($info->Key)) 
 	   	{
 	   		$indexes = $this->getTableIndexes($info->Table);
 	   		
 	   		foreach($indexes[$info->Table] as $index)
			{
				//We only deal with composite-unique indexes
			    if(count($index) > 1 && !$index[1]->Non_unique)
				{
					$fields = array_values($index);
				    
				    if($fields[0]->Column_name == $column->name)
					{
					    unset($fields[0]);
					    		    
				        foreach($fields as $key => $value) {
				            $column->related[] =  $value->Column_name;
					    }
					}
					
					$column->unique = true;	 
					break;
				}
			}
		}
		
 	    return $column;
	}
    
	/**
	 * Given a raw column specification, parse into datatype, length, and decimal scope.
	 *
	 * @param string The column specification; for example,
 	 * "VARCHAR(255)" or "NUMERIC(10,2)" or ENUM('yes','no','maybe')
 	 *
 	 * @return array A sequential array of the column type, size, and scope.
 	 */
	protected function _parseColumnType($spec)
 	{
 	 	$spec    = strtolower($spec);
 	  	$type    = null;
 	   	$length  = null;
 	   	$scope   = null;

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
 	      	
 	      	// there were parens, so there's at least a length
 	       	// remove parens to get the size.
 	      	$length = trim(substr($spec, $pos), '()');
 	      	
 	   		if($type != 'enum' && $type != 'set')
 	     	{
 	     		// A comma in the size indicates a scope.
 	      		$pos = strpos($length, ',');
 	      		if ($pos !== false) {
 	        		$scope = substr($length, $pos + 1);
 	           		$length  = substr($length, 0, $pos);
 	       		}
 	     		
 	     		
 	     	}
 	     	else $length = explode(',', str_replace("'", "", $length));
 	   	}
	 	
 	  	return array($type, $length, $scope);
 	}
}