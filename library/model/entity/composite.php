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
 * Model Entity Composite
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Model
 */
class ModelEntityComposite extends ObjectSet implements ModelEntityInterface, ModelEntityComposable
{
    /**
     * Name of the identity key in the collection
     *
     * @var    string
     */
    protected $_identity_key;

    /**
     * Clone entity object
     *
     * @var    boolean
     */
    protected $_prototypable;

    /**
     * The entity prototype
     *
     * @var  ModelEntityInterface
     */
    protected $_prototype;

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  An optional ObjectConfig object with configuration options
     * @return ModelEntityComposite
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_prototypable = $config->prototypable;
        $this->_identity_key = $config->identity_key;

        // Reset the collection
        $this->reset();

        // Insert the data, if exists
        if (!empty($config->data))
        {
            foreach($config->data->toArray() as $properties) {
                $this->create($properties, $config->status);
            }
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'data'         => null,
            'identity_key' => null,
            'prototypable' => true
        ));

        parent::_initialize($config);
    }

    /**
     * Insert an entity into the collection
     *
     * The entity will be stored by it's identity_key if set or otherwise by it's object handle.
     *
     * @param  ModelEntityInterface $entity
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntity
     * @return boolean    TRUE on success FALSE on failure
     */
    public function insert(ObjectHandlable $entity)
    {
        if (!$entity instanceof ModelEntityInterface) {
            throw new \InvalidArgumentException('Entity needs to implement ModelEntityInterface');
        }

        $this->offsetSet($entity);

        return true;
    }

    /**
     * Removes an entity from the collection
     *
     * The entity will be removed based on it's identity_key if set or otherwise by it's object handle.
     *
     * @param  ModelEntityInterface $entity
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntityInterface
     * @return ModelEntityComposite
     */
    public function remove(ObjectHandlable $entity)
    {
        if (!$entity instanceof ModelEntityInterface) {
            throw new \InvalidArgumentException('Entity needs to implement ModelEntityInterface');
        }

        return parent::remove($entity);
    }

    /**
     * Checks if the collection contains a specific entity
     *
     * @param   ModelEntityInterface $entity
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntityInterface
     * @return  bool Returns TRUE if the object is in the set, FALSE otherwise
     */
    public function contains(ObjectHandlable $entity)
    {
        if (!$entity instanceof ModelEntityInterface) {
            throw new \InvalidArgumentException('Entity needs to implement ModelEntityInterface');
        }

        return parent::contains($entity);
    }

    /**
     * Create a new entity and insert it
     *
     * This function will either clone the entity object, or create a new instance of the entity object for each entity
     * being inserted. By default the entity will be cloned.
     *
     * @param   array   $properties The entity properties
     * @param   string  $status     The entity status
     * @return  ModelEntityComposite
     */
    public function create(array $properties = array(), $status = null)
    {
        if($this->_prototypable)
        {
            if(!$this->_prototype instanceof ModelEntityInterface)
            {
                $identifier = $this->getIdentifier()->toArray();
                $identifier['path'] = array('model', 'entity');
                $identifier['name'] = StringInflector::singularize($this->getIdentifier()->name);

                //The entity default options
                $options = array(
                    'identity_key' => $this->getIdentityKey()
                );

                $this->_prototype = $this->getObject($identifier, $options);
            }

            $entity = clone $this->_prototype;

            $entity->setStatus($status);
            $entity->setProperties($properties, $entity->isNew());
        }
        else
        {
            $identifier = $this->getIdentifier()->toArray();
            $identifier['path'] = array('model', 'entity');
            $identifier['name'] = StringInflector::singularize($this->getIdentifier()->name);

            //The entity default options
            $options = array(
                'data'         => $properties,
                'status'       => $status,
                'identity_key' => $this->getIdentityKey()
            );

            $entity = $this->getObject($identifier, $options);
        }

        //Insert the entity into the collection
        $this->insert($entity);

        return $entity;
    }

    /**
     * Find an entity in the collection based on a needle
     *
     * This functions accepts either a know position or associative array of property/value pairs
     *
     * @param   string|array  $needle The position or the key or an associative array of column data to match
     * @return  ModelEntityComposite Returns a collection if successful. Otherwise NULL.
     */
    public function find($needle)
    {
        $result = null;

        if(is_array($needle))
        {
            $result = clone $this;

            foreach($this as $entity)
            {
                foreach($needle as $key => $value)
                {
                    if(!in_array($entity->{$key}, (array) $value)) {
                        $result->remove($entity);
                    }
                }
            }
        }

        if(is_scalar($needle) && isset($this->_data[$needle])) {
            $result = $this->_data[$needle];
        }

        return $result;
    }

    /**
     * Store all entities in the collection to the data store
     *
     * @return boolean  If successful return TRUE, otherwise FALSE
     */
    public function save()
    {
        $result = false;

        if (count($this))
        {
            $result = true;

            foreach ($this as $i => $entity)
            {
                if (!$entity->save())
                {
                    // Set current entity status message as collection status message.
                    $this->setStatusMessage($entity->getStatusMessage());
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * Remove all entities in the collection from the data store
     *
     * @return bool  If successful return TRUE, otherwise FALSE
     */
    public function delete()
    {
        $result = false;

        if (count($this))
        {
            $result = true;

            foreach ($this as $i => $entity)
            {
                if (!$entity->delete())
                {
                    // Set current entity status message as collection status message.
                    $this->setStatusMessage($entity->getStatusMessage());
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * Reset the collection
     *
     * @return  ModelEntityComposite
     */
    public function reset()
    {
        $this->_data = array();
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
     * @param   string  $name The property name.
     * @return  mixed
     */
    public function getProperty($name)
    {
        $result = null;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->getProperty($name);
        }

        return $result;
    }

    /**
     * Set a property
     *
     * @param   string  $name       The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     * @return  ModelEntityComposite
     */
    public function setProperty($name, $value, $modified = true)
    {
        if($entity = $this->getIterator()->current()) {
            $entity->setProperty($name, $value, $modified);
        }

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
        $result = false;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->hasProperty($name);
        }

        return $result;
    }

    /**
     * Remove a property
     *
     * @param   string  $name The property name.
     * @return  ModelEntityComposite
     */
    public function removeProperty($name)
    {
        if($entity = $this->getIterator()->current()) {
            $entity->removeProperty($name);
        }

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
        $result = array();

        if($entity = $this->getIterator()->current()) {
            $result = $entity->getProperties($modified);
        }

        return $result;
    }

    /**
     * Set the properties
     *
     * @param   mixed   $data        Either and associative array, an object or a ModelEntityInterface
     * @param   boolean $modified If TRUE, update the modified information for each column being set.
     * @return  ModelEntityComposite
     */
    public function setProperties($properties, $modified = true)
    {
        //Prevent changing the identity key
        if (isset($this->_identity_key)) {
            unset($properties[$this->_identity_key]);
        }

        if($entity = $this->getIterator()->current()) {
            $entity->setProperties($properties, $modified);
        }

        return $this;
    }

    /**
     * Get a list of the computed properties
     *
     * @return array An array
     */
    public function getComputedProperties()
    {
        $result = array();

        if($entity = $this->getIterator()->current()) {
            $result = $entity->getComputedProperties();
        }

        return $result;
    }

    /**
     * Returns the status
     *
     * @return string The status
     */
    public function getStatus()
    {
        $status = null;

        if($entity = $this->getIterator()->current()) {
            $status = $entity->getStatus();
        }

        return $status;
    }

    /**
     * Set the status
     *
     * @param   string|null  $status The status value or NULL to reset the status
     * @return  ModelEntityComposite
     */
    public function setStatus($status)
    {
        if($entity = $this->getIterator()->current()) {
            $entity->setStatusMessage($status);
        }

        return $this;
    }

    /**
     * Returns the status message
     *
     * @return string The status message
     */
    public function getStatusMessage()
    {
        $message = false;

        if($entity = $this->getIterator()->current()) {
            $message = $entity->getStatusMessage($message);
        }

        return $message;
    }

    /**
     * Set the status message
     *
     * @param   string $message The status message
     * @return  ModelEntityComposite
     */
    public function setStatusMessage($message)
    {
        if($entity = $this->getIterator()->current()) {
            $entity->setStatusMessage($message);
        }

        return $this;
    }

    /**
     * Checks if the current entity is new or not
     *
     * @return boolean
     */
    public function isNew()
    {
        $result = true;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->isNew();
        }

        return $result;
    }

    /**
     * Check if a the current entity or specific entity property has been modified.
     *
     * If a specific property name is giving method will return TRUE only if this property was modified.
     *
     * @param   string $property The property name
     * @return  boolean
     */
    public function isModified($property = null)
    {
        $result = false;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->isModified($property);
        }

        return $result;
    }

    /**
     * Test if the entity is connected to a data store
     *
     * @return	bool
     */
    public function isConnected()
    {
        $result = false;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->isConnected();
        }

        return $result;
    }

    /**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $key => $entity) {
            $result[$key] = $entity->toArray();
        }
        return $result;
    }

    /**
     * Get a property
     *
     * @param   string  $property The property name.
     * @return  mixed
     */
    final public function __get($property)
    {
        return $this->getProperty($property);
    }

    /**
     * Set a property
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @return  void
     */
    final public function __set($property, $value)
    {
        $this->setProperty($property, $value);
    }

    /**
     * Test existence of a property
     *
     * @param  string  $property The property name.
     * @return boolean
     */
    final public function __isset($property)
    {
        return $this->hasProperty($property);
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  ModelEntityComposite
     */
    final public function __unset($property)
    {
        $this->removeProperty($property);
    }

    /**
     * Forward the call to the current entity
     *
     * @param  string   $method    The function name
     * @param  array    $arguments The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        $result = null;

        if($entity = $this->getIterator()->current())
        {
            // Call_user_func_array is ~3 times slower than direct method calls.
            switch (count($arguments))
            {
                case 0 :
                    $result = $entity->$method();
                    break;
                case 1 :
                    $result = $entity->$method($arguments[0]);
                    break;
                case 2 :
                    $result = $entity->$method($arguments[0], $arguments[1]);
                    break;
                case 3 :
                    $result = $entity->$method($arguments[0], $arguments[1], $arguments[2]);
                    break;
                default:
                    // Resort to using call_user_func_array for many segments
                    $result = call_user_func_array(array($entity, $method), $arguments);
            }
        }

        return $result;
    }
}