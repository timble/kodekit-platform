<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Composite Model Decorator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Searchable
 */
class ModelCompositeDecorator extends ObjectDecorator implements ModelInterface, ModelEntityComposable
{
    /**
     * Create a new entity for the data store
     *
     * @param  array $properties Array of entity properties
     * @return  ModelEntityInterface
     */
    public function create(array $properties = array())
    {
        return $this->getDelegate()->create($properties);
    }

    /**
     * Fetch an entity from the data store using the the model state
     *
     * @return ModelEntityComposite
     */
    public function fetch()
    {
        return $this->getDelegate()->fetch();
    }

    /**
     * Get the total amount of entities from the data store using the model state
     *
     * @return  int
     */
    public function count()
    {
        return $this->getDelegate()->count();
    }

    /**
     * Reset the model data and state
     *
     * @param  array $modified List of changed state names
     * @return ModelInterface
     */
    public function reset(array $modified = array())
    {
        return $this->getDelegate()->reset($modified);
    }

    /**
     * Insert a new entity into the model
     *
     * This function will either clone a entity prototype or create a new instance of the entity object for each
     * entity being inserted. By default the entity will be cloned. The entity will be stored by it's identity_key
     * if set or otherwise by it's object handle.
     *
     * @param   ModelEntityInterface|array $entity  A ModelEntityInterface object or an array of entity properties
     * @param   string  $status     The entity status
     * @return  ModelEntityComposite
     */
    public function insert($entity, $status = null)
    {
        return $this->fetch()->insert($entity, $status);
    }

    /**
     * Find an entity in the model based on a needle
     *
     * This functions accepts either a know position or associative array of property/value pairs
     *
     * @param 	string $needle The position or the key to search for
     * @return ModelEntityInterface
     */
    public function find($needle)
    {
        return $this->fetch()->find($needle);
    }

    /**
     * Checks if the model contains a specific entity
     *
     * @param   ModelEntityInterface $entity
     * @return  bool Returns TRUE if the object is in the set, FALSE otherwise
     */
    public function contains($entity)
    {
        return $this->fetch()->contains($entity);
    }

    /**
     * Removes an entity from the model
     *
     * The entity will be removed based on it's identity_key if set or otherwise by it's object handle.
     *
     * @param  ModelEntityInterface $entity
     * @return ModelEntityComposite
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntityInterface
     */
    public function remove($entity)
    {
        return $this->fetch()->remove($entity);
    }

    /**
     * Saves the to the data store
     *
     * This performs an intelligent insert/update and reloads the properties
     * with fresh data from the table on success.
     *
     * @return ModelEntityInterface
     */
    public function save()
    {
        return $this->fetch()->save();
    }

    /**
     * Deletes the entity form the data store
     *
     * @return ModelEntityInterface
     */
    public function delete()
    {
        return $this->fetch()->delete();
    }

    /**
     * Clear the entity data
     *
     * @return ModelEntityInterface
     */
    public function clear()
    {
        return $this->fetch()->clear();
    }

    /**
     * Serialize
     *
     * Required by interface \Serializable
     *
     * @return  string
     */
    public function serialize()
    {
        return $this->fetch()->serialize();
    }

    /**
     * Unserialize
     *
     * Required by interface \Serializable
     *
     * @param   string  $data
     */
    public function unserialize($data)
    {
        $this->fetch()->unserialize($data);
    }

    /**
     * Get the entity key
     *
     * @return string
     */
    public function getIdentityKey()
    {
        return $this->fetch()->getIdentityKey();
    }

    /**
     * Get a property
     *
     * @param   string  $name The property name.
     * @return  mixed   The property value.
     */
    public function getProperty($name)
    {
        return $this->fetch()->getProperty($name);
    }

    /**
     * Set a property
     *
     * If the value is the same as the current value and the entity is loaded from the data store the value will not be
     * reset. If the entity is new the value will be (re)set and marked as modified
     *
     * @param   string  $name       The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     *
     * @return  ModelEntityInterface
     */
    public function setProperty($name, $value, $modified = true)
    {
        return $this->fetch()->setProperty($name, $value, $modified);
    }

    /**
     * Test existence of a property
     *
     * @param  string  $name The property name.
     * @return boolean
     */
    public function hasProperty($name)
    {
        return $this->fetch()->hasProperty($name);
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  ModelEntityInterface
     */
    public function removeProperty($name)
    {
        return $this->fetch()->removeProperty($name);
    }

    /**
     * Get the properties
     *
     * @param   boolean  $modified If TRUE, only return the modified data.
     * @return  array   An associative array of entity properties
     */
    public function getProperties($modified = false)
    {
        return $this->fetch()->getProperties($modified);
    }

    /**
     * Set the properties
     *
     * @param   mixed   $properties  Either and associative array, an object or a ModelEntityInterface
     * @param   boolean $modified    If TRUE, update the modified information for each column being set.
     * @return  ModelEntityInterface
     */
    public function setProperties($properties, $modified = true)
    {
        return $this->fetch()->setProperties($properties, $modified = true);
    }

    /**
     * Get a list of the computed properties
     *
     * @return array An array
     */
    public function getComputedProperties()
    {
        return $this->fetch()->getComputedProperties();
    }

    /**
     * Returns the status.
     *
     * @return string The status value.
     */
    public function getStatus()
    {
        return $this->fetch()->getStatus();
    }

    /**
     * Set the status
     *
     * @param   string|null $status The status value or NULL to reset the status
     * @return  ModelEntityInterface
     */
    public function setStatus($status)
    {
        return $this->fetch()->setStatus($status);
    }

    /**
     * Returns the status message
     *
     * @return string The status message
     */
    public function getStatusMessage()
    {
        return $this->fetch()->getStatusMessage();
    }

    /**
     * Set the status message
     *
     * @param   string $message The status message
     * @return  ModelEntityInterface
     */
    public function setStatusMessage($message)
    {
        return $this->fetch()->setStatusMessage($message);
    }

    /**
     * Set the model state values
     *
     * @param  array $values Set the state values
     *
     * @return ModelInterface
     */
    public function setState(array $values)
    {
        return $this->getDelegate()->setState($values);
    }

    /**
     * Method to get state object
     *
     * @return  ModelStateInterface  The model state object
     */
    public function getState()
    {
        return $this->getDelegate()->getState();
    }

    /**
     * Get a new iterator
     *
     * @return  \ArrayIterator
     */
    public function getIterator()
    {
        return $this->fetch()->getIterator();
    }

    /**
     * Checks if the entity is new or not
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->fetch()->isNew();
    }

    /**
     * Check if the entity or specific entity property has been modified.
     *
     * If a specific property name is giving method will return TRUE only if this property was modified.
     *
     * @param   string $property The property name
     * @return  boolean
     */
    public function isModified($property = null)
    {
        return $this->fetch()->isModified($property);
    }

    /**
     * Test if the entity is connected to a data store
     *
     * @return	bool
     */
    public function isConnected()
    {
        return $this->fetch()->isConnected();
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
        $this->setProperty($property, $value);
    }

    /**
     * Get a property
     *
     * @param   string  $property   The property name.
     * @return  mixed The property value
     */
    final public function offsetGet($property)
    {
        return $this->getProperty($property);
    }

    /**
     * Check if a property exists
     *
     * @param   string  $property   The property name.
     * @return  boolean
     */
    final public function offsetExists($property)
    {
        return $this->hasProperty($property);
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  void
     */
    final public function offsetUnset($property)
    {
        $this->removeProperty($property);
    }

    /**
     * Set the decorated model
     *
     * @param   ModelInterface $delegate The decorated model
     * @return  ModelCompositeDecorator
     * @throws \InvalidArgumentException If the delegate is not a model
     */
    public function setDelegate($delegate)
    {
        if (!$delegate instanceof ModelInterface) {
            throw new \InvalidArgumentException('Delegate: '.get_class($delegate).' does not implement ModelInterface');
        }

        return parent::setDelegate($delegate);
    }

    /**
     * Get the decorated model
     *
     * @return ModelInterface
     */
    public function getDelegate()
    {
        return parent::getDelegate();
    }

    /**
     * Overloaded call function
     *
     * Auto-matically fetch the entity and forward the call if the method exists in the entity,
     * if not delegate to the model instead.
     *
     * @param  string     $method    The function name
     * @param  array      $arguments The function arguments
     * @throws \BadMethodCallException     If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        $model  = $this->getDelegate();
        $entity = $this->fetch();

        //Call the method if it exists
        if (!method_exists($model, $method) && is_callable(array($entity, $method)))
        {
            $result = null;

            // Call_user_func_array is ~3 times slower than direct method calls.
            switch (count($arguments))
            {
                case 0 :
                    $result = $entity->$method();
                    break;
                case 1 :
                    $result = $entity->$method($arguments[0]);
                    break;
                case 2:
                    $result = $entity->$method($arguments[0], $arguments[1]);
                    break;
                case 3:
                    $result = $entity->$method($arguments[0], $arguments[1], $arguments[2]);
                    break;
                default:
                    // Resort to using call_user_func_array for many segments
                    $result = call_user_func_array(array($entity, $method), $arguments);
            }

            return $result;
        }

        return parent::__call($method, $arguments);
    }
}