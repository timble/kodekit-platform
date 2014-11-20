<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database Row Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Database
 */
interface DatabaseRowInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Saves the to the database.
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
     * Resets to the row to it's default properties
     *
     * @return DatabaseRowInterface
     */
    public function reset();

    /**
     * Gets the identity column
     *
     * @return string
     */
    public function getIdentityColumn();

    /**
     * Get a property
     *
     * @param   string  $name The property name.
     * @return  mixed   The property value.
     */
    public function getProperty($name);

    /**
     * Set a property
     *
     * If the value is the same as the current value and the row is loaded from the database the value will not be reset.
     * If the row is new the value will be (re)set and marked as modified
     *
     * @param   string  $name       The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     * @return  DatabaseRowInterface
     */
    public function setProperty($name, $value, $modified = true);

    /**
     * Test existence of a property
     *
     * @param  string  $property The property name.
     * @return boolean
     */
    public function hasProperty($name);

    /**
     * Remove a property
     *
     * @param   string  $name The property name.
     * @return  DatabaseRowInterface
     */
    public function removeProperty($name);

    /**
     * Get the properties
     *
     * @param   boolean  $modified If TRUE, only return the modified data.
     * @return  array   An associative array of the row properties
     */
    public function getProperties($modified = false);

    /**
     * Set the properties
     *
     * @param   mixed   $properties  Either and associative array, an object or a DatabaseRow
     * @param   boolean $modified    If TRUE, update the modified information for each column being set.
     * @return  DatabaseRowInterface
     */
    public function setProperties($properties, $modified = true);

    /**
     * Get a list of the computed properties
     *
     * @return array An array
     */
    public function getComputedProperties();

    /**
     * Returns the status.
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
     * @return  DatabaseRowInterface
     */
    public function setStatusMessage($message);

    /**
     * Method to get a table object
     *
     * Function catches DatabaseTableExceptions that are thrown for tables that
     * don't exist. If no table object can be created the function will return FALSE.
     *
     * @return DatabaseTableAbstract
     */
    public function getTable();

    /**
     * Method to set a table object attached to the rowset
     *
     * @param    mixed    $table An object that implements ObjectInterface, ObjectIdentifier object
     *                           or valid identifier string
     * @throws  \UnexpectedValueException    If the identifier is not a table identifier
     * @return  DatabaseRowInterface
     */
    public function setTable($table);

    /**
     * Checks if the row is new or not
     *
     * @return bool
     */
    public function isNew();

    /**
     * Check if a the row or specific row property has been modified.
     *
     * If a specific property name is giving method will return TRUE only if this property was modified.
     *
     * @param   string $property The property name
     * @return  boolean
     */
    public function isModified($property = null);

	/**
	 * Test the connected status of the row.
	 *
	 * @return	bool
	 */
    public function isConnected();
}