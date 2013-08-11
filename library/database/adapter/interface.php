<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database Adapter Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
interface DatabaseAdapterInterface
{
	/**
	 * Connect to the db
	 * 
	 * @return  DatabaseAdapterAbstract
	 */
	public function connect();

	/**
	 * Reconnect to the db
	 * 
	 * @return  DatabaseAdapterAbstract
	 */
	public function reconnect();

	/**
	 * Disconnect from db
	 * 
	 * @return  DatabaseAdapterAbstract
	 */
	public function disconnect();

    /**
     * Turns off autocommit mode
     *
     * @return  boolean  Returns TRUE on success or FALSE on failure.
     */
    public function begin();

    /**
     * Commits a transaction
     *
     * @return  boolean  Returns TRUE on success or FALSE on failure.
     */
    public function commit();

    /**
     * Rolls back a transaction
     *
     * @return  boolean  Returns TRUE on success or FALSE on failure.
     */
    public function rollback();

    /**
     * Lock a table
     *
     * @param   string $table  The name of the table.
     * @return  boolean  TRUE on success, FALSE otherwise.
     */
    public function lock($table);

    /**
     * Unlock tables
     *
     * @return  boolean  TRUE on success, FALSE otherwise.
     */
    public function unlock();

	/**
     * Preform a select query.
     * 
     * @param	string  	A full SQL query to run. Data inside the query should be properly escaped. 
     * @param	integer 	The result maode, either the constant Database::RESULT_USE or Database::RESULT_STORE
     * 						depending on the desired behavior. By default, Database::RESULT_STORE is used. If you
     * 						use Database::RESULT_USE all subsequent calls will return error Commands out of sync
     * 						unless you free the result first.
     * @param 	string 		The column name of the index to use.
     * @return  mixed 		If successfull returns a result object otherwise FALSE
     */
	public function select(DatabaseQueryInterface $query, $mode = Database::RESULT_STORE, $key = '');

	/**
     * Insert a row of data into a table.
     *
     * @param DatabaseQueryInsert The query object.
     * @return bool|integer  If the insert query was executed returns the number of rows updated, or 0 if 
     * 					     no rows where updated, or -1 if an error occurred. Otherwise FALSE.
     */
	public function insert(DatabaseQueryInsert $query);

	/**
     * Update a table with specified data.
     *
     * @param  DatabaseQueryUpdate The query object.
     * @return integer  If the update query was executed returns the number of rows updated, or 0 if 
     * 					no rows where updated, or -1 if an error occurred. Otherwise FALSE. 
     */
	public function update(DatabaseQueryUpdate $query);

	/**
     * Delete rows from the table.
     *
     * @param  DatabaseQueryDelete The query object.
     * @return integer 	Number of rows affected, or -1 if an error occured.
     */
	public function delete(DatabaseQueryDelete $query);

	/**
	 * Use and other queries that don't return rows
	 *
	 * @param  string 	The query to run. Data inside the query should be properly escaped. 
	 * @param  integer 	The result made, either the constant Database::RESULT_USE or Database::RESULT_STORE
     * 					depending on the desired behavior. By default, Database::RESULT_STORE is used. If you
     * 					use Database::RESULT_USE all subsequent calls will return error Commands out of sync
     * 					unless you free the result first.
	 * @throws \RuntimeException If the query could not be executed
	 * @return boolean 	For SELECT, SHOW, DESCRIBE or EXPLAIN will return a result object. 
	 * 					For other successful queries  return TRUE. 
	 */
	public function execute($sql, $mode = Database::RESULT_STORE );

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
     * @return  DatabaseAdapterAbstract
     */
    public function setConnection($resource);

    /**
     * Get the insert id of the last insert operation
     *
     * @return mixed The id of the last inserted row(s)
     */
    public function getInsertId();

    /**
     * Retrieves the column schema information about the given table
     *
     * @param 	string 	A table name
     * @return	DatabaseSchemaTable
     */
    public function getTableSchema($table);

    /**
     * Safely quotes a value for an SQL statement.
     *
     * If an array is passed as the value, the array values are quoted
     * and then returned as a comma-separated string; this is useful
     * for generating IN() lists.
     *
     * @param   mixed The value to quote.
     * @return string An SQL-safe quoted value (or a string of separated-
     *                and-quoted values).
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
    public function quoteIdentifier($spec);

    /**
     * Determines if the connection to the server is active.
     *
     * @return      boolean
     */
    public function isConnected();

    /**
     * Checks if inside a transaction
     *
     * @return  boolean  Returns TRUE if a transaction is currently active, and FALSE if not.
     */
    public function inTransaction();
}