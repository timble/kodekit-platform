<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
	* Returns the first field of the first row
	*
	* @return The value returned in the query or null if the query failed.
	*/
	public function selectResult($sql)
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
	 * Returns an array of single field results
	 *
	 * @return array
	 */
	public function selectResultList($sql)
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
     * Returns the first row of a result set as an associative array
     * 
     * @param	string  The SQL query
     * @return array
     */
	public function selectAssoc($sql)
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
	 * Returns all result rows of a result set as an array of associative arrays
	 * 
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string  The SQL query
	 * @param 	string 	The column name of the index to use
	 * @return 	array 	If key is empty as sequential list of returned records.
	 */
	public function selectAssocList($sql, $key = '')
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
	 * Returns the first row of a result set as an object
	 *
	 * @param	string  The SQL query
	 * @param object
	 */
	public function selectObject($sql)
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
	public function selectObjectList($sql, $key='')
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
}