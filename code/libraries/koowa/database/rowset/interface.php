<?php
/**
 * @version		$Id$
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Database Rowset Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Rowset
 */
interface KDatabaseRowsetInterface
{
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
  	 * @param   mixed 	$data     Either and associative array, a KDatabaseRow object or object
  	 * @param   boolean $modified If TRUE, update the modified information for each column being set. Default TRUE
 	 * @return 	\KDatabaseRowsetInterface
  	 */
  	 public function setData( $data, $modified = true );

	/**
     * Add rows to the rowset
     *
     * @param  array   $data An associative array of row data to be inserted.
     * @param  boolean $new  If TRUE, mark the row(s) as new (i.e. not in the database yet). Default TRUE
     * @return void
     * @see __construct
     */
    public function addData(array $data, $new = true);

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
     * @return  KDatabaseRowsetAbstract
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
     * @return \KDatabaseRowInterface
     */
    public function find($needle);

	/**
     * Saves all rows in the rowset to the database
     *
     * @return \KDatabaseRowsetInterface
     */
    public function save();

	/**
     * Deletes all rows in the rowset from the database
     *
     * @return \KDatabaseRowsetInterface
     */
    public function delete();

	/**
     * Reset the rowset
     *
     * @return \KDatabaseRowsetInterface
     */
    public function reset();

	/**
     * Insert a row in the rowset
     *
     * The row will be stored by i'ts identity_column if set or otherwise by
     * it's object handle.
     *
     * @param  KDatabaseRowInterface $row A KDatabaseRow object to be inserted
     * @return \KDatabaseRowsetInterface
     */
    public function insert(KObjectHandlable $row);

	/**
     * Removes a row
     *
     * The row will be removed based on it's identity_column if set or otherwise by
     * it's object handle.
     *
     * @param  KDatabaseRowInterface $row A KDatabaseRow object to be removed
     * @return \KDatabaseRowsetInterface
     */
    public function extract(KObjectHandlable $row);

    /**
	 * Test the connected status of the rowset.
	 *
	 * @return	bool
	 */
    public function isConnected();
}