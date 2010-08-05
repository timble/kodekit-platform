<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Mysqli Database Adapter
 *
 * @author		Johan Janssens <johan@koowa.org>
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
	 * Connect to the db
	 *
	 * MySQLi can connect using SSL if $config contains an 'ssl' sub-array
	 * containing the following keys:
	 * + key The path to the key file.
	 * + cert The path to the certificate file.
	 * + ca The path to the certificate authority file.
	 * + capath The path to a directory that contains trusted SSL
	 * CA certificates in pem format.
	 * + cipher The list of allowable ciphers for SSL encryption.
	 *
	 * Example of how to connect using SSL:
	 * <code>
	 * $config = array(
	 *   'adpater'  => 'mysqli'
	 * 	 'username' => 'someuser',
	 * 	'password' => 'apasswd',
	 * 	'hostspec' => 'localhost',
	 * 	'database' => 'thedb',
	 * 	'ssl' => array(
	 * 		'key' => 'client-key.pem',
	 * 		'cert' => 'client-cert.pem',
	 * 		'ca' => 'cacert.pem',
	 * 		'capath' => '/path/to/ca/dir',
	 * 		'cipher' => 'AES',
	 * 	),
	 * );
	 *
	 * $db = KFactory::get('lib.koowa.database', $config)
	 * </code>
	 * 
	 * @return KDatabaseAdapterMysqli
	 */
	 public function connect()
	 {
		if (!empty($this->_options['ssl'])) 
		{
			$mysqli = mysqli_init();
			
			$mysqli->ssl_set(
				empty($this->_options['ssl']['key']) ? null : $this->_options['ssl']['key'],
				empty($this->_options['ssl']['cert']) ? null : $this->_options['ssl']['cert'],
				empty($this->_options['ssl']['ca']) ? null : $this->_options['ssl']['ca'],
				empty($this->_options['ssl']['capath']) ? null : $this->_options['ssl']['capath'],
				empty($this->_options['ssl']['cipher']) ? null : $this->_options['ssl']['cipher']
			);

			$mysqli->real_connect(	
				$this->_options['host'], $this->_options['username'], $this->_options['password'],
				$this->_options['dbname'], $this->_options['port'], $this->_options['socket']
			);	
		} 
		else 
		{
			$oldErrorReporting = error_reporting(0);
			$mysqli = new mysqli(
				$this->_options['host'], $this->_options['username'], $this->_options['password'],
				$this->_options['dbname'], $this->_options['port'], $this->_options['socket']
			);
			
			error_reporting($oldErrorReporting);
		}

		if (mysqli_connect_errno()) {
			throw new KDatabaseAdapterException('Connect failed: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error(), mysqli_connect_errno());
		}
		  
		// If supported, request real datatypes from MySQL instead of returning
		// everything as a string.
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
		if ($this->_connection) 
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
		return isset($this->_connection) && !$this->_connection->ping();
	}
	
	/**
	 * Fetch the first field of the first row
	 *
	 * @return The value returned in the query or null if the query failed.
	 */
	public function fetchField($sql)
	{
		$return = null;
		if ($result = $this->select($sql, KDatabase::RESULT_USE)) 
		{
			if($row = $result->fetch_row( )) {
				$return = $row[0];
			}
			$result->free();
		}
		
		return $return;
	}

	/**
	 * Fetch an array of single field results
	 *
	 * @return array
	 */
	public function fetchFieldList($sql)
	{
		$array = array();
		if ($result = $this->select($sql, KDatabase::RESULT_USE))
		{
			while ($row = $result->fetch_row( )) {
				$array[] = $row[0];
			}
			
			$result->free();
		}
	
		return $array;
	}
	
	/**
     * Fetch the first row of a result set as an associative array
     * 
     * @param	string  The SQL query
     * @return array
     */
	public function fetchArray($sql)
	{
		$array = array();
		if ($result = $this->select($sql, KDatabase::RESULT_USE)) 
		{
			$array = $result->fetch_assoc( );
			$result->free();
		}
		
		return $array;
	}

	/**
	 * Fetch all result rows of a result set as an array of associative arrays
	 * 
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string  The SQL query
	 * @param 	string 	The column name of the index to use
	 * @return 	array 	If key is empty as sequential list of returned records.
	 */
	public function fetchArrayList($sql, $key = '')
	{
		$array = array();
		if ($result = $this->select($sql, KDatabase::RESULT_USE))
		{
			while ($row = $result->fetch_assoc( )) 
			{
				if ($key) {
					$array[$row[$key]] = $row;
				} else {
					$array[] = $row;
				}
			}
			
			$result->free();
		}
		
		return $array;
	}

	/**
	 * Fetch the first row of a result set as an object
	 *
	 * @param	string  The SQL query
	 * @param object
	 */
	public function fetchObject($sql)
	{
		$object = null;
		if ($result = $this->select($sql, KDatabase::RESULT_USE)) 
		{
			$object = $result->fetch_object( );
			$result->free();
		}
		
		return $object;
	}

	/**
	 * Fetch all rows of a result set as an array of objects
	 * 
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string  The SQL query
	 * @param 	string 	The column name of the index to use
	 * @return 	array 	If <var>key</var> is empty as sequential array of returned rows.
	 */
	public function fetchObjectList($sql, $key='')
	{
		$array = array();
		if ($result = $this->select($sql, KDatabase::RESULT_USE))
		{
			while ($row = $result->fetch_object( )) 
			{
				if ($key) {
					$array[$row->$key] = $row;
				} else {
					$array[] = $row;
				}
			}
			
			$result->free();
		}
	
		return $array;
	}
	
	/**
	 * Retrieves the column schema information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 	An associative array of columns by table
	 */
	public function fetchTableColumns($tables)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval)
		{
			$table = $tblval;
			
			//Check the table if it already has a table prefix applied.
			if(substr($tblval, 0, 3) != '#__') {
				$table = '#__'.$tblval;
			}
			else $tblval = $this->replaceTablePrefix($tblval, '');

			if(!isset($this->_table_schema[$tblval]['columns']))
			{
				$columns = $this->fetchObjectList( 'SHOW FULL COLUMNS FROM ' . $this->quoteName($table));
				foreach ($columns as $column) 
				{
					//Set the table name in the raw info (MySQL doesn't add this)
					$column->Table = $tblval;
					
					//Parse the column raw schema data
        			$column = $this->_parseColumnInfo($column, $table);
        			
              		//Cache the column schame data	
					$this->_table_schema[$tblval]['columns'][$column->name] = $column;
				}
			}

			//Add the requested table to the result
			$result[$tblval] = $this->_table_schema[$tblval]['columns'];
		}
			
		return $result;
	}

	/**
	 * Retrieves the table schema information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 	An associative array of table information by table
	 */
	public function fetchTableInfo($tables)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval)
		{
			$table = $tblval;
			
			//Check the table if it already has a table prefix applied.
			if(substr($tblval, 0, 3) != '#__') {
				$table = '#__'.$tblval;
			}
			else $tblval = $this->replaceTablePrefix($tblval, '');

			if(!isset($this->_table_schema[$tblval]['info']))
			{
				$table = $this->replaceTablePrefix($table);
				$info  = $this->fetchObject( 'SHOW TABLE STATUS LIKE '.$this->quoteValue($table));
				
				//Parse the table raw schema data
        		$table = $this->_parseTableInfo($info);
				
        		//Cache the table schame data
				$this->_table_schema[$tblval]['info'] = $table;
			}

			//Add the requested table to the result
			$result[$tblval] = $this->_table_schema[$tblval]['info'];
		}
	
		return $result;
	}
	
	/**
	 * Retrieves the index information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 	An associative array of indexes by table
	 */
	public function fetchTableIndexes($tables)
	{
		settype($tables, 'array');
		$result = array();
		
		foreach($tables as $tblval)
		{
			$table = $tblval;
			
			//Check the table if it already has a table prefix applied.
			if(substr($tblval, 0, 3) != '#__') {
				$table = '#__'.$tblval;
			}
			else $tblval = $this->replaceTablePrefix($tblval, '');
			
			if(!isset($this->_table_schema[$tblval]['indexes']))
			{
				$indexes = $this->fetchObjectList('SHOW INDEX FROM ' . $this->quoteName($table));
				
				foreach($indexes as $index) {
					$this->_table_schema[$tblval]['indexes'][$index->Key_name][$index->Seq_in_index] = $index;
				}
			}
			
			$result[$tblval] = $this->_table_schema[$tblval]['indexes'];
		}
		
		return $result;
	}
	
	/**
     * Safely quotes a value for an SQL statement.
     * 
     * @param 	mixed 	The value to quote
     * @return string An SQL-safe quoted value
     */
    public function _quoteValue($value)
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
 	   		
 	   	// Get the related fields if the column is part of a unqiue multi column index
 	  	if($info->Key == 'MUL') 
 	   	{
 	   		$indexes = $this->fetchTableIndexes($info->Table);
 	   		
 	   		foreach($indexes[$info->Table] as $index)
			{
				if(count($index) > 1)
				{
					//We only deal with unique indexes for now.
					if($index[1]->Column_name == $column->name && !$index[1]->Non_unique) 
					{
						array_shift($index); //remove the first column of the index
						
						foreach($index as $key => $value) {
							$column->related[] = $index[$key]->Column_name;
						}
						
						$column->unique = true;	
						break; 
					}
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