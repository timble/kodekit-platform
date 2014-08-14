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
 * Immutable Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Model
 */
final class ModelEntityImmutable extends ModelEntityAbstract
{
    /**
     * Constructor
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        ObjectArray::__construct($config);

        $this->_identity_key = $config->identity_key;

        //Set the status
        if (isset($config->status)) {
            $this->_status = $config->status;
        }

        // Set the entity data
        if (isset($config->data)) {
            $this->_data = $config->data->toArray();
        } else {
            $this->_data = array();
        }

        //Set the status message
        if (!empty($config->status_message)) {
            $this->_status_message = $config->status_message;
        }
    }

    /**
     * Saves the entity to the data store
     *
     * @return boolean  If successful return TRUE, otherwise FALSE
     */
    public function save()
    {
        return false;
    }

    /**
     * Deletes the entity form the data store
     *
     * @return boolean  If successful return TRUE, otherwise FALSE
     */
    public function delete()
    {
        return false;
    }

    /**
     * Resets to the default properties
     *
     * @return ModelEntityAbstract
     */
    public function reset()
    {
        return $this;
    }

    /**
     * Set a property
     *
     * If the value is the same as the current value and the entity is loaded from the data store the value will not be
     * set. If the entity is new the value will be (re)set and marked as modified.
     *
     * @param   string  $name       The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     *
     * @return  ModelEntityAbstract
     */
    public function setProperty($name, $value, $modified = true)
    {
        return $this;
    }

    /**
     * Remove a property
     *
     * @param   string  $name The property name.
     * @return  ModelEntityAbstract
     */
    public function removeProperty($name)
    {
        return $this;
    }

    /**
     * Set the properties
     *
     * @param   mixed   $data        Either and associative array, an object or a ModelEntityInterface
     * @param   boolean $modified If TRUE, update the modified information for each property being set.
     * @return  ModelEntityAbstract
     */
    public function setProperties($properties, $modified = true)
    {
        return $this;
    }

    /**
     * Set the status
     *
     * @param   string|null  $status The status value or NULL to reset the status
     * @return  ModelEntityAbstract
     */
    public function setStatus($status)
    {
        return $this;
    }

    /**
     * Returns the status message
     *
     * @return string The status message
     */
    public function getStatusMessage()
    {
        return $this->_status_message;
    }

    /**
     * Set the status message
     *
     * @param   string $message The status message
     * @return  ModelEntityAbstract
     */
    public function setStatusMessage($message)
    {
        return $this;
    }

    /**
     * Check if a the entity or specific entity property has been modified.
     *
     * If a specific property name is giving method will return TRUE only if this property was modified.
     *
     * @param   string $property The property name
     * @return  boolean
     */
    public function isModified($property = null)
    {
        return false;
    }

    /**
     * Test if the entity is connected to a data store
     *
     * @return	bool
     */
    public function isConnected()
    {
        return false;
    }
}