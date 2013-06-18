<?php
/**
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Abstract Rowset Class
 *
 * @author        Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Rowset
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
     * Clone row object when adding data
     *
     * @var    boolean
     */
    protected $_row_cloning;

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  An optional ObjectConfig object with configuration options
     * @return DatabaseRowsetAbstract
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_row_cloning = $config->row_cloning;

        // Set the table identifier
        if (isset($config->identity_column)) {
            $this->_identity_column = $config->identity_column;
        }

        // Reset the rowset
        $this->reset();

        // Insert the data, if exists
        if (!empty($config->data)) {
            $this->addRow($config->data->toArray(), $config->status);
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
            'data'            => null,
            'identity_column' => null,
            'row_cloning'     => true
        ));

        parent::_initialize($config);
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
     * Test the connected status of the rowset.
     *
     * @return    bool    Returns TRUE by default.
     */
    public function isConnected()
    {
        return true;
    }

    /**
     * Insert a row into the rowset
     *
     * The row will be stored by it's identity_column if set or otherwise by it's object handle.
     *
     * @param  DatabaseRowInterface $row
     * @return boolean    TRUE on success FALSE on failure
     * @throws \InvalidArgumentException if the object doesn't implement DatabaseRowInterface
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
     * @return DatabaseRowsetAbstract
     * @throws \InvalidArgumentException if the object doesn't implement DatabaseRowInterface
     */
    public function extract(ObjectHandlable $row)
    {
        if (!$row instanceof DatabaseRowInterface) {
            throw new \InvalidArgumentException('Row needs to implement DatabaseRowInterface');
        }

        if ($this->offsetExists($row)) {
            $this->offsetUnset($row);
        }

        return $this;
    }

    /**
     * Get a property
     *
     * @param   string  $property The property name.
     * @return  mixed
     */
    public function get($property)
    {
        $result = null;
        if($row = $this->getIterator()->current()) {
            $result = $row->get($property);
        }

        return $result;
    }

    /**
     * Set a property
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     * @return  DatabaseRowsetAbstract
     */
    public function set($property, $value, $modified = true)
    {
        if($row = $this->getIterator()->current()) {
            $row->set($property, $value, $modified);
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
        $result = false;
        if($row = $this->getIterator()->current()) {
            $result = $row->has($property);
        }

        return $result;
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  DatabaseRowAbstract
     */
    public function remove($property)
    {
        if($row = $this->getIterator()->current()) {
            $row->remove($property);
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
     * Add rows to the rowset
     *
     * This function will either clone the row object, or create a new instance of the row object for each row being
     * inserted. By default the row will be cloned.
     *
     * @param  array   $rows    An associative array of row data to be inserted.
     * @param  string  $status  The row(s) status
     * @return  DatabaseRowsetAbstract
     * @see __construct
     */
    public function addRow(array $rows, $status = NULL)
    {
        if ($this->_row_cloning)
        {
            $default = $this->createRow()->setStatus($status);

            foreach ($rows as $k => $data)
            {
                $row = clone $default;
                $row->setProperties($data, $row->isNew());

                $this->insert($row);
            }
        }
        else
        {
            foreach ($rows as $k => $data)
            {
                $row = $this->createRow()->setStatus($status);
                $row->setProperties($data, $row->isNew());

                $this->insert($row);
            }
        }

        return $this;
    }

    /**
     * Get an instance of a row object for this rowset
     *
     * @param    array $options An optional associative array of configuration settings.
     * @return  DatabaseRowInterface
     */
    public function createRow(array $options = array())
    {
        $identifier = clone $this->getIdentifier();
        $identifier->path = array('database', 'row');
        $identifier->name = StringInflector::singularize($this->getIdentifier()->name);

        //The row default options
        $options['identity_column'] = $this->getIdentityColumn();

        return $this->getObject($identifier, $options);
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
     * Get a list of properties that have been modified
     *
     * @return array An array of properties keys that have been modified
     */
    public function getModified()
    {
        $result = array();

        if($row = $this->getIterator()->current()) {
            $result = $row->getModified();
        }

        return $result;
    }

    /**
     * Returns a DatabaseRow(set)
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
                        $result->extract($row);
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
     * Get a property
     *
     * @param   string  $property The property name.
     * @return  mixed
     */
    public function __get($property)
    {
        return $this->get($property);
    }

    /**
     * Set a property
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @return  void
     */
    public function __set($property, $value)
    {
        $this->set($property, $value);
    }

    /**
     * Test existence of a property
     *
     * @param  string  $property The property name.
     * @return boolean
     */
    public function __isset($property)
    {
        return $this->has($property);
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  DatabaseRowAbstract
     */
    public function __unset($property)
    {
        $this->remove($property);
    }

    /**
     * Forward the call to the current row
     *
     * @param  string   $method    The function name
     * @param  array    $arguments The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        $result = null;

        if($row = $this->getIterator()->current()) {
            $result = $row->$method($arguments);
        }

        return $result;
    }
}