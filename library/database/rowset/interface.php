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
 * Database Rowset Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
interface DatabaseRowsetInterface extends \IteratorAggregate, \ArrayAccess, \Countable, \Serializable
{
    /**
     * Set the value of all the columns
     *
     * @param   string  $column The column name.
     * @param   mixed   $value The value for the property.
     * @return  void
     */
    public function set($column, $value);

    /**
     * Retrieve an array of column values
     *
     * @param   string  $column The column name.
     * @return  array   An array of all the column values
     */
    public function get($column);

    /**
     * Returns all data as an array.
     *
     * @param  boolean $modified If TRUE, only return the modified data. Default FALSE
     * @return array
     */
    public function getData($modified = false);

	/**
  	 * Set the rowset data based on a named array/hash
  	 *
  	 * @param   mixed 	$data     Either and associative array, a DatabaseRow object or object
  	 * @param   boolean $modified If TRUE, update the modified information for each column being set. Default TRUE
 	 * @return 	DatabaseRowsetInterface
  	 */
  	 public function setData( $data, $modified = true );

	/**
     * Add rows to the rowset
     *
     * @param  array   $rows    An associative array of row data to be inserted.
     * @param  string  $status  The row(s) status
     * @return DatabaseRowsetInterface
     * @see __construct
     */
    public function addRow(array $rows, $status = null);

    /**
     * Returns the status message
     *
     * @return string The status message
     */
    public function getStatusMessage();
    
    /**
     * Set the status message
     *
     * @param   string $message The status message
     * @return  DatabaseRowsetAbstract
     */
    public function setStatusMessage($message);
    
	/**
	 * Gets the identity column of the rowset
	 *
	 * @return string
	 */
	public function getIdentityColumn();

	/**
     * Find a row in the rowset based on a needle
     *
     * This functions accepts either a know position or associative array of key/value pairs
     *
     * @param 	string $needle The position or the key to search for
     * @return DatabaseRowInterface
     */
    public function find($needle);

	/**
     * Saves all rows in the rowset to the database
     *
     * @return DatabaseRowsetInterface
     */
    public function save();

	/**
     * Deletes all rows in the rowset from the database
     *
     * @return DatabaseRowsetInterface
     */
    public function delete();

	/**
     * Reset the rowset
     *
     * @return DatabaseRowsetInterface
     */
    public function reset();

	/**
     * Insert a row in the rowset
     *
     * The row will be stored by i'ts identity_column if set or otherwise by it's object handle.
     *
     * @param  DatabaseRowInterface $row A DatabaseRow object to be inserted
     * @return DatabaseRowsetInterface
     */
    public function insert(ObjectHandlable $row);

	/**
     * Removes a row
     *
     * The row will be removed based on it's identity_column if set or otherwise by it's object handle.
     *
     * @param  DatabaseRowInterface $row A DatabaseRow object to be removed
     * @return DatabaseRowsetInterface
     */
    public function extract(ObjectHandlable $row);

    /**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray();

    /**
	 * Test the connected status of the rowset.
	 *
	 * @return	bool
	 */
    public function isConnected();
}