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
 * Model Entity Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
interface ModelEntityInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Entity States
     */
    const STATUS_LOADED   = 'loaded';
    const STATUS_DELETED  = 'deleted';
    const STATUS_CREATED  = 'created';
    const STATUS_UPDATED  = 'updated';
    const STATUS_FAILED   = 'failed';

    /**
     * Saves the to the data store
     *
     * This performs an intelligent insert/update and reloads the properties
     * with fresh data from the table on success.
     *
     * @return ModelEntityInterface
     */
    public function save();

    /**
     * Deletes the entity form the data store
     *
     * @return ModelEntityInterface
     */
    public function delete();

    /**
     * Resets to the entity to it's default properties
     *
     * @return ModelEntityInterface
     */
    public function reset();

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
     * If the value is the same as the current value and the entity is loaded from the data store the value will not be
     * reset. If the entity is new the value will be (re)set and marked as modified
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     *
     * @return  ModelEntityInterface
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
     * @return  ModelEntityInterface
     */
    public function remove($property);

    /**
     * Get the entity key
     *
     * @return string
     */
    public function getIdentityKey();

    /**
     * Get the properties
     *
     * @param   boolean  $modified If TRUE, only return the modified data.
     * @return  array   An associative array of entity properties
     */
    public function getProperties($modified = false);

    /**
     * Set the properties
     *
     * @param   mixed   $properties  Either and associative array, an object or a ModelEntityInterface
     * @param   boolean $modified    If TRUE, update the modified information for each column being set.
     * @return  ModelEntityInterface
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
     * @return  ModelEntityInterface
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
     * @return  ModelEntityInterface
     */
    public function setStatusMessage($message);

    /**
     * Checks if the entity is new or not
     *
     * @return bool
     */
    public function isNew();

    /**
     * Check if the entity or specific entity property has been modified.
     *
     * If a specific property name is giving method will return TRUE only if this property was modified.
     *
     * @param   string $property The property name
     * @return  boolean
     */
    public function isModified($property = null);

	/**
	 * Test the connected status of the entity
	 *
	 * @return	bool
	 */
    public function isConnected();
}