<?php
/**
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Database Row Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Row
 */
interface DatabaseRowInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Get a property
     *
     * @param   string  $property The property name.
     * @return  mixed   The property value.
     */
    public function get($property);

    /**
     * Set a property
     *
     * If the value is the same as the current value and the row is loaded from the database the value will not be reset.
     * If the row is new the value will be (re)set and marked as modified
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     * @return  DatabaseRowInterface
     */
    public function set($property, $value, $modified = true);

    /**
     * Test existence of a property
     *
     * @param  string  $property The property name.
     * @return boolean
     */
    public function has($property);

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  DatabaseRowInterface
     */
    public function remove($property);

    /**
     * Gets the identity key
     *
     * @return string
     */
    public function getIdentityColumn();

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
     * @return  DatabaseRowAbstract
     */
    public function setProperties($properties, $modified = true);

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
     * @return  DatabaseRowAbstract
     */
    public function setStatusMessage($message);

    /**
     * Get a list of properties that have been modified
     *
     * @return array An array of property names that have been modified
     */
    public function getModified();

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