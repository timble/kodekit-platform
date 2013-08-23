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
 * Abstract Database Table
 *
 * Parent class to all tables.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
abstract class DatabaseTableAbstract extends Object implements DatabaseTableInterface, ObjectMultiton
{
    /**
     * Real name of the table in the db schema
     *
     * @var string
     */
    protected $_name;

    /**
     * Base name of the table in the db schema
     *
     * @var string
     */
    protected $_base;

    /**
     * Name of the identity column in the table
     *
     * @var string
     */
    protected $_identity_column;

    /**
     * Array of column mappings by column name
     *
     * @var array
     */
    protected $_column_map = array();

    /**
     * Database adapter
     *
     * @var DatabaseAdapterInterface
     */
    protected $_adapter;

    /**
     * Default values for this table
     *
     * @var array
     */
    protected $_defaults;

    /**
     * Object constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @throrws \RuntimeException If the table does not exist.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_name = $config->name;
        $this->_base = $config->base;
        $this->_adapter = $config->adapter;

        //Check if the table exists
        if (!$info = $this->getSchema()) {
            throw new \RuntimeException('Table ' . $this->_name . ' does not exist');
        }

        // Set the identity column
        if (!isset($config->identity_column))
        {
            foreach ($this->getColumns(true) as $column)
            {
                //Find auto increment or none-composite primary column
                if ($column->autoinc || ($column->primary && empty($column->related)))
                {
                    $this->_identity_column = $column->name;
                    break;
                }
            }
        }
        else $this->_identity_column = $config->identity_column;

        //Set the default column mappings
        $this->_column_map = $config->column_map ? $config->column_map->toArray() : array();
        if (!isset($this->_column_map['id']) && isset($this->_identity_column)) {
            $this->_column_map['id'] = $this->_identity_column;
        }

        // Set the column filters
        if (!empty($config->filters))
        {
            foreach ($config->filters as $column => $filter) {
                $this->getColumn($column, true)->filter = ObjectConfig::unbox($filter);
            }
        }

        // Mixin the behavior interface
        $this->mixin('lib:behavior.mixin', $config);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $package = $this->getIdentifier()->package;
        $name = $this->getIdentifier()->name;

        $config->append(array(
            'adapter'           => 'lib:database.adapter.mysql',
            'name'              => empty($package) ? $name : $package . '_' . $name,
            'column_map'        => null,
            'filters'           => array(),
            'behaviors'         => array(),
            'identity_column'   => null,
            'command_chain'     => $this->getObject('lib:command.chain'),
            'dispatch_events'   => false,
            'event_dispatcher'  => null,
            'enable_callbacks'  => false,
        ))->append(
            array('base' => $config->name)
        );

        parent::_initialize($config);
    }

    /**
     * Gets the database adapter
     *
     * @throws	\UnexpectedValueException	If the adapter doesn't implement DatabaseAdapterInterface
     * @return DatabaseAdapterInterface
     */
    public function getAdapter()
    {
        if(!$this->_adapter instanceof DatabaseAdapterInterface)
        {
            $this->_adapter = $this->getObject($this->_adapter);

            if(!$this->_adapter instanceof DatabaseAdapterInterface)
            {
                throw new \UnexpectedValueException(
                    'Adapter: '.get_class($this->_adapter).' does not implement DatabaseAdapterInterface'
                );
            }
        }

        return $this->_adapter;
    }

