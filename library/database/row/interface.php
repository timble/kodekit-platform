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
 * Database Row Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
interface DatabaseRowInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Set row field value
     *
     * If the value is the same as the current value and the row is loaded from the database the value will not be reset.
     * If the row is new the value will be (re)set and marked as modified
     *
     * @param   string $column The column name.
     * @param   mixed  $value  The value for the property.
     * @return  DatabaseRowInterface
     */
    public function set($column, $value);

    /**
     * Get a row field value
     *
     * @param   string  $column The column name.
     * @return  string  The corresponding value.
     */
    public function get($column);

    /**
     * Test existence of a column
     *
     * @param  string  $column The column name.
     * @return boolean
     */
    public function has($column);

    /**
     * Remove a row field
     *
     * @param   string  $column The column name.
     * @return  DatabaseRowAbstract
     */
    public function remove($column);

    /**
     * Returns an associative array of the raw data
     *
     * @param   boolean  $modified If TRUE, only return the modified data.
     * @return  array
     */
    public function getData($modified = false);

    /**
     * Set the row data
     *
     * @param   mixed   $data       Either and associative array, an object or a DatabaseRow
     * @param   boolean $modified   If TRUE, update the modified information for each column being set.
     * @return  DatabaseRowInterface
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
     * @param   string|null $status The status value or NULL to reset the status
     * @return  DatabaseRowAbstract
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
     * @param   string $message The status message
     * @return  DatabaseRowAbstract
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
     * @return object	If successful returns the row object, otherwise NULL
     */
	public function load();

    /**
     * Saves the row to the database.
     *
     * This performs an intelligent insert/update and reloads the properties
     * with fresh data from the table on success.
     *
     * @return DatabaseRowInterface
     */
    public function save();

    /**
     * Deletes the row form the database.
     *
     * @return DatabaseRowInterface
     */
    public function delete();

    /**
     * Resets to the default properties
     *
     * @return DatabaseRowInterface
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
     * @param   string  $column The column name.
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