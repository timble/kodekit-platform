<?php
/**
 * @version		$Id$
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Database Row Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Row
 */
interface KDatabaseRowInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Set row field value
     *
     * If the value is the same as the current value and the row is loaded from the database the value will not be reset.
     * If the row is new the value will be (re)set and marked as modified
     *
     * @param   string  The column name.
     * @param   mixed   The value for the property.
     * @return  KDatabaseRowInterface
     */
    public function set($column, $value);

    /**
     * Get row field value
     *
     * @param   string  The column name.
     * @return  KDatabaseRowInterface
     */
    public function get($column);

    /**
     * Returns an associative array of the raw data
     *
     * @param   boolean  If TRUE, only return the modified data. Default FALSE
     * @return  array
     */
    public function getData($modified = false);

    /**
     * Set the row data
     *
     * @param   mixed   Either and associative array, an object or a KDatabaseRow
     * @param   boolean If TRUE, update the modified information for each column being set.
     *                  Default TRUE
     * @return  KDatabaseRowInterface
     */
    public function setData( $data, $modified = true );

    /**
     * Returns the status of this row.
     *
     * @return string The status value.
     */
    public function getStatus();
    
    /**
     * Set the status
     *
     * @param   string|null     The status value or NULL to reset the status
     * @return  KDatabaseRowAbstract
     */
    public function setStatus($status);
    
    /**
     * Returns the status message
     *
     * @return string The status message
     */
    public function getStatusMessage();  
    
    /**
     * Set the status message
     *
     * @param   string      The status message
     * @return  KDatabaseRowAbstract
     */
    public function setStatusMessage($message);

    /**
     * Get a list of columns that have been modified
     *
     * @return array    An array of column names that have been modified
     */
    public function getModified();

	/**
     * Load the row from the database.
     *
     * @return object	If successfull returns the row object, otherwise NULL
     */
	public function load();

    /**
     * Saves the row to the database.
     *
     * This performs an intelligent insert/update and reloads the properties
     * with fresh data from the table on success.
     *
     * @return KDatabaseRowInterface
     */
    public function save();

    /**
     * Deletes the row form the database.
     *
     * @return KDatabaseRowInterface
     */
    public function delete();

    /**
     * Resets to the default properties
     *
     * @return KDatabaseRowInterface
     */
    public function reset();

    /**
     * Checks if the row is new or not
     *
     * @return bool
     */
    public function isNew();

    /**
     * Check if a column has been modified
     *
     * @param   string  The column name.
     * @return  boolean
     */
    public function isModified($column);

	/**
	 * Test the connected status of the row.
	 *
	 * @return	bool
	 */
    public function isConnected();
}