<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
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
 	 *  Note that fetchTableFields() will programmatically convert TINYINT(1) to
     * 'bool' independent of this map.
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
 		'set'				=> 'raw',
 		'enum'				=> 'raw',
 	
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
	 * $db = new KDatabaseAdapterMysqli($config);
	 * </code>
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
				$this->_options['host'], $config['username'], $config['password'],
				$config['dbname'], $config['port'], $config['socket']
			);	
		} else {
			$oldErrorReporting = error_reporting(0);
			$mysqli = new mysqli(
				$config['host'], $config['username'], $config['password'],
				$config['dbname'], $config['port'], $config['socket']
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
 	}
 	
	/**
	 * Disconnect from db
	 */
	public function disconnect()
	{
		if ($this->_connection) 
		{
			$this->_connection->close();
			$this->_connection = null;
			$this->_active = false;
		}
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
	public function fetchResult($sql)
	{
		$return = null;
		if ($result = $this->select($sql)) 
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
	public function fetchResultList($sql)
	{
		$array = array();
		if ($result = $this->select($sql))
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
	public function fetchAssoc($sql)
	{
		$array = array();
		if ($result = $this->select($sql)) 
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
	public function fetchAssocList($sql, $key = '')
	{
		$array = array();
		if ($result = $this->select($sql))
		{
			while ($row = $result->fetch_assoc( )) 
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
	 * Fetch the first row of a result set as an object
	 *
	 * @param	string  The SQL query
	 * @param object
	 */
	public function fetchObject($sql)
	{
		$object = null;
		if ($result = $this->select($sql)) 
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
		if ($result = $this->select($sql))
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
     * Safely quotes a value for an SQL statement.
     * 
     * @param 	mixed 	The value to quote
     * @return string An SQL-safe quoted value
     */
    public function _quoteString($value)
    {
        if(!is_numeric($value)) {
        	$value =  '\''.mysqli_real_escape_string( $this->_connection, $value ).'\'';
        }
        	
        return $value;
    }
    
	/**
	 * Gets the fields for the table
	 *
	 * @param  	object 	The raw field data
	 * @return object
	 */
	public function parseField($field)
	{	
		$name = $field->Field;

 	   	// override $type to find tinyint(1) as boolean
 	    if (strtolower($field->Type) == 'tinyint(1)') {
 	      	$type 	= 'boolean';
 	       	$size 	= null;
 	        $scope 	= null;
 	    } else {
 	    	list($type, $size, $scope) = $this->_parseFieldType($field->Type);
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

 	    return $description;
	}
    
	/**
	 * Given a column specification, parse into datatype, size, and decimal 
	 * scope.
	 *
	 * @param string $spec The column specification; for example,
 	 * "VARCHAR(255)" or "NUMERIC(10,2)".
 	 *
 	 * @return array A sequential array of the column type, size, and scope.
 	 */
	protected function _parseFieldType($spec)
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