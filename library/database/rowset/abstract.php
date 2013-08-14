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
 * Abstract Database Rowset
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
     * The status message
     *
     * @var string
     */
    protected $_status_message = '';

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

        // Set the table indentifier
        if (isset($config->identity_column)) {
            $this->_identity_column = $config->identity_column;
        }

        // Reset the rowset
        $this->reset();

        // Insert the data, if exists
        if (!empty($config->data)) {
            $this->addRow($config->data->toArray(), $config->status);
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
            'data'            => null,
            'identity_column' => null,
            'status'          => null,
            'status_message'  => '',
            'row_cloning'     => true
        ));

        parent::_initialize($config);
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
     * The row will be stored by it's identity_column if set or otherwise by
     * it's object handle.
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

        if (isset($this->_identity_column)) {
            $handle = $row->{$this->_identity_column};
        } else {
            $handle = $row->getHandle();
        }

        if ($handle) {
            $this->_object_set->offsetSet($handle, $row);
        }

        return true;
    }

    /**
     * Removes a row from the rowset
     *
     * The row will be removed based on it's identity_column if set or otherwise by
     * it's object handle.
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

        if (isset($this->_identity_column)) {
            $handle = $row->{$this->_identity_column};
        } else {
            $handle = $row->getHandle();
        }

        if ($this->_object_set->offsetExists($handle)) {
            $this->_object_set->offsetUnset($handle);
        }

        return $this;
    }

    /**
     * Retrieve an array of column values
     *
     * @param   string  $column The column name.
     * @return  array   An array of all the column values
     */
    public function get($column)
    {
        $result = array();
        foreach ($this as $key => $row) {
            $result[$key] = $row->$column;
        }

        return $result;
    }

    /**
     * Set the value of all the columns
     *
     * @param   string  $column The column name.
     * @param   mixed   $value The value for the property.
     * @return  DatabaseRowsetAbstract
     */
    public function set($column, $value)
    {
        foreach ($this as $row) {
            $row->$column = $value;
        }

        return $this;
    }

    /**
     * Returns all data as an array.
     *
     * @param  bool  $modified  If TRUE, only return the modified data. Default FALSE
     * @return array
     */
    public function getData($modified = false)
    {
        $result = array();
        foreach ($this as $key => $row) {
            $result[$key] = $row->getData($modified);
        }
        return $result;
    }

    /**
     * Set the rowset data based on a named array/hash
     *
     * @param   mixed   $data     Either and associative array, a DatabaseRow object or object
     * @param   boolean $modified If TRUE, update the modified information for each column being set. Default TRUE
     * @return  DatabaseRowsetAbstract
     */
    public function setData($data, $modified = true)
    {
        //Prevent changing the identity column
        if (isset($this->_identity_column)) {
            unset($data[$this->_identity_column]);
        }

        //Set the data in the rows
        if ($modified) {
            foreach ($data as $column => $value) {
                $this->$column = $value;
            }
        } else {
            foreach ($this as $row) {
                $row->setData($data, false);
            }
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
            $default = $this->getRow()->setStatus($status);

            foreach ($rows as $k => $data)
            {
                $row = clone $default;
                $row->setData($data, $row->isNew());

                $this->insert($row);
            }
        }
        else
        {
            foreach ($rows as $k => $data)
            {
                $row = $this->getRow()->setStatus($status);
                $row->setData($data, $row->isNew());

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
    public function getRow(array $options = array())
    {
        $identifier = clone $this->getIdentifier();
        $identifier->path = array('database', 'row');
        $identifier->name = StringInflector::singularize($this->getIdentifier()->name);

        //The row default options
        $options['identity_column'] = $this->getIdentityColumn();

        return $this->getObject($identifier, $options);
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
     * @return  DatabaseRowsetAbstract
     */
    public function setStatusMessage($message)
    {
        $this->_status_message = $message;
        return $this;
    }

    /**
     * Gets the identity column of the rowset
     *
     * @return string
     */
    public function getIdentityColumn()
    {
        return $this->_identity_column;
    }

    /**
     * Returns a DatabaseRow(set)
     *
     * This functions accepts either a know position or associative array of key/value pairs
     *
     * @param   string|array  $needle The position or the key or an associative array of column data to match
     * @return  DatabaseRow(set)Abstract Returns a row or rowset if successful. Otherwise NULL.
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

        if(is_scalar($needle) && isset($this->_object_set[$needle])) {
            $result = $this->_object_set[$needle];
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
        $this->_object_set->exchangeArray(array());

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
     * Retrieve an array of column values
     *
     * @param   string  $column The column name.
     * @return  array   An array of all the column values
     */
    public function __get($column)
    {
        return $this->get($column);
    }

    /**
     * Set the value of all the columns
     *
     * @param   string  $column The column name.
     * @param   mixed   $value The value for the property.
     * @return  void
     */
    public function __set($column, $value)
    {
        $this->set($column, $value);
    }

    /**
     * Search the mixin method map and call the method or forward the call to each row for processing.
     *
     * @param  string   $method    The function name
     * @param  array    $arguments The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        //If the mixed method exists call it for all the rows
        if (!isset($this->_mixed_methods[$method]))
        {
            foreach ($this as $i => $row) {
                $row->__call($method, $arguments);
            }

            return $this;
        }

        return parent::__call($method, $arguments);
    }
}