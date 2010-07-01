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
 * Database Adapter Interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 */
interface KDatabaseAdapterInterface
{
	/**
	 * Get a database query object
	 *
	 * @return KDatabaseQuery
	 */
	public function getQuery(KConfig $config = null);
	
	/**
	 * Connect to the db
	 * 
	 * @return  KDatabaseAdapterAbstract
	 */
	public function connect();

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @return      boolean
	 */
	public function active();

	/**
	 * Reconnect to the db
	 * 
	 * @return  KDatabaseAdapterAbstract
	 */
	public function reconnect();
	

	/**
	 * Disconnect from db
	 * 
	 * @return  KDatabaseAdapterAbstract
	 */
	public function disconnect();
	
	/**
	 * Get the connection
	 *
	 * Provides access to the underlying database connection. Useful for when
	 * you need to call a proprietary method such as postgresql's lo_* methods
	 *
	 * @return resource
	 */
	public function getConnection();

	/**
	 * Set the connection
	 *
	 * @param 	resource 	The connection resource
	 * @return  KDatabaseAdapterAbstract
	 */
	public function setConnection($resource);

	/**
	 * Get the insert id of the last insert operation
	 *
	 * @return mixed The id of the last inserted row(s)
	 */
 	public function getInsertId();

   /**
	 * Fetch the first field of the first row
	 *
	 * @return scalar The value returned in the query or null if the query failed.
	 */
	public function fetchField($sql);

	/**
	 * Fetch an array of single field results
	 *
	 * @return array
	 */
	public function fetchFieldList($sql);

	/**
     * Fetch the current row as an associative array
     *
     * @param	string  The SQL query. Data inside the query should be properly escaped. 
     * @return array
     */
	public function fetchArray($sql);

	/**
	 * Fetch all result rows as an array of associative arrays
	 *
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string  The SQL query. Data inside the query should be properly escaped. 
	 * @param 	string 	The column name of the index to use
	 * @return 	array 	If key is empty as sequential list of returned records.
	 */
	public function fetchArrayList($sql, $key = '');

	/**
	 * Fetch the current row of a result set as an object
	 *
	 * @param	string  The SQL query. Data inside the query should be properly escaped. 
	 * @param object
	 */
	public function fetchObject($sql);

	/**
	 * Fetch all rows of a result set as an array of objects
	 *
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string  The SQL query. Data inside the query should be properly escaped. 
	 * @param 	string 	The column name of the index to use
	 * @return 	array 	If <var>key</var> is empty as sequential array of returned rows.
	 */
	public function fetchObjectList($sql, $key='' );

	/**
	 * Retrieves the column schema information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 	An associative array of columns by table
	 */
	public function fetchTableColumns($tables);
	
	/**
	 * Retrieves the table schema information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 	An associative array of table information by table
	 */
	public function fetchTableInfo($tables);
	
	/**
	 * Retrieves the index information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 	An associative array of indexes by table
	 */
	public function fetchTableIndexes($tables);
	
	/**
     * Preforms a select query
     *
     * Use for SELECT and anything that returns rows.  
     *
     * @param	string  	A full SQL query to run. Data inside the query should be properly escaped. 
     * @param	integer 	The result maode, either the constant KDatabase::RESULT_USE or KDatabase::RESULT_STORE 
     * 						depending on the desired behavior. By default, KDatabase::RESULT_STORE is used. If you 
     * 						use KDatabase::RESULT_USE all subsequent calls will return error Commands out of sync 
     * 						unless you free the result first. 
     * @return  mixed 		If successfull returns a result object otherwise FALSE
     */
	public function select($sql, $mode = KDatabase::RESULT_STORE);

	/**
     * Inserts a row of data into a table.
     *
     * Automatically quotes the data values
     *
     * @param string  	The table to insert data into.
     * @param array 	An associative array where the key is the colum name and
     * 					the value is the value to insert for that column.
     * @return integer  If successfull the new rows primary key value, false is no row was inserted.
     */
	public function insert($table, array $data);

	/**
     * Updates a table with specified data based on a WHERE clause
     *
     * Automatically quotes the data values
     *
     * @param string 	The table to update
     * @param array  	An associative array where the key is the column name and
     * 				 	the value is the value to use ofr that column.
     * @param mixed 	A sql string or KDatabaseQuery object to limit which rows are updated.
     * @return integer  If successfull the Number of rows affected, otherwise false
     */
	public function update($table, array $data, $where = null);

	/**
     * Deletes rows from the table based on a WHERE clause.
     *
     * @param string The table to update
     * @param mixed  A query string or a KDatabaseQuery object to limit which rows are updated.
     * @return integer Number of rows affected
     */
	public function delete($table, $where);

	/**
	 * Use and other queries that don't return rows
	 *
	 * @param  string 	The query to run. Data inside the query should be properly escaped. 
	 * @param  integer 	The result maode, either the constant KDatabase::RESULT_USE or KDatabase::RESULT_STORE 
     * 					depending on the desired behavior. By default, KDatabase::RESULT_STORE is used. If you 
     * 					use KDatabase::RESULT_USE all subsequent calls will return error Commands out of sync 
     * 					unless you free the result first.
	 * @throws KDatabaseException
	 * @return boolean 	For SELECT, SHOW, DESCRIBE or EXPLAIN will return a result object. 
	 * 					For other successful queries  return TRUE. 
	 */
	public function execute($sql, $mode = KDatabase::RESULT_STORE );

	/**
	 * Set the table prefix
	 *
	 * @param string The table prefix
	 * @return KDatabaseAdapterAbstract
	 */
	public function setTablePrefix($prefix);

 	/**
	 * Get the table prefix
	 *
	 * @return string The table prefix
	 */
	public function getTablePrefix();

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @param 	string 	The SQL query string
	 * @param 	string 	The table prefix to use as a replacement
	 * @param 	string 	The needle to search for in the query string
	 * @return string	The SQL query string
	 */
	public function replaceTablePrefix( $sql, $replace = null, $needle = '#__' );

    /**
     * Safely quotes a value for an SQL statement.
     *
     * If an array is passed as the value, the array values are quoted
     * and then returned as a comma-separated string; this is useful
     * for generating IN() lists.
     *
     * @param 	mixed The value to quote.
     * @return string An SQL-safe quoted value (or a string of separated-
     * 				  and-quoted values).
     */
    public function quoteValue($value);
    
   	/**
     * Quotes a single identifier name (table, table alias, table column,
     * index, sequence).  Ignores empty values.
     * 
     * This function requires all SQL statements, operators and functions to be 
     * uppercased.
     *
     * @param string|array The identifier name to quote.  If an array, quotes 
     *                      each element in the array as an identifier name.
     * @return string|array The quoted identifier name (or array of names).
     */
    public function quoteName($spec);
}