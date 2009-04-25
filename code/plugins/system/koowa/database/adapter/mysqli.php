<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Mysql Database Adapter
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 */
class KDatabaseAdapterMysqli extends KDatabaseAdapterAbstract
{
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
		return isset($this->_connection) && $this->_connection->query('SELECT 1');
	}
}