<?php
/**
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link         http://www.koowa.org
 */

namespace Nooku\Framework;

/**
 * Table Rowset Class
 *
 * @author        Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Rowset
 */
class DatabaseRowsetTable extends DatabaseRowsetAbstract
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
     * @param Config|null $config  An optional Config object with configuration options
     * @return DatabaseRowsetTable
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->_table = $config->table;

        // Reset the rowset
        $this->reset();

        // Insert the data, if exists
        if (!empty($config->data)) {
            $this->addRow($config->data->toArray(), $config->new);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Config $object An optional Config object with configuration options
     * @return  void
     */
    protected function _initialize(Config $config)
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
                if (!($this->_table instanceof ServiceIdentifier)) {
                    $this->setTable($this->_table);
                }

                try {
                    $this->_table = $this->getService($this->_table);
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
     * @param    mixed    $table  An object that implements ServiceInterface, ServiceIdentifier object or valid
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
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('database', 'table');
                $identifier->name = Inflector::tableize($table);
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
     * Test the connected status of the row.
     *
     * @return    bool    Returns TRUE if we have a reference to a live DatabaseTableAbstract object.
     */
    public function isConnected()
    {
        return (bool)$this->getTable();
    }

    /**
     * Add rows to the rowset
     *
     * @param  array  $data  An associative array of row data to be inserted.
     * @param  boole  $new   If TRUE, mark the row(s) as new (i.e. not in the database yet). Default TRUE
     * @return  DatabaseRowsetAbstract
     * @see __construct
     */
    public function addRow(array $data, $new = true)
    {
        if ($this->isConnected()) {
            parent::addRow($data, $new);
        }

        return $this;
    }

    /**
     * Get an empty row
     *
     * @param    array $options An optional associative array of configuration settings.
     * @return    DatabaseRowAbstract
     */
    public function getRow(array $options = array())
    {
        $result = null;

        if ($this->isConnected()) {
            $result = $this->getTable()->getRow($options);
        }

        return $result;
    }

    /**
     * Search the mixin method map and call the method or forward the call to each row
     *
     * This function implements a just in time mixin strategy. Available table behaviors are only mixed when needed.
     * Lazy mixing is triggered by calling DatabaseRowTable::is[Behaviorable]();
     *
     * @param  string     $method    The function name
     * @param  array      $arguments The function arguments
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        if ($this->isConnected() && !isset($this->_mixed_methods[$method]))
        {
            $parts = Inflector::explode($method);

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