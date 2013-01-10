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
 * @uses 		KMixinClass
 */
interface KDatabaseRowsetInterface
{
	/**
     * Returns all data as an array.
     *
     * @param   boolean 	If TRUE, only return the modified data. Default FALSE
     * @return array
     */
    public function getData($modified = false);

	/**
  	 * Set the rowset data based on a named array/hash
  	 *
  	 * @param   mixed 	Either and associative array, a KDatabaseRow object or object
  	 * @param   boolean If TRUE, update the modified information for each column being set.
  	 * 					Default TRUE
 	 * @return 	KDatabaseRowsetAbstract
  	 */
  	 public function setData( $data, $modified = true );

	/**
     * Add rows to the rowset
     *
     * @param  array    An associative array of row data to be inserted.
     * @param  boolean  If TRUE, mark the row(s) as new (i.e. not in the database yet). Default TRUE
     * @return void
     * @see __construct
     */
    public function addData(array $data, $new = true);

	/**
	 * Gets the identitiy column of the rowset
	 *
	 * @return string
	 */
	public function getIdentityColumn();

	/**
     * Returns a KDatabaseRow
     *
     * This functions accepts either a know position or associative
     * array of key/value pairs
     *
     * @param 	string 	The position or the key to search for
     * @param 	mixed  	The value to search for
     * @return KDatabaseRowAbstract
     */
    public function find($needle);

	/**
     * Saves all rows in the rowset to the database
     *
     * @return KDatabaseRowsetAbstract
     */
    public function save();

	/**
     * Deletes all rows in the rowset from the database
     *
     * @return KDatabaseRowsetAbstract
     */
    public function delete();

	/**
     * Reset the rowset
     *
     * @return KDatabaseRowsetAbstract
     */
    public function reset();

	/**
     * Insert a row in the rowset
     *
     * The row will be stored by i'ts identity_column if set or otherwise by
     * it's object handle.
     *
     * @param  object 	A KDatabaseRow object to be inserted
     * @return KDatabaseRowsetAbstract
     */
    public function insert(KDatabaseRowInterface $row);

	/**
     * Removes a row
     *
     * The row will be removed based on it's identity_column if set or otherwise by
     * it's object handle.
     *
     * @param  object 	A KDatabaseRow object to be removed
     * @return KDatabaseRowsetAbstract
     */
    public function extract(KDatabaseRowInterface $row);

    /**
	 * Test the connected status of the rowset.
	 *
	 * @return	bool
	 */
    public function isConnected();
}