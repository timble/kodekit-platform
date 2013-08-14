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
 * Table Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseRowTable extends DatabaseRowAbstract
{
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
        //Bypass DatabaseRowAbstract constructor to prevent data from being added twice
        Object::__construct($config);

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
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'table' => $this->getIdentifier()->name
        ));

        parent::_initialize($config);
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
     * @return  DatabaseRowsetAbstract
     */
    public function setTable($table)
    {
        if (!($table instanceof DatabaseTableInterface))
        {
            if (is_string($table) && strpos($table, '.') === false)
            {
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('database', 'table');
                $identifier->name = StringInflector::tableize($table);
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
                $data = $this->getTable()->filter($this->getData(true), true);
                $row  = $this->getTable()->select($data, Database::FETCH_ROW);

                // Set the data if the row was loaded successfully.
                if (!$row->isNew())
                {
                    $this->setData($row->getData(), false);
                    $this->_modified = array();

                    $this->setStatus(Database::STATUS_LOADED);
                    $result = $this;
                }
            }
        }

        return $result;
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
     * @return DatabaseRowTable
     */
    public function reset()
    {
        $result = parent::reset();

        if ($this->isConnected()) {
            $this->_data = $this->getTable()->getDefaults();
        }

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
     * Unset a row field
     *
     * This function will reset required column to their default value, not required fields will be unset.
     *
     * @param    string  $column The column name.
     * @return   void
     */
    public function offsetUnset($column)
    {
        if ($this->isConnected())
        {
            $field = $this->getTable()->getColumn($column);

            if (isset($field) && $field->required) {
                parent::offsetSet($this->_data[$column], $field->default);
            } else {
                parent::offsetUnset($column);
            }
        }
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
        if ($this->isConnected() && !isset($this->_mixed_methods[$method]))
        {
            $parts = StringInflector::explode($method);

            //Check if a behavior is mixed
            if ($parts[0] == 'is' && isset($parts[1]))
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

        return parent::__call($method, $arguments);
    }
}