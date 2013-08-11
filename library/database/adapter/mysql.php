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
 * MySQL Database Adapter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Library\Database
 */
class DatabaseAdapterMysql extends DatabaseAdapterAbstract
{
    /**
     * Quote for query identifiers
     *
     * @var string
     */
    protected $_identifier_quote = '`';

    /**
     * The database name of the active connection
     *
     * @var string
     */
    protected $_database;

    /**
     * Map of native MySQL types to generic types used when reading
     * table column information.
     *
     * @var array
     */
    protected $_type_map = array(
        // numeric
        'smallint'  => 'int',
        'int'       => 'int',
        'integer'   => 'int',
        'bigint'    => 'int',
        'mediumint' => 'int',
        'smallint'  => 'int',
        'tinyint'   => 'int',
        'numeric'   => 'numeric',
        'dec'       => 'numeric',
        'decimal'   => 'numeric',
        'float'     => 'float',
        'double'    => 'float',
        'real'      => 'float',

        // boolean
        'bool'    => 'boolean',
        'boolean' => 'boolean',

        // date and time
        'date'      => 'date',
        'time'      => 'time',
        'datetime'  => 'timestamp',
        'timestamp' => 'int',
        'year'      => 'int',

        // string
        'national char'    => 'string',
        'nchar'            => 'string',
        'char'             => 'string',
        'binary'           => 'string',
        'national varchar' => 'string',
        'nvarchar'         => 'string',
        'varchar'          => 'string',
        'varbinary'        => 'string',
        'text'             => 'string',
        'mediumtext'       => 'string',
        'tinytext'         => 'string',
        'longtext'         => 'string',

        // blob
        'blob'       => 'raw',
        'tinyblob'   => 'raw',
        'mediumblob' => 'raw',
        'longtext'   => 'raw',
        'longblob'   => 'raw',

        // other
        'set'  => 'string',
        'enum' => 'string'
    );

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
        $config->append(array(
            'options' => array(
                'username' => null,
                'password' => null,
                'database' => null,
                'host'     => null,
                'port'     => null,
                'socket'   => null
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Connects to the database
     *
     * @throws DatabaseAdapterException  If connection failed.
     * @return DatabaseAdapterMysql
     */
    public function connect()
    {
        $options = $this->_options;
        $dsn     = 'mysql:dbname='.$options->database.';charset=utf8';

        if($options->host)
        {
            $dsn .= ';host='.$options->host;

            if($options->port) {
                $dsn .= ';port='.$options->port;
            }
        }
        elseif($options->socket) {
            $dsn .= ';socket='.$options->socket;
        }

        $dbh = new \PDO($dsn, $options->username, $options->password, array(
            \PDO::ATTR_PERSISTENT => true,
        ));

        $this->_connection = $dbh;
        $this->_connected  = true;
        $this->_database   = $options->database;

        return $this;
    }

    /**
     * Disconnects from database
     *
     * @return DatabaseAdapterMysql
     */
    public function disconnect()
    {
        if($this->isConnected())
        {
            $this->_connection = null;
            $this->_connected  = false;
        }

        return $this;
    }

    /**
     * Turns off autocommit mode
     *
     * @return  boolean  Returns TRUE on success or FALSE on failure.
     */
    public function begin()
    {
        // Create command chain context.
        $context = $this->getCommandContext();

        if($this->getCommandChain()->run('before.begin', $context) !== false)
        {
            $context->result = $this->getConnection()->beginTransaction();
            $this->getCommandChain()->run('after.begin', $context);
        }

        return $context->result;
    }

    /**
     * Commits a transaction
     *
     * @return  boolean  Returns TRUE on success or FALSE on failure.
     */
    public function commit()
    {
        // Create command chain context.
        $context = $this->getCommandContext();

        if($this->getCommandChain()->run('before.commit', $context) !== false)
        {
            $context->result = $this->getConnection()->commit();
            $this->getCommandChain()->run('after.commit', $context);
        }

        return $context->result;
    }

    /**
     * Rolls back a transaction
     *
     * @return  boolean  Returns TRUE on success or FALSE on failure.
     */
    public function rollback()
    {
        // Create command chain context.
        $context = $this->getCommandContext();

        if($this->getCommandChain()->run('before.rollback', $context) !== false)
        {
            $context->result = $this->getConnection()->rollBack();
            $this->getCommandChain()->run('after.rollback', $context);
        }

        return $context->result;
    }

    /**
     * Locks a table
     *
     * @param   string $table  The name of the table.
     * @return  boolean  TRUE on success, FALSE otherwise.
     */
    public function lock($table)
    {
        $query = 'LOCK TABLES '.$this->quoteIdentifier($table).' WRITE';

        // Create command chain context.
        $context = $this->getCommandContext();
        $context->table = $table;
        $context->query = $query;

        if($this->getCommandChain()->run('before.lock', $context) !== false)
        {
            $context->result = $this->execute($context->query, Database::RESULT_USE);
            $this->getCommandChain()->run('after.lock', $context);
        }

        return $context->result;
    }

    /**
     * Unlocks tables
     *
     * @return  boolean  TRUE on success, FALSE otherwise.
     */
    public function unlock()
    {
        $query = 'UNLOCK TABLES';

        // Create command chain context.
        $context = $this->getCommandContext();
        $context->table = null;
        $context->query = $query;

        if($this->getCommandChain()->run('before.unlock', $context) !== false)
        {
            $context->result = $this->execute($context->query, Database::RESULT_USE);
            $this->getCommandChain()->run('after.unlock', $context);
        }

        return $context->result;
    }

    /**
     * Executes queries
     *
     * @param  string  $query  The query to run. Data inside the query should be properly escaped.
     * @param  integer $mode   The result mode, either the constant Database::RESULT_USE or Database::RESULT_STORE
     *                         depending on the desired behavior. By default, Database::RESULT_STORE is used. If you
     *                         use Database::RESULT_USE all subsequent calls will return error Commands out of sync
     *                         unless you free the result first.
     *
     * @throws \RuntimeException If the query could not be executed
     * @return object|boolean  For SELECT, SHOW, DESCRIBE or EXPLAIN will return a result object.
     *                         For other successful queries return TRUE.
     */
    public function execute($query, $mode = Database::RESULT_STORE)
    {
        $dbh    = $this->getConnection();
        $result = $dbh->query((string) $query, $mode);

        if($result === false)
        {
            $error = $dbh->errorInfo();
            throw new \RuntimeException($error[2].' of the following query: '.$query, $error[1]);
        }

        $this->_affected_rows = $result->rowCount();
        $this->_insert_id     = $dbh->lastInsertId();

        return $result;
    }

    /**
     * Set the connection
     *
     * @param   $connection  The connection object.
     *
     * @throws  \InvalidArgumentException If the resource is not a PDO instance.
     * @return  DatabaseAdapterMysql
     */
    public function setConnection($connection)
    {
        if(!$connection instanceof \PDO) {
            throw new \InvalidArgumentException('Not a PDO instance');
        }

        $this->_connection = $connection;
        return $this;
    }

    /**
     * Gets the database name
     *
     * @return string  The database name.
     */
    public function getDatabase()
    {
        if(!isset($this->_database))
        {
            $query = $this->getObject('lib:database.query.select')
                ->columns('DATABASE');

            $this->_database = $this->select($query, Database::FETCH_FIELD);
        }

        return $this->_database;
    }

    /**
     * Sets the database name
     *
     * @param   string $database  The database name.
     * @throws  \RuntimeException If the database could not be set
     * @return  DatabaseAdapterMysql
     */
    public function setDatabase($database)
    {
        try {
            $this->execute('USE '.$this->quoteIdentifier($database));
        } catch(\RuntimeException $e) {
            throw new \RuntimeException('Could not connect to database : ' . $database);
        }

        $this->_database = $database;
        return $this;
    }

    /**
     * Retrieves the table schema information about the given table
     *
     * @param   string  $table  A table name.
     * @return  DatabaseSchemaTable
     */
    public function getTableSchema($table)
    {
        if(!isset($this->_table_schema[$table]))
        {
            $this->_table_schema[$table] = $this->_fetchTableInfo($table);

            $this->_table_schema[$table]->indexes = $this->_fetchTableIndexes($table);
            $this->_table_schema[$table]->columns = $this->_fetchTableColumns($table);
        }

        return $this->_table_schema[$table];
    }

    /**
     * Checks if the connection is active
     *
     * @return boolean
     */
    public function isConnected()
    {
        if($this->_connected)
        {
            if($this->_connection instanceof \PDO) {
                $this->_connected = (bool) $this->_connection->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
            } else {
                $this->_connected = false;
            }
        }

        return $this->_connected;
    }

    /**
     * Checks if inside a transaction
     *
     * @return  boolean  Returns TRUE if a transaction is currently active, and FALSE if not.
     */
    public function inTransaction()
    {
        return $this->getConnection()->inTransaction();
    }

    /**
     * Retrieves the table schema information about the given tables
     *
     * @param   string $table  A table name.
     * @return  DatabaseSchemaTable or null if the table doesn't exist.
     */
    protected function _fetchTableInfo($table)
    {
        $return = null;
        $query  = $this->getObject('lib:database.query.show')
            ->show('TABLE STATUS')
            ->like(':like')
            ->bind(array('like' => $table));

        if($info = $this->select($query, Database::FETCH_OBJECT)) {
            $return = $this->_parseTableInfo($info);
        }

        return $return;
    }

    /**
     * Retrieves the column schema information about the given table
     *
     * @param   string  $table  A table name.
     * @return  array   An array of columns.
     */
    protected function _fetchTableColumns($table)
    {
        $return = array();
        $query  = $this->getObject('lib:database.query.show')
            ->show('FULL COLUMNS')
            ->from($table);

        if($columns = $this->select($query, Database::FETCH_OBJECT_LIST))
        {
            foreach($columns as $column)
            {
                // Set the table name in the raw info (MySQL doesn't add this).
                $column->Table = $table;

                $column = $this->_parseColumnInfo($column, $table);
                $return[$column->name] = $column;
            }
        }

        return $return;
    }

    /**
     * Retrieves the index information about the given table
     *
     * @param   string  $table  A table name.
     * @return  array   An associative array of indexes by index name.
     */
    protected function _fetchTableIndexes($table)
    {
        $return = array();
        $query  = $this->getObject('lib:database.query.show')
            ->show('INDEX')
            ->from($table);

        if($indexes = $this->select($query, Database::FETCH_OBJECT_LIST))
        {
            foreach($indexes as $index) {
                $return[$index->Key_name][$index->Seq_in_index] = $index;
            }
        }

        return $return;
    }

    /**
     * Parses the raw table schema information
     *
     * @param   object  $info  The raw table schema information.
     * @return  DatabaseSchemaTable
     */
    protected function _parseTableInfo($info)
    {
        $table              = $this->getObject('lib:database.schema.table');
        $table->name        = $info->Name;
        $table->engine      = $info->Engine;
        $table->type        = $info->Comment == 'VIEW' ? 'VIEW' : 'BASE';
        $table->length      = $info->Data_length;
        $table->autoinc     = $info->Auto_increment;
        $table->collation   = $info->Collation;
        $table->behaviors   = array();
        $table->description = $info->Comment != 'VIEW' ? $info->Comment : '';

        return $table;
    }

    /**
     * Parses the raw column schema information
     *
     * @param   object  $info  The raw column schema information.
     * @return  DatabaseSchemaColumn
     */
    protected function _parseColumnInfo($info)
    {
        list($type, $length, $scope) = $this->_parseColumnType($info->Type);

        $column = $this->getObject('lib:database.schema.column');
        $column->name     = $info->Field;
        $column->type     = $type;
        $column->length   = $length ? $length : null;
        $column->scope    = $scope ? (int) $scope : null;
        $column->default  = $info->Default;
        $column->required = $info->Null != 'YES';
        $column->primary  = $info->Key == 'PRI';
        $column->unique   = ($info->Key == 'UNI' || $info->Key == 'PRI');
        $column->autoinc  = strpos($info->Extra, 'auto_increment') !== false;
        $column->filter   = $this->_type_map[$type];

        // Don't keep "size" for integers.
        if(substr($type, -3) == 'int') {
            $column->length = null;
        }

        // Get the related fields if the column is primary key or part of a unique multi column index.
        if($indexes = $this->_table_schema[$info->Table]->indexes)
        {
            foreach($indexes as $index)
            {
                // We only deal with composite-unique indexes.
                if(count($index) > 1 && !$index[1]->Non_unique)
                {
                    $fields = array();
                    foreach($index as $field) {
                        $fields[$field->Column_name] = $field->Column_name;
                    }

                    if(array_key_exists($column->name, $fields))
                    {
                        unset($fields[$column->name]);
                        $column->related = array_values($fields);
                        $column->unique = true;
                        break;
                    }
                }
            }
        }

        return $column;
    }

    /**
     * Given a raw column specification, parses into data type, length, and decimal scope.
     *
     * @param  string  $spec  The column specification; for example:
     * "VARCHAR(255)" or "NUMERIC(10,2)" or "float(6,2) UNSIGNED" or ENUM('yes','no','maybe').
     *
     * @return  array  A sequential array of the column type, size, and scope.
     */
    protected function _parseColumnType($spec)
    {
        $spec   = strtolower($spec);
        $length = null;
        $scope  = null;

        // Find the type first.
        $type = strtok($spec, '( ');

        // Find the parens, if any.
        if(($pos = strpos($spec, '(')) !== false)
        {
            // There were parens, so there's at least a length remove parens to get the size.
            $length = trim(substr(strtok($spec, ' '), $pos), '()');

            if($type != 'enum' && $type != 'set')
            {
                // A comma in the size indicates a scope.
                $pos = strpos($length, ',');
                if($pos !== false)
                {
                    $scope  = substr($length, $pos + 1);
                    $length = substr($length, 0, $pos);
                }

            }
            else $length = explode(',', str_replace('\'', '', $length));
        }

        return array($type, $length, $scope);
    }

    /**
     * Fetch the first field of the first row
     *
     * @param   object  $result  The result object.
     * @param   integer $key     The index to use.
     * @return  string  The value returned in the query.
     */
    protected function _fetchField($result, $key = 0)
    {
        $return = $result->fetchColumn((int) $key);
        $result = null;

        return $return;
    }

    /**
     * Fetches an array of single field results
     *
     * @param   object   $result  The result object.
     * @param   integer  $key     The index to use.
     * @return  array  A sequential array of returned rows.
     */
    protected function _fetchFieldList($result, $key = 0)
    {
        $return = $result->fetchAll(\PDO::FETCH_COLUMN, (int) $key);
        $result = null;

        return $return;
    }


    /**
     * Fetches the first row of a result set as an associative array
     *
     * @param   object $result  The result object.
     * @return  array
     */
    protected function _fetchArray($result)
    {
        $return = $result->fetch(\PDO::FETCH_ASSOC);
        $result = null;

        return $return;
    }

    /**
     * Fetches all result rows of a result set as an array of associative arrays
     *
     * If <var>key</var> is not empty then the returned array is indexed by the value
     * of the database key. Returns <var>null</var> if the query fails.
     *
     * @param   object $result  The result object.
     * @param   string $key     The column name of the index to use.
     * @return  array  If key is empty as sequential list of returned records.
     */
    protected function _fetchArrayList($result, $key = '')
    {
        $return = array();
        foreach($result->fetchAll(\PDO::FETCH_ASSOC) as $row)
        {
            if($key) {
                $return[$row[$key]] = $row;
            } else {
                $return[] = $row;
            }
        }

        $result = null;
        return $return;
    }

    /**
     * Fetches the first row of a result set as an object
     *
     * @param   object $result  The result object.
     * @return  object
     */
    protected function _fetchObject($result)
    {
        $return = $result->fetchObject();
        $result = null;

        return $return;
    }

    /**
     * Fetches all rows of a result set as an array of objects
     *
     * If <var>key</var> is not empty then the returned array is indexed by the value
     * of the database key. Returns <var>null</var> if the query fails.
     *
     * @param   object $result  The result object.
     * @param   string $key     The column name of the index to use.
     * @return  array  If <var>key</var> is empty as sequential array of returned rows.
     */
    protected function _fetchObjectList($result, $key = '')
    {
        $return = array();
        foreach($result->fetchAll(\PDO::FETCH_OBJ) as $row)
        {
            if($key) {
                $return[$row->$key] = $row;
            } else {
                $return[] = $row;
            }
        }

        $result = null;
        return $return;
    }

    /**
     * Safely quotes a value for an SQL statement
     *
     * @param   mixed $value  The value to quote.
     * @return  string  An SQL-safe quoted value.
     */
    protected function _quoteValue($value)
    {
        $value = $this->getConnection()->quote($value);
        return $value;
    }
}