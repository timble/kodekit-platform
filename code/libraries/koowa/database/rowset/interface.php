<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Rowset Interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @uses 		KMixinClass
 */
interface KDatabaseRowsetInterface extends KObjectIdentifiable
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
	 * Gets the identitiy column of the rowset
	 *
	 * @return string
	 */
	public function getIdentityColumn();

	/**
     * Returns a KDatabaseRow from a known position or based on a key/value pair
     *
     * @param 	string 	The position or the key to search for
     * @param 	mixed  	The value to search for
     * @return KDatabaseRowAbstract
     */
    public function find($key, $value = null);

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
     * Add a row in the rowset
     * 
     * The row will be stored by i'ts identity_column if set or otherwise by
     * it's object handle.
     *
     * @param  object 	A KDatabaseRow object to be inserted
     * @return KDatabaseRowsetAbstract
     */
    public function addRow(KDatabaseRowInterface $row);
    
	/**
     * Removes a row
     * 
     * The row will be removed based on it's identity_column if set or otherwise by
     * it's object handle.
     *
     * @param  object 	A KDatabaseRow object to be removed
     * @return KDatabaseRowsetAbstract
     */
    public function removeRow(KDatabaseRowInterface $row);
}