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
 * Abstract Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
abstract class DatabaseRowAbstract extends ObjectArray implements DatabaseRowInterface
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
     * Available row status values are defined as STATUS_ constants in Database
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
     * Tracks if row data is new
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
     * Table object or identifier
     *
     * @var    string|object
     */
    protected $_table = false;

    /**
     * Constructor
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the table identifier
        $this->_table = $config->table;

        // Set the table identifier
        if (isset($config->identity_column)) {
            $this->_identity_column = $config->identity_column;
        }

        // Reset the row
        $this->reset();

        //Set the status
        if (isset($config->status)) {
            $this->setStatus($config->status);
        }

        // Set the row data
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
            'table'           => $this->getIdentifier()->name,
            'data'            => null,
            'status'          => null,
            'status_message'  => '',
            'identity_column' => null
        ));

        parent::_initialize($config);
    }

    /**
     * Saves the row to the database.
     *
     * This performs an intelligent insert/update and reloads the properties with fresh data from the table on success.
     *
     * @return boolean If successful return TRUE, otherwise FALSE
     */
    public function save()
    {
        $result = false;

        if ($this->isConnected())
        {
            if (!$this->isNew()) {
                $result = $this->getTable()->update($this);
            } else {
                $result = $this->getTable()->insert($this);
            }

            //Reset the modified array
            if ($result !== false)
            {
                if (((integer) $result) > 0) {
                    $this->_modified = array();
                }
            }
        }

        return (bool) $result;
    }

    /**
     * Deletes the row form the database.
     *
     * @return boolean    If successful return TRUE, otherwise FALSE
     */
    public function delete()
    {
        $result = false;

        if ($this->isConnected())
        {
            if (!$this->isNew()) {
                $result = $this->getTable()->delete($this);
            }
        }

        return (bool) $result;
    }

    /**
     * Reset the row data using the defaults
     *
     * @return DatabaseRowInterface
     */
    public function reset()
    {
        $this->_data     = array();
        $this->_modified = array();

        if ($this->isConnected()) {
            $this->_data = $this->getTable()->getDefaults();
        }

        return $this;
    }

    /**
     * Load the row from the database using the data in the row
     *
     * @return object    If successful returns the row object, otherwise NULL
     */
    public function load()
    {
        $result = null;

        if ($this->isNew())
        {
            if ($this->isConnected())
            {
                $data = $this->getTable()->filter($this->getProperties(true), true);
                $row  = $this->getTable()->select($data, Database::FETCH_ROW);

                // Set the data if the row was loaded successfully.
                if (!$row->isNew())
                {
                    $this->setProperties($row->getProperties(), false);
                    $this->_modified = array();

                    $this->setStatus(Database::STATUS_LOADED);
                    $result = $this;
                }
            }
        }

        return $result;
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
     * If the value is the same as the current value and the row is loaded from the database the value will not be set.
     * If the row is new the value will be (re)set and marked as modified.
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     * @return  DatabaseRowAbstract
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
     * This function will reset required properties to their default value, not required properties will be unset.
     *
     * @param   string  $property The property name.
     * @return  DatabaseRowAbstract
     */
    public function remove($property)
    {
        if ($this->isConnected())
        {
            $column = $this->getTable()->getColumn($property);

            if (isset($column) && $column->required) {
                parent::set($this->_data[$property], $column->default);
            }
            else
            {
                parent::offsetUnset($property);
                unset($this->_modified[$property]);
            }
        }

        return $this;
    }

    /**
     * Get the properties
     *
     * @param   boolean  $modified If TRUE, only return the modified data.
     * @return  array   An associative array of the row properties
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
     * @param   mixed   $data        Either and associative array, an object or a DatabaseRow
     * @param   boolean $modified If TRUE, update the modified information for each property being set.
     * @return  DatabaseRowAbstract
     */
    public function setProperties($properties, $modified = true)
    {
        if ($properties instanceof DatabaseRowInterface) {
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
     * @return  DatabaseRowAbstract
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
     * @return  DatabaseRowAbstract
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
     * Method to get a table object
     *
     * Function catches DatabaseTableExceptions that are thrown for tables that
     * don't exist. If no table object can be created the function will return FALSE.
     *
     * @return DatabaseTableAbstract
     */
    public function getTable()
    {
        if ($this->_table !== false)
        {
            if (!($this->_table instanceof DatabaseTableInterface))
            {
                //Make sure we have a table identifier
                if (!($this->_table instanceof ObjectIdentifier)) {
                    $this->setTable($this->_table);
                }

                try {
                    $this->_table = $this->getObject($this->_table);
                } catch (\RuntimeException $e) {
                    $this->_table = false;
                }
            }
        }

        return $this->_table;
    }

    /**
     * Method to set a table object attached to the rowset
     *
     * @param    mixed    $table An object that implements ObjectInterface, ObjectIdentifier object
     *                           or valid identifier string
     * @throws  \UnexpectedValueException    If the identifier is not a table identifier
     * @return  DatabaseRowInterface
     */
    public function setTable($table)
    {
        if (!($table instanceof DatabaseTableInterface))
        {
            if (is_string($table) && strpos($table, '.') === false)
            {
                $identifier = $this->getIdentifier()->toArray();
                $identifier['path'] = array('database', 'table');
                $identifier['name'] = StringInflector::tableize($table);

                $identifier = $this->getIdentifier($identifier);
            }
            else $identifier = $this->getIdentifier($table);

            if ($identifier->path[1] != 'table') {
                throw new \UnexpectedValueException('Identifier: ' . $identifier . ' is not a table identifier');
            }

            $table = $identifier;
        }

        $this->_table = $table;

        return $this;
    }

    /**
     * Checks if the row is new or not
     *
     * @return bool
     */
    public function isNew()
    {
        return (bool) $this->__new;
    }

    /**
     * Check if a the row or specific row property has been modified.
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
     * Test the connected status of the row.
     *
     * @return    boolean    Returns TRUE if we have a reference to a live DatabaseTableAbstract object.
     */
    public function isConnected()
    {
        return (bool)$this->getTable();
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

    /**
     * Search the mixin method map and call the method or trigger an error
     *
     * This function implements a just in time mixin strategy. Available table behaviors are only mixed when needed.
     * Lazy mixing is triggered by calling DatabaseRowsetTable::is[Behaviorable]();
     *
     * @param  string     $method   The function name
     * @param  array      $argument The function arguments
     * @throws \BadMethodCallException     If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        if ($this->isConnected())
        {
            $parts = StringInflector::explode($method);

            //Check if a behavior is mixed
            if ($parts[0] == 'is' && isset($parts[1]))
            {
                if(!isset($this->_mixed_methods[$method]))
                {
                    //Lazy mix behaviors
                    $behavior = strtolower($parts[1]);

                    if ($this->getTable()->hasBehavior($behavior)) {
                        $this->mixin($this->getTable()->getBehavior($behavior));
                    } else {
                        return false;
                    }
                }
            }
        }

        return parent::__call($method, $arguments);
    }
}
