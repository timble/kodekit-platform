<?php
/**
 * @version        $Id$
 * @package     Koowa_Database
 * @subpackage  Table
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Abstract Table Class
 *
 * Parent class to all tables.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Table
 * @uses        KMixinClass
 * @uses        KFilter
 */
abstract class KDatabaseTableAbstract extends KObject implements KDatabaseTableInterface
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
     * @var KDatabaseAdapterInterface
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
     * @param   object  An optional KConfig object with configuration options.
     * @throrws \RuntimeException If the table does not exist.
     */
    public function __construct(KConfig $config)
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
                $this->getColumn($column, true)->filter = KConfig::unbox($filter);
            }
        }

        //Set the mixer in the config
        $config->mixer = $this;

        // Mixin the command interface
        $this->mixin(new KMixinCommand($config));

        // Mixin the behavior interface
        $this->mixin(new KMixinBehavior($config));
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $package = $this->getIdentifier()->package;
        $name = $this->getIdentifier()->name;

        $config->append(array(
            'adapter'           => 'koowa:database.adapter.mysql',
            'name'              => empty($package) ? $name : $package . '_' . $name,
            'column_map'        => null,
            'filters'           => array(),
            'behaviors'         => array(),
            'identity_column'   => null,
            'command_chain'     => $this->getService('koowa:command.chain'),
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
     * @throws	\UnexpectedValueException	If the adapter doesn't implement KDatabaseAdapterInterface
     * @return KDatabaseAdapterInterface
     */
    public function getAdapter()
    {
        if(!$this->_adapter instanceof KDatabaseAdapterInterface)
        {
            $this->_adapter = $this->getService($this->_adapter);

            if(!$this->_adapter instanceof KDatabaseAdapterInterface)
            {
                throw new \UnexpectedValueException(
                    'Adapter: '.get_class($this->_adapter).' does not implement KDatabaseAdapterInterface'
                );
            }
        }

        return $this->_adapter;
    }

    /**
     * Set the database adapter
     *
     * @param KDatabaseAdpaterInterface $adapter
     * @return KDatabaseQueryInterface
     */
    public function setAdapter(KDatabaseAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Test the connected status of the table
     *
     * @return    boolean    Returns TRUE if we have a reference to a live KDatabaseAdapterAbstract object.
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
     * @return  object|null Returns a KDatabaseSchemaTable object or NULL if the table doesn't exists
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
     * @param  boolean  If TRUE, get the column information from the base table.
     * @return KDatabaseColumn  Returns a KDatabaseSchemaColumn object or NULL if the column does not exist
     */
    public function getColumn($columnname, $base = false)
    {
        $columns = $this->getColumns($base);
        return isset($columns[$columnname]) ? $columns[$columnname] : null;
    }

    /**
     * Gets the columns for the table
     *
     * @param   boolean  If TRUE, get the column information from the base table.
     * @return  array    Associative array of KDatabaseSchemaColumn objects
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
     * @param  array|string An associative array of data to be mapped, or a column name
     * @param  boolean      If TRUE, perform a reverse mapping
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
     * @return KDatabaseTableAbstract
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
     * @return mixed    Returns the column default value or NULL if the
     *                  column does not exist
     */
    public function getDefault($columnname)
    {
        $defaults = $this->getDefaults();
        return isset($defaults[$columnname]) ? $defaults[$columnname] : null;
    }

    /**
     * Get an instance of a row object for this table
     *
     * @param    array An optional associative array of configuration settings.
     * @return  KDatabaseRowInterface
     */
    public function getRow(array $options = array())
    {
        $identifier = clone $this->getIdentifier();
        $identifier->path = array('database', 'row');
        $identifier->name = KInflector::singularize($this->getIdentifier()->name);

        //Force the table
        $options['table'] = $this;

        //Set the identity column if not set already
        if (!isset($options['identity_column'])) {
            $options['identity_column'] = $this->mapColumns($this->getIdentityColumn(), true);
        }

        return $this->getService($identifier, $options);
    }

    /**
     * Get an instance of a rowset object for this table
     *
     * @param    array An optional associative array of configuration settings.
     * @return  KDatabaseRowInterface
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

        return $this->getService($identifier, $options);
    }

    /**
     * Table select method
     *
     * This function will return an empty rowset if called without a parameter.
     *
     * @param mixed    $query   KDatabaseQuery, query string, array of row id's, or an id or null
     * @param integer  $mode    The database fetch style.
     * @param integer  $mode    The database fetch style.
     * @param array    $options An optional associative array of configuration options.
     * @return KDatabaseRow(set) depending on the mode.
     */
    public function select($query = null, $mode = KDatabase::FETCH_ROWSET, array $options = array())
    {
        //Create query object
        if (is_numeric($query) || is_string($query) || (is_array($query) && is_numeric(key($query))))
        {
            $key = $this->getIdentityColumn();
            $query = $this->getService('koowa:database.query.select')
                ->where('tbl.'.$key . ' IN :' . $key)
                ->bind(array($key => (array)$query));
        }

        if (is_array($query) && !is_numeric(key($query)))
        {
            $columns = $this->mapColumns($query);
            $query = $this->getService('koowa:database.query.select');

            foreach ($columns as $column => $value)
            {
                $query->where('tbl.'.$column . ' ' . (is_array($value) ? 'IN' : '=') . ' :' . $column)
                      ->bind(array($column => $value));
            }
        }

        if ($query instanceof KDatabaseQuerySelect)
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
        $context->operation = KDatabase::OPERATION_SELECT;
        $context->table     = $this->getBase();
        $context->query     = $query;
        $context->mode      = $mode;
        $context->options   = $options;

        if ($this->getCommandChain()->run('before.select', $context) !== false)
        {
            if ($context->query)
            {
                if($context->mode == KDatabase::FETCH_ARRAY_LIST || $context->mode == KDatabase::FETCH_OBJECT_LIST) {
                    $key = $this->getIdentityColumn();
                } else {
                    $key = null;
                }

                $data = $this->getAdapter()->select($context->query, $context->mode, $key);

                //Map the columns
                if (($context->mode != KDatabase::FETCH_FIELD) && ($context->mode != KDatabase::FETCH_FIELD_LIST))
                {
                    if ($context->mode % 2)
                    {
                        foreach ($data as $key => $value) {
                            $data[$key] = $this->mapColumns($value, true);
                        }
                    }
                    else $data = $this->mapColumns(KConfig::unbox($data), true);
                }
            }

            switch ($context->mode)
            {
                case KDatabase::FETCH_ROW    :
                {
                    if (isset($data) && !empty($data))
                    {
                        $options['data'] = $data;
                        $options['new'] = false;
                        $options['status'] = KDatabase::STATUS_LOADED;
                    }

                    $context->data = $this->getRow($options);
                    break;
                }

                case KDatabase::FETCH_ROWSET :
                {
                    if (isset($data) && !empty($data))
                    {
                        $options['data'] = $data;
                        $options['new'] = false;
                    }

                    $context->data = $this->getRowset($options);
                    break;
                }

                default :
                    $context->data = $data;
            }

            $this->getCommandChain()->run('after.select', $context);
        }

        return KConfig::unbox($context->data);
    }

    /**
     * Count table rows
     *
     * @param   mixed KDatabaseQuery object or query string or null to count all rows
     * @param   array $options An optional associative array of configuration options.
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
            $query = $this->getService('koowa:database.query.select');

            foreach ($columns as $column => $value)
            {
                $query->where($column . ' ' . (is_array($value) ? 'IN' : '=') . ' :' . $column)
                      ->bind(array($column => $value));
            }
        }

        if ($query instanceof KDatabaseQuerySelect)
        {
            if (!$query->columns) {
                $query->columns('COUNT(*)');
            }

            if (!$query->table) {
                $query->table(array('tbl' => $this->getName()));
            }
        }

        $result = (int)$this->select($query, KDatabase::FETCH_FIELD, $options);
        return $result;
    }

    /**
     * Table insert method
     *
     * @param  object       A KDatabaseRow object
     * @return bool|integer Returns the number of rows inserted, or FALSE if insert query was not executed.
     */
    public function insert(KDatabaseRowInterface $row)
    {
        // Create query object.
        $query = $this->getService('koowa:database.query.insert')
                      ->table($this->getBase());

        //Create commandchain context
        $context = $this->getCommandContext();
        $context->operation = KDatabase::OPERATION_INSERT;
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
                    if ($this->getIdentityColumn()) {
                        $data[$this->getIdentityColumn()] = $this->getAdapter()->getInsertId();
                    }

                    $context->data->setData($this->mapColumns($data, true))->setStatus(KDatabase::STATUS_CREATED);
                }
                else $context->data->setStatus(KDatabase::STATUS_FAILED);
            }

            $this->getCommandChain()->run('after.insert', $context);
        }

        return $context->affected;
    }

    /**
     * Table update method
     *
     * @param  object           A KDatabaseRow object
     * @return boolean|integer  Returns the number of rows updated, or FALSE if insert query was not executed.
     */
    public function update(KDatabaseRowTable $row)
    {
        // Create query object.
        $query = $this->getService('koowa:database.query.update')
                      ->table($this->getBase());

        // Create commandchain context.
        $context = $this->getCommandContext();
        $context->operation = KDatabase::OPERATION_UPDATE;
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
                    $context->data->setData($this->mapColumns($data, true), true)->setStatus(KDatabase::STATUS_UPDATED);
                } else {
                    $context->data->setStatus(KDatabase::STATUS_FAILED);
                }
            }

            $this->getCommandChain()->run('after.update', $context);
        }

        return $context->affected;
    }

    /**
     * Table delete method
     *
     * @param  object       A KDatabaseRow object
     * @return bool|integer Returns the number of rows deleted, or FALSE if delete query was not executed.
     */
    public function delete(KDatabaseRowInterface $row)
    {
        // Create query object.
        $query = $this->getService('koowa:database.query.delete')
                      ->table($this->getBase());

        //Create commandchain context
        $context = $this->getCommandContext();
        $context->operation = KDatabase::OPERATION_DELETE;
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
                $context->data->setStatus($context->affected ? KDatabase::STATUS_DELETED : KDatabase::STATUS_FALIED);
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
     * @param  array    An associative array of data to be filtered
     * @param  boolean  If TRUE, get the column information from the base table.
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
     * @param  string     The function name
     * @param  array      The function arguments
     * @throws BadMethodCallException     If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        // If the method is of the form is[Bahavior] handle it.
        $parts = KInflector::explode($method);

        if ($parts[0] == 'is' && isset($parts[1]))
        {
            if(!$this->hasBehavior(strtolower($parts[1]))) {
                return false;
            }
        }

        return parent::__call($method, $arguments);
    }
}
