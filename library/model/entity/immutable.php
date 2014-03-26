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
 * Immutable Model Entity
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
final class ModelEntityImmutable extends ObjectArray implements ModelEntityInterface
{
    /**
     * The status
     *
     * Available entity status values are defined as STATUS_ constants
     *
     * @var string
     * @see Database
     */
    protected $_status = null;

    /**
     * The status message
     *
     * @var string
     */
    protected $_status_message = '';

    /**
     * The identity key
     *
     * @var string
     */
    protected $_identity_key;

    /**
     * Constructor
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        // Set the table identifier
        if (isset($config->identity_key)) {
            $this->_identity_key = $config->identity_key;
        }

        //Set the status
        if (isset($config->status)) {
            $this->_status = $config->status;
        }

        // Set the entity data
        if (isset($config->data)) {
            $this->_data = $config->data->toArray();
        }

        //Set the status message
        if (!empty($config->status_message)) {
            $this->_status_message = $config->status_message;
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'data'            => array(),
            'status'          => null,
            'status_message'  => '',
            'identity_key'    => null
        ));

        parent::_initialize($config);
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
     * Gets the identity key
     *
     * @return string
     */
    public function getIdentityKey()
    {
        return $this->_identity_key;
    }

    /**
     * Get a property
     *
     * @param   string  $name The property name
     * @return  mixed   The property value.
     */
    public function getProperty($name)
    {
        //Handle computed properties
        if(!$this->hasProperty($name))
        {
            $getter = 'getProperty'.StringInflector::camelize($name);
            if(method_exists($this, $getter)) {
                parent::offsetSet($name, $this->$getter());
            }
        }

        return parent::offsetGet($name);
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
     * Test existence of a property
     *
     * @param  string  $name The property name.
     * @return boolean
     */
    public function hasProperty($name)
    {
        return parent::offsetExists($name);
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
     * Get the properties
     *
     * @param   boolean  $modified If TRUE, only return the modified data.
     * @return  array   An associative array of the entity properties
     */
    public function getProperties($modified = false)
    {
        $properties = $this->_data;

        if ($modified) {
            $properties = array_intersect_key($properties, $this->_modified);
        }

        return $properties;
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
     * Returns the status
     *
     * @return string The status
     */
    public function getStatus()
    {
        return $this->_status;
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
     * Get a handle for this object
     *
     * This function returns an unique identifier for the object. This id can be used as a hash key for storing objects
     * or for identifying an object
     *
     * @return string A string that is unique
     */
    public function getHandle()
    {
        if (isset($this->_identity_key)) {
            $handle = $this->getProperty($this->_identity_key);
        } else {
            $handle = parent::getHandle();
        }

        return $handle;
    }

    /**
     * Checks if the entity is new or not
     *
     * @return bool
     */
    public function isNew()
    {
        return (bool) $this->__new;
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

    /**
     * Set a property
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @return  void
     */
    public function offsetSet($property, $value)
    {
        $this->setProperty($property, $value);
    }

    /**
     * Get a property
     *
     * @param   string  $property   The property name.
     * @return  mixed The property value
     */
    public function offsetGet($property)
    {
        return $this->getProperty($property);
    }

    /**
     * Check if a property exists
     *
     * @param   string  $property   The property name.
     * @return  boolean
     */
    public function offsetExists($property)
    {
        return $this->hasProperty($property);
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  void
     */
    public function offsetUnset($property)
    {
        $this->removeProperty($property);
    }
}