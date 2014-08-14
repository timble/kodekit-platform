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
 * Abstract Database Rowset
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Database
 */
abstract class DatabaseRowsetAbstract extends ObjectSet implements DatabaseRowsetInterface
{
    /**
     * Name of the identity column in the rowset
     *
     * @var    string
     */
    protected $_identity_column;

    /**
     * Clone entity object
     *
     * @var    boolean
     */
    protected $_prototypable;

    /**
     * The entity protoype
     *
     * @var  ModelEntityInterface
     */
    protected $_prototype;

    /**
     * Table object or identifier
     *
     * @var    string|object
     */
    protected $_table = false;

    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return DatabaseRowsetAbstract
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_prototypable = $config->prototypable;

        //Set the table identifier
        $this->_table = $config->table;

        // Set the table identifier
        if (isset($config->identity_column)) {
            $this->_identity_column = $config->identity_column;
        }

        // Reset the rowset
        $this->reset();

        // Insert the data, if exists
        if (!empty($config->data))
        {
            foreach($config->data->toArray() as $properties) {
                $this->create($properties, $config->status);
            }
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
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'table'           => $this->getIdentifier()->name,
            'data'            => null,
            'identity_column' => null,
            'prototypable'    => true
        ));

        parent::_initialize($config);
    }

    /**
     * Insert a row into the rowset
     *
     * The row will be stored by it's identity_column if set or otherwise by it's object handle.
     *
     * @param  DatabaseRowInterface $row
     * @throws \InvalidArgumentException if the object doesn't implement DatabaseRowInterface
     * @return boolean    TRUE on success FALSE on failure
     */
    public function insert(ObjectHandlable $row)
    {
        if (!$row instanceof DatabaseRowInterface) {
            throw new \InvalidArgumentException('Row needs to implement DatabaseRowInterface');
        }

        $this->offsetSet($row);

        return true;
    }

    /**
     * Removes a row from the rowset
     *
     * The row will be removed based on it's identity_column if set or otherwise by it's object handle.
     *
     * @param  DatabaseRowInterface $row
     * @throws \InvalidArgumentException if the object doesn't implement DatabaseRowInterface
     * @return DatabaseRowsetAbstract
     */
    public function remove(ObjectHandlable $row)
    {
        if (!$row instanceof DatabaseRowInterface) {
            throw new \InvalidArgumentException('Row needs to implement DatabaseRowInterface');
        }

        return parent::remove($row);
    }

    /**
     * Checks if the collection contains a specific row
     *
     * @param  DatabaseRowInterface $row
     * @throws \InvalidArgumentException if the object doesn't implement DatabaseRowInterface
     * @return  bool Returns TRUE if the object is in the set, FALSE otherwise
     */
    public function contains(ObjectHandlable $row)
    {
        if (!$row instanceof DatabaseRowInterface) {
            throw new \InvalidArgumentException('Entity needs to implement ModelEntityInterface');
        }

        return parent::contains($row);
    }

    /**
     * Create a new row and insert it
     *
     * This function will either clone the row prototype, or create a new instance of the row object for each row
     * being inserted. By default the prototype will be cloned.
     *
     * @param   array   $properties The entity properties
     * @param   string  $status     The entity status
     * @return  ModelEntityComposite
     */
    public function create(array $properties = array(), $status = null)
    {
        if($this->_prototypable)
        {
            if(!$this->_prototype instanceof DatabaseRowInterface) {
                $this->_prototype = $this->getTable()->createRow();
            }

            $row = clone $this->_prototype;

            $row->setStatus($status);
            $row->setProperties($properties, $row->isNew());
        }
        else
        {
            $config = array(
                'data'   => $properties,
                'status' => $status,
            );

            $row = $this->getTable()->createRow($config);
        }

        //Insert the row into the rowset
        $this->insert($row);

        return $row;
    }

    /**
     * Find rows in the rowset based on a needle
     *
     * This functions accepts either a know position or associative array of key/value pairs
     *
     * @param   string|array  $needle The position or the key or an associative array of column data to match
     * @return  DatabaseRowsetInterface Returns a rowset if successful. Otherwise NULL.
     */
    public function find($needle)
    {
        $result = null;

        if(is_array($needle))
        {
            $result = clone $this;

            foreach($this as $row)
            {
                foreach($needle as $key => $value)
                {
                    if(!in_array($row->{$key}, (array) $value)) {
                        $result->remove($row);
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
     * Saves all rows in the rowset to the database
     *
     * @return boolean  If successful return TRUE, otherwise FALSE
     */
    public function save()
    {
        $result = false;

        if (count($this))
        {
            $result = true;

            foreach ($this as $i => $row)
            {
                if (!$row->save())
                {
                    // Set current row status message as rowset status message.
                    $this->setStatusMessage($row->getStatusMessage());
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * Deletes all rows in the rowset from the database
     *
     * @return bool  If successful return TRUE, otherwise FALSE
     */
    public function delete()
    {
        $result = false;

        if (count($this))
        {
            $result = true;

            foreach ($this as $i => $row)
            {
                if (!$row->delete())
                {
                    // Set current row status message as rowset status message.
                    $this->setStatusMessage($row->getStatusMessage());
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * Reset the rowset
     *
     * @return  DatabaseRowInterface
     */
    public function reset()
    {
        $this->_data = array();
        return $this;
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
        if($row = $this->getIterator()->current()) {
            $result = $row->getProperty($name);
        }

        return $result;
    }

    /**
     * Set a property
     *
     * @param   string  $name       The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     * @return  DatabaseRowsetAbstract
     */
    public function setProperty($name, $value, $modified = true)
    {
        if($row = $this->getIterator()->current()) {
            $row->setProperty($name, $value, $modified);
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
        if($row = $this->getIterator()->current()) {
            $result = $row->hasProperty($name);
        }

        return $result;
    }

    /**
     * Remove a property
     *
     * @param   string  $name The property name.
     * @return  DatabaseRowAbstract
     */
    public function removeProperty($name)
    {
        if($row = $this->getIterator()->current()) {
            $row->removeProperty($name);
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
        $result = array();

        if($row = $this->getIterator()->current()) {
            $result = $row->getProperties($modified);
        }

        return $result;
    }

    /**
     * Set the properties
     *
     * @param   mixed   $data        Either and associative array, an object or a DatabaseRow
     * @param   boolean $modified If TRUE, update the modified information for each column being set.
     * @return  DatabaseRowAbstract
     */
    public function setProperties($properties, $modified = true)
    {
        //Prevent changing the identity column
        if (isset($this->_identity_column)) {
            unset($properties[$this->_identity_column]);
        }

        if($row = $this->getIterator()->current()) {
            $row->setProperties($properties, $modified);
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

        if($row = $this->getIterator()->current()) {
            $result = $row->getComputedProperties();
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

        if($row = $this->getIterator()->current()) {
            $status = $row->getStatus();
        }

        return $status;
    }

    /**
     * Set the status
     *
     * @param   string|null  $status The status value or NULL to reset the status
     * @return  DatabaseRowsetAbstract
     */
    public function setStatus($status)
    {
        if($row = $this->getIterator()->current()) {
            $row->setStatusMessage($status);
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

        if($row = $this->getIterator()->current()) {
            $message = $row->getStatusMessage($message);
        }

        return $message;
    }

    /**
     * Set the status message
     *
     * @param   string $message The status message
     * @return  DatabaseRowsetAbstract
     */
    public function setStatusMessage($message)
    {
        if($row = $this->getIterator()->current()) {
            $row->setStatusMessage($message);
        }

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
     * @param    mixed    $table  An object that implements ObjectInterface, ObjectIdentifier object or valid
     *                            identifier string
     * @throws  \UnexpectedValueException If the identifier is not a table identifier
     * @return  DatabaseRowsetAbstract
     */
    public function setTable($table)
    {
        if (!($table instanceof DatabaseTableInterface))
        {
            if (is_string($table) && strpos($table, '.') === false)
            {
                $identifier = $this->getIdentifier()->toArray();
                $identifier['path'] = array('database', 'table');
                $identifier['name'] = StringInflector::pluralize(StringInflector::underscore($table));

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
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $key => $row) {
            $result[$key] = $row->toArray();
        }
        return $result;
    }

    /**
     * Checks if the row is new or not
     *
     * @return boolean
     */
    public function isNew()
    {
        $result = true;
        if($row = $this->getIterator()->current()) {
            $result = $row->isNew();
        }

        return $result;
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
        if($row = $this->getIterator()->current()) {
            $result = $row->isModified($property);
        }

        return $result;
    }

    /**
     * Test the connected status of the row.
     *
     * @return    bool    Returns TRUE if we have a reference to a live DatabaseTableAbstract object.
     */
    public function isConnected()
    {
        return (bool)$this->getTable();
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
     * @return  DatabaseRowAbstract
     */
    final public function __unset($property)
    {
        $this->removeProperty($property);
    }

    /**
     * Forward the call to the current row
     *
     * Search the mixin method map and call the method or forward the call to each row
     *
     * This function implements a just in time mixin strategy. Available table behaviors are only mixed when needed.
     * Lazy mixing is triggered by calling DatabaseRowTable::is[Behaviorable]();
     *
     * @param  string   $method    The function name
     * @param  array    $arguments The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        $result = null;

        if ($this->isConnected())
        {
            $parts = StringInflector::explode($method);

            //Check if a behavior is mixed
            if ($parts[0] == 'is' && isset($parts[1]))
            {
                $row = $this->getIterator()->current();

                if ($row && !in_array($method, $row->getMethods()))
                {
                    //Lazy mix behaviors
                    $behavior = strtolower($parts[1]);

                    if ($row->getTable()->hasBehavior($behavior)) {
                        $row->mixin($row->getTable()->getBehavior($behavior));
                    } else {
                        return false;
                    }
                }
            }
        }

        if($row = $this->getIterator()->current())
        {
            // Call_user_func_array is ~3 times slower than direct method calls.
            switch (count($arguments))
            {
                case 0 :
                    $result = $row->$method();
                    break;
                case 1 :
                    $result = $row->$method($arguments[0]);
                    break;
                case 2 :
                    $result = $row->$method($arguments[0], $arguments[1]);
                    break;
                case 3 :
                    $result = $row->$method($arguments[0], $arguments[1], $arguments[2]);
                    break;
                default:
                    // Resort to using call_user_func_array for many segments
                    $result = call_user_func_array(array($row, $method), $arguments);
            }
        }

        return $result;
    }
}