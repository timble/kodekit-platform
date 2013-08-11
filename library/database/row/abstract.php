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
     * Tracks columns who's data is modified and has not been persisted yet.
     *
     * @var array
     */
    protected $_modified = array();

    /**
     * Tracks the status the row
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
     * Name of the identity column in the rowset
     *
     * @var    string
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

        // Reset the row
        $this->reset();

        //Set the status
        if (isset($config->status)) {
            $this->setStatus($config->status);
        }

        // Set the row data
        if (isset($config->data)) {
            $this->setData($config->data->toArray(), $this->isNew());
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
     * Get a row field value
     *
     * @param   string  $column The column name.
     * @return  string  The corresponding value.
     */
    public function get($column)
    {
        return $this->offsetGet($column);
    }

    /**
     * Set row field value
     *
     * If the value is the same as the current value and the row is loaded from the database the value will not be reset.
     * If the row is new the value will be (re)set and marked as modified
     *
     * @param   string  $column The column name.
     * @param   mixed   $value  The column value.
     * @return  DatabaseRowAbstract
     */
    public function set($column, $value)
    {
        $this->offsetSet($column, $value);
        return $this;
    }

    /**
     * Test existence of a column
     *
     * @param  string  $column The column name.
     * @return boolean
     */
    public function has($column)
    {
        return $this->offsetExists($column);
    }

    /**
     * Remove a row field
     *
     * @param   string  $column The column name.
     * @return  DatabaseRowAbstract
     */
    public function remove($column)
    {
        $this->offsetUnset($column);
        return $this;
    }

    /**
     * Returns an associative array of the raw data
     *
     * @param   boolean  $modified If TRUE, only return the modified data.
     * @return  array
     */
    public function getData($modified = false)
    {
        $data = $this->_data;

        if ($modified) {
            $data = array_intersect_key($data, $this->_modified);
        }

        return $data;
    }

    /**
     * Set the row data
     *
     * @param   mixed   $data        Either and associative array, an object or a DatabaseRow
     * @param   boolean $modified If TRUE, update the modified information for each column being set.
     * @return  DatabaseRowAbstract
     */
    public function setData($data, $modified = true)
    {
        if ($data instanceof DatabaseRowInterface) {
            $data = $data->toArray();
        } else {
            $data = (array)$data;
        }

        if ($modified)
        {
            foreach ($data as $column => $value) {
                $this->$column = $value;
            }
        }
        else $this->_data = array_merge($this->_data, $data);

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
     * Gets the identity column of the rowset
     *
     * @return string
     */
    public function getIdentityColumn()
    {
        return $this->_identity_column;
    }

    /**
     * Get a list of columns that have been modified
     *
     * @return array    An array of column names that have been modified
     */
    public function getModified()
    {
        return $this->_modified;
    }

    /**
     * Load the row from the database.
     *
     * @return object    If successful returns the row object, otherwise NULL
     */
    public function load()
    {
        $this->_modified = array();
        return $this;
    }

    /**
     * Saves the row to the database.
     *
     * @return boolean  If successful return TRUE, otherwise FALSE
     */
    public function save()
    {
        if (!$this->isNew()) {
            $this->setStatus(Database::STATUS_UPDATED);
        } else {
            $this->setStatus(Database::STATUS_CREATED);
        }

        $this->_modified = array();
        return false;
    }

    /**
     * Deletes the row form the database.
     *
     * @return boolean  If successful return TRUE, otherwise FALSE
     */
    public function delete()
    {
        $this->setStatus(Database::STATUS_DELETED);

        return false;
    }

    /**
     * Resets to the default properties
     *
     * @return DatabaseRowInterface
     */
    public function reset()
    {
        $this->_data     = array();
        $this->_modified = array();

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
     * Check if a column has been modified
     *
     * @param   string  $column The column name.
     * @return  boolean
     */
    public function isModified($column)
    {
        $result = false;
        if (isset($this->_modified[$column]) && $this->_modified[$column]) {
            $result = true;
        }

        return $result;
    }

    /**
     * Test the connected status of the row.
     *
     * @return    boolean    Returns TRUE by default.
     */
    public function isConnected()
    {
        return true;
    }

    /**
     * Set row field value
     *
     * If the value is the same as the current value and the row is loaded from the database the value will not be reset.
     * If the row is new the value will be (re)set and marked as modified
     *
     * @param   string  $column The column name.
     * @param   mixed   $value  The column value.
     * @return  void
     */
    public function offsetSet($column, $value)
    {
        if ($this->isNew() || !array_key_exists($column, $this->_data) || ($this->_data[$column] != $value))
        {
            parent::offsetSet($column, $value);
            $this->_modified[$column] = $column;
        }
    }

    /**
     * Remove a row field
     *
     * @param   string  $column The column name.
     * @return  void
     */
    public function offsetUnset($column)
    {
        parent::offsetUnset($column);
        unset($this->_modified[$column]);
    }
}