    /**
     * Set the database adapter
     *
     * @param DatabaseAdapterInterface $adapter
     * @return DatabaseQueryInterface
     */
    public function setAdapter(DatabaseAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Test the connected status of the table
     *
     * @return    boolean    Returns TRUE if we have a reference to a live DatabaseAdapterAbstract object.
     */
    public function isConnected()
    {
        return (bool)$this->getAdapter();
    }

    /**
     * Gets the table schema name without the table prefix
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Gets the base table name without the table prefix
     *
     * If the table type is 'VIEW' the base name will be the name of the base table that is connected to the view.
     * If the table type is 'BASE' this function will return the same as {@link getName}
     *
     * @return string
     */
    public function getBase()
    {
        return $this->_base;
    }

    /**
     * Gets the primary key(s) of the table
     *
     * @return array    An associate array of fields defined in the primary key
     */
    public function getPrimaryKey()
    {
        $keys = array();
        $columns = $this->getColumns(true);

        foreach ($columns as $name => $description)
        {
            if ($description->primary) {
                $keys[$name] = $description;
            }
        }

        return $keys;
    }

    /**
     * Gets the schema of the table
     *
     * @return  DatabaseSchemaTable|null Returns a DatabaseSchemaTable object or NULL if the table doesn't exists
     */
    public function getSchema()
    {
        $result = null;

        if ($this->isConnected()){
            $result = $this->getAdapter()->getTableSchema($this->getBase());
        }

        return $result;
    }

    /**
     * Get a column by name
     *
     * @param  string   $columnn The name of the column
     * @param  boolean  $base    If TRUE, get the column information from the base table.
     * @return DatabaseSchemaColumn  Returns a DatabaseSchemaColumn object or NULL if the column does not exist
     */
    public function getColumn($column, $base = false)
    {
        $columns = $this->getColumns($base);
        return isset($columns[$column]) ? $columns[$column] : null;
    }

    /**
     * Gets the columns for the table
     *
     * @param   boolean  $base If TRUE, get the column information from the base table.
     * @return  array    Associative array of DatabaseSchemaColumn objects
     */
    public function getColumns($base = false)
    {
        //Get the table name
        $name = $base ? $this->getBase() : $this->getName();

        //Get the columns from the schema
        $columns = $this->getSchema($name)->columns;

        return $this->mapColumns($columns, true);
    }

    /**
     * Table map method
     *
     * This functions maps the column names to those in the table schema
     *
     * @param  array|string $data    An associative array of data to be mapped, or a column name
     * @param  boolean      $reverse If TRUE, perform a reverse mapping
     * @return array|string The mapped data or column name
     */
    public function mapColumns($data, $reverse = false)
    {
        $map = $reverse ? array_flip($this->_column_map) : $this->_column_map;

        $result = null;

        if (is_array($data))
        {
            $result = array();

            foreach ($data as $column => $value)
            {
                if (is_string($column))
                {
                    //Map the key
                    if (isset($map[$column])) {
                        $column = $map[$column];
                    }
                }
                else
                {
                    //Map the value
                    if (isset($map[$value])) {
                        $value = $map[$value];
                    }
                }

                $result[$column] = $value;
            }
        }

        if (is_string($data))
        {
            $result = $data;
            if (isset($map[$data])) {
                $result = $map[$data];
            }
        }

        return $result;
    }

    /**
     * Get the identity column of the table.
     *
     * @return string
     */
    public function getIdentityColumn()
    {
        $result = null;
        if (isset($this->_identity_column)) {
            $result = $this->_identity_column;
        }

        return $result;
    }

    /**
     * Set the identity column of the table.
     *
     * @param string $column The name of the identity column
     * @throws \DomainException If the column is not unique
     * @return DatabaseTableAbstract
     */
    public function setIdentityColumn($column)
    {
        $columns = $this->getUniqueColumns();

        if (!isset($columns[$column])) {
            throw new \DomainException('Column ' . $column . 'is not unique');
        }

        $this->_identity_column = $column;
        return $this;
    }

    /**
     * Gets the unique columns of the table
     *
     * @return array An associative array of unique table columns by column name
     */
    public function getUniqueColumns()
    {
        $result = array();
        $columns = $this->getColumns(true);

        foreach ($columns as $name => $description)
        {
            if ($description->unique) {
                $result[$name] = $description;
            }
        }

        return $result;
    }

    /**
     * Get default values for all columns
     *
     * @return  array
     */
    public function getDefaults()
    {
        if (!isset($this->_defaults))
        {
            $defaults = array();
            $columns = $this->getColumns();

            foreach ($columns as $name => $description) {
                $defaults[$name] = $description->default;
            }

            $this->_defaults = $defaults;
        }

        return $this->_defaults;
    }

    /**
     * Get a default by name
     *
     * @param string   $column The name of the column
     * @return mixed Returns the column default value or NULL if the column does not exist
     */
    public function getDefault($column)
    {
        $defaults = $this->getDefaults();
        return isset($defaults[$column]) ? $defaults[$column] : null;
    }

    /**
     * Get an instance of a row object for this table
     *
     * @param array $options An optional associative array of configuration settings.
     * @return  DatabaseRowInterface
     */
    public function getRow(array $options = array())
    {
        $identifier = clone $this->getIdentifier();
        $identifier->path = array('database', 'row');
        $identifier->name = StringInflector::singularize($this->getIdentifier()->name);

        //Force the table
        $options['table'] = $this;

        //Set the identity column if not set already
        if (!isset($options['identity_column'])) {
            $options['identity_column'] = $this->mapColumns($this->getIdentityColumn(), true);
        }

        return $this->getObject($identifier, $options);
    }

    /**
     * Get an instance of a rowset object for this table
     *
     * @param   array $options An optional associative array of configuration settings.
     * @return  DatabaseRowInterface
     */
    public function getRowset(array $options = array())
    {
        $identifier = clone $this->getIdentifier();
        $identifier->path = array('database', 'rowset');

        //Force the table
        $options['table'] = $this;

        //Set the identity column if not set already
        if (!isset($options['identity_column'])) {
            $options['identity_column'] = $this->mapColumns($this->getIdentityColumn(), true);
        }

        return $this->getObject($identifier, $options);
    }

    /**
     * Table select method
     *
     * This function will return an empty rowset if called without a parameter.
     *
     * @param mixed    $query   DatabaseQuery, query string, array of row id's, or an id or null
     * @param integer  $mode    The database fetch style.
     * @param integer  $mode    The database fetch style.
     * @param array    $options An optional associative array of configuration options.
     * @return DatabaseRow(set) depending on the mode.
     */
    public function select($query = null, $mode = Database::FETCH_ROWSET, array $options = array())
    {
        //Create query object
        if (is_numeric($query) || is_string($query) || (is_array($query) && is_numeric(key($query))))
        {
            $key = $this->getIdentityColumn();
            $query = $this->getObject('lib:database.query.select')
                ->where('tbl.'.$key . ' IN :' . $key)
                ->bind(array($key => (array)$query));
        }

        if (is_array($query) && !is_numeric(key($query)))
        {
            $columns = $this->mapColumns($query);
            $query = $this->getObject('lib:database.query.select');

            foreach ($columns as $column => $value)
            {
                $query->where('tbl.'.$column . ' ' . (is_array($value) ? 'IN' : '=') . ' :' . $column)
                      ->bind(array($column => $value));
            }
        }

        if ($query instanceof DatabaseQuerySelect)
        {
            if (!$query->columns) {
                $query->columns('*');
            }

            if (!$query->table) {
                $query->table(array('tbl' => $this->getName()));
            }
        }

        //Create commandchain context
        $context = $this->getCommandContext();
        $context->operation = Database::OPERATION_SELECT;
        $context->table     = $this->getBase();
        $context->query     = $query;
        $context->mode      = $mode;
        $context->options   = $options;

        if ($this->getCommandChain()->run('before.select', $context) !== false)
        {
            if ($context->query)
            {
                if($context->mode == Database::FETCH_ARRAY_LIST || $context->mode == Database::FETCH_OBJECT_LIST) {
                    $key = $this->getIdentityColumn();
                } else {
                    $key = null;
                }

                $data = $this->getAdapter()->select($context->query, $context->mode, $key);

                //Map the columns
                if (($context->mode != Database::FETCH_FIELD) && ($context->mode != Database::FETCH_FIELD_LIST))
                {
                    if ($context->mode % 2)
                    {
                        foreach ($data as $key => $value) {
                            $data[$key] = $this->mapColumns($value, true);
                        }
                    }
                    else $data = $this->mapColumns(ObjectConfig::unbox($data), true);
                }
            }

            switch ($context->mode)
            {
                case Database::FETCH_ROW    :
                {
                    if (isset($data) && !empty($data))
                    {
                        $options['data']   = $data;
                        $options['status'] = Database::STATUS_LOADED;
                    }

                    $context->data = $this->getRow($options);
                    break;
                }

                case Database::FETCH_ROWSET :
                {
                    if (isset($data) && !empty($data)) {
                        $options['data']   = $data;
                        $options['status'] = Database::STATUS_LOADED;
                    }

                    $context->data = $this->getRowset($options);
                    break;
                }

                default :
                    $context->data = $data;
            }

            $this->getCommandChain()->run('after.select', $context);
        }

        return ObjectConfig::unbox($context->data);
    }

    /**
     * Count table rows
     *
     * @param   mixed $query    DatabaseQuery object or query string or null to count all rows
     * @param   array $options  An optional associative array of configuration options.
     * @return  int   Number of rows
     */
    public function count($query = null, array $options = array())
    {
        //Count using the identity column
        if (is_scalar($query)) {
            $key = $this->getIdentityColumn();
            $query = array($key => $query);
        }

        //Create query object
        if (is_array($query) && !is_numeric(key($query)))
        {
            $columns = $this->mapColumns($query);
            $query = $this->getObject('lib:database.query.select');

            foreach ($columns as $column => $value)
            {
                $query->where($column . ' ' . (is_array($value) ? 'IN' : '=') . ' :' . $column)
                      ->bind(array($column => $value));
            }
        }

        if ($query instanceof DatabaseQuerySelect)
        {
            if (!$query->columns) {
                $query->columns('COUNT(*)');
            }

            if (!$query->table) {
                $query->table(array('tbl' => $this->getName()));
            }
        }

        $result = (int)$this->select($query, Database::FETCH_FIELD, $options);
        return $result;
    }

    /**
     * Table insert method
     *
     * @param DatabaseRowInterface $row  A DatabaseRow object
     * @return bool|integer Returns the number of rows inserted, or FALSE if insert query was not executed.
     */
    public function insert(DatabaseRowInterface $row)
    {
        // Create query object.
        $query = $this->getObject('lib:database.query.insert')
                      ->table($this->getBase());

        //Create commandchain context
        $context = $this->getCommandContext();
        $context->operation = Database::OPERATION_INSERT;
        $context->table     = $this->getBase();
        $context->data      = $row;
        $context->query     = $query;
        $context->affected = false;

        if ($this->getCommandChain()->run('before.insert', $context) !== false)
        {
            // Filter the data and remove unwanted columns.
            $data = $this->filter($context->data->getData());
            $context->query->values($this->mapColumns($data));

            // Execute the insert query.
            $context->affected = $this->getAdapter()->insert($context->query);

            // Set the status and data before calling the command chain
            if ($context->affected !== false)
            {
                if ($context->affected)
                {
                    if(($column = $this->getIdentityColumn()) && $this->getColumn($this->mapColumns($column, true), true)->autoinc) {
                        $data[$this->getIdentityColumn()] = $this->getAdapter()->getInsertId();
                    }

                    $context->data->setData($this->mapColumns($data, true))->setStatus(Database::STATUS_CREATED);
                }
                else $context->data->setStatus(Database::STATUS_FAILED);
            }

            $this->getCommandChain()->run('after.insert', $context);
        }

        return $context->affected;
    }

    /**
     * Table update method
     *
     * @param  DatabaseRowTable $row A DatabaseRow object
     * @return boolean|integer  Returns the number of rows updated, or FALSE if insert query was not executed.
     */
    public function update(DatabaseRowTable $row)
    {
        // Create query object.
        $query = $this->getObject('lib:database.query.update')
                      ->table($this->getBase());

        // Create commandchain context.
        $context = $this->getCommandContext();
        $context->operation = Database::OPERATION_UPDATE;
        $context->table     = $this->getBase();
        $context->data      = $row;
        $context->query     = $query;
        $context->affected  = false;

        if ($this->getCommandChain()->run('before.update', $context) !== false)
        {
            foreach ($this->getPrimaryKey() as $key => $column)
            {
                $context->query->where($column->name . ' = :' . $key)
                    ->bind(array($key => $context->data->$key));
            }

            // Filter the data and remove unwanted columns.
            $data = $this->filter($context->data->getData(true));

            foreach ($this->mapColumns($data) as $key => $value) {
                $query->values($key . ' = :_' . $key)->bind(array('_' . $key => $value));
            }

            // Execute the update query.
            $context->affected = $this->getAdapter()->update($context->query);

            // Set the status and data before calling the command chain
            if ($context->affected !== false)
            {
                if ($context->affected) {
                    $context->data->setData($this->mapColumns($data, true), true)->setStatus(Database::STATUS_UPDATED);
                } else {
                    $context->data->setStatus(Database::STATUS_FAILED);
                }
            }

            $this->getCommandChain()->run('after.update', $context);
        }

        return $context->affected;
    }

    /**
     * Table delete method
     *
     * @param  DatabaseRowInterface $row A DatabaseRow object
     * @return bool|integer Returns the number of rows deleted, or FALSE if delete query was not executed.
     */
    public function delete(DatabaseRowInterface $row)
    {
        // Create query object.
        $query = $this->getObject('lib:database.query.delete')
                      ->table($this->getBase());

        //Create commandchain context
        $context = $this->getCommandContext();
        $context->operation = Database::OPERATION_DELETE;
        $context->table     = $this->getBase();
        $context->data      = $row;
        $context->query     = $query;
        $context->affected  = false;

        if ($this->getCommandChain()->run('before.delete', $context) !== false)
        {
            foreach ($this->getPrimaryKey() as $key => $column)
            {
                $context->query->where($column->name . ' = :' . $column->name)
                    ->bind(array($column->name => $context->data->$key));
            }

            // Execute the delete query.
            $context->affected = $this->getAdapter()->delete($context->query);

            // Set the query in the context.
            if ($context->affected !== false) {
                $context->data->setStatus($context->affected ? Database::STATUS_DELETED : Database::STATUS_FALIED);
            }

            $this->getCommandChain()->run('after.delete', $context);
        }

        return $context->affected;
    }

    /**
     * Lock the table.
     *
     * return boolean True on success, false otherwise.
     */
    public function lock()
    {
        $result = null;

        $context = $this->getCommandContext();
        $context->table = $this->getBase();

        if ($this->getCommandChain()->run('before.lock', $context) !== false)
        {
            if ($this->isConnected()) {
                $context->result = $this->getAdapter()->lock($this->getBase());
            }

            $this->getCommandChain()->run('after.lock', $context);
        }

        return $context->result;
    }

    /**
     * Unlock the table.
     *
     * return boolean True on success, false otherwise.
     */
    public function unlock()
    {
        $result = null;

        $context = $this->getCommandContext();
        $context->table = $this->getBase();

        if ($this->getCommandChain()->run('before.unlock', $context) !== false)
        {
            if ($this->isConnected()) {
                $context->result = $this->getAdapter()->unlock();
            }

            $this->getCommandChain()->run('after.unlock', $context);
        }

        return $context->result;
    }

    /**
     * Table filter method
     *
     * This function removes extra columns based on the table columns taking any table mappings into account and
     * filters the data based on each column type.
     *
     * @param  array   $data    An associative array of data to be filtered
     * @param  boolean $base    If TRUE, get the column information from the base table.
     * @return array    The filtered data
     */
    public function filter(array $data, $base = true)
    {
        // Filter out any extra columns.
        $data = array_intersect_key($data, $this->getColumns($base));

        // Filter data based on column type
        foreach ($data as $key => $value)
        {
            $column     = $this->getColumn($key, $base);
            $data[$key] = $column->filter->sanitize($value);

            // If NULL is allowed and default is NULL, set value to NULL in the following cases.
            if (!$column->required && is_null($column->default))
            {
                // If value is empty.
                if (empty($data[$key])) {
                    $data[$key] = null;
                }

                // If type is date, time or datetime and value is like 0000-00-00 00:00:00.
                $date_types = array('date', 'time', 'datetime');
                if (in_array($column->type, $date_types) && !preg_match('/[^-0:\s]/', $data[$key])) {
                    $data[$key] = null;
                }
            }
        }

        return $data;
    }

    /**
     * Search the behaviors to see if this table behaves as.
     *
     * Function is also capable of checking is a behavior has been mixed successfully using is[Behavior] function.
     * If the behavior exists the function will return TRUE, otherwise FALSE.
     *
     * @param  string     $method    The function name
     * @param  array      $arguments The function arguments
     * @throws \BadMethodCallException     If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        // If the method is of the form is[Bahavior] handle it.
        $parts = StringInflector::explode($method);

        if ($parts[0] == 'is' && isset($parts[1]))
        {
            if(!$this->hasBehavior(strtolower($parts[1]))) {
                return false;
            }
        }

        return parent::__call($method, $arguments);
    }
}
