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
 * Model Entity
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
abstract class ModelEntityAbstract extends ObjectArray implements ModelEntityInterface
{
    /**
     * List of modified properties
     *
     * @var array
     */
    protected $_modified = array();

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
     * Tracks if entity data is new
     *
     * @var bool
     */
    private $__new = true;

    /**
     * The identity key
     *
     * @var string
     */
    protected $_identity_column;

    /**
     * Constructor
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        // Set the table identifier
        if (isset($config->identity_column)) {
            $this->_identity_column = $config->identity_column;
        }

        // Reset the entity
        $this->reset();

        //Set the status
        if (isset($config->status)) {
            $this->setStatus($config->status);
        }

        // Set the entity data
        if (isset($config->data)) {
            $this->setProperties($config->data->toArray(), $this->isNew());
        }

        //Set the status message
        if (!empty($config->status_message)) {
            $this->setStatusMessage($config->status_message);
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
            'data'            => null,
            'status'          => null,
            'status_message'  => '',
            'identity_column' => null
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
        if (!$this->isNew()) {
            $this->setStatus(self::STATUS_UPDATED);
        } else {
            $this->setStatus(self::STATUS_CREATED);
        }

        $this->_modified = array();
        return false;
    }

    /**
     * Deletes the entity form the data store
     *
     * @return boolean  If successful return TRUE, otherwise FALSE
     */
    public function delete()
    {
        $this->setStatus(self::STATUS_DELETED);
        return false;
    }

    /**
     * Resets to the default properties
     *
     * @return ModelEntityAbstract
     */
    public function reset()
    {
        $this->_data     = array();
        $this->_modified = array();

        return $this;
    }

    /**
     * Get a property
     *
     * @param   string  $property The property name
     * @return  mixed   The property value.
     */
    public function get($property)
    {
        return parent::offsetGet($property);
    }

    /**
     * Set a property
     *
     * If the value is the same as the current value and the entity is loaded from the data store the value will not be
     * set. If the entity is new the value will be (re)set and marked as modified.
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     *
     * @return  ModelEntityAbstract
     */
    public function set($property, $value, $modified = true)
    {
        if (!array_key_exists($property, $this->_data) || ($this->_data[$property] != $value))
        {
            parent::offsetSet($property, $value);

            if($modified || $this->isNew()) {
                $this->_modified[$property] = $property;
            }
        }

        return $this;
    }

    /**
     * Test existence of a property
     *
     * @param  string  $property The property name.
     * @return boolean
     */
    public function has($property)
    {
        return parent::offsetExists($property);
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  ModelEntityAbstract
     */
    public function remove($property)
    {
        parent::offsetUnset($property);
        unset($this->_modified[$property]);

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
        if ($properties instanceof ModelEntityInterface) {
            $properties = $properties->getProperties(false);
        } else {
            $properties = (array) $properties;
        }

        foreach ($properties as $property => $value) {
            $this->set($property, $value, $modified);
        }

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
        if($status === Database::STATUS_CREATED) {
            $this->__new = false;
        }

        if($status === Database::STATUS_DELETED) {
            $this->__new = true;
        }

        if($status === Database::STATUS_LOADED) {
            $this->__new = false;
        }

        $this->_status = $status;
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
        $this->_status_message = $message;
        return $this;
    }

    /**
     * Gets the identity key
     *
     * @return string
     */
    public function getIdentityColumn()
    {
        return $this->_identity_column;
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
        if (isset($this->_identity_column)) {
            $handle = $this->get($this->_identity_column);
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
        $result = false;

        if($property)
        {
            if (isset($this->_modified[$property]) && $this->_modified[$property]) {
                $result = true;
            }
        }
        else $result = (bool) count($this->_modified);

        return $result;
    }

    /**
     * Test the connected status of the entity
     *
     * @return    boolean    Returns TRUE by default.
     */
    public function isConnected()
    {
        return true;
    }

    /**
     * Set a property
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @return  void
     */
    final public function offsetSet($property, $value)
    {
        $this->set($property, $value);
    }

    /**
     * Get a property
     *
     * @param   string  $property   The property name.
     * @return  mixed The property value
     */
    final public function offsetGet($property)
    {
        return $this->get($property);
    }

    /**
     * Check if a property exists
     *
     * @param   string  $property   The property name.
     * @return  boolean
     */
    final public function offsetExists($property)
    {
        return $this->has($property);
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  void
     */
    final public function offsetUnset($property)
    {
        $this->remove($property);
    }
}