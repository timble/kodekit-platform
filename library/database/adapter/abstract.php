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
 * Abstract Database Adapter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
abstract class DatabaseAdapterAbstract extends Object implements DatabaseAdapterInterface
{
    /**
     * Active state of the connection
     *
     * @var boolean
     */
    protected $_connected = null;

    /**
     * The database connection resource
     *
     * @var mixed
     */
    protected $_connection = null;

    /**
     * Last auto-generated insert_id
     *
     * @var integer
     */
    protected $_insert_id;

    /**
     * The affected row count
     *
     * @var int
     */
    protected $_affected_rows;

    /**
     * Schema cache
     *
     * @var array
     */
    protected $_table_schema = null;

    /**
     * The table needle
     *
     * @var string
     */
    protected $_table_needle = '';

    /**
     * Quote for query identifiers
     *
     * @var string
     */
    protected $_identifier_quote = '`';

    /**
     * The connection options
     *
     * @var ObjectConfig
     */
    protected $_options = null;
    
    /**
     * Character set used for connection
     * 
     * @var string
     */
    protected $_charset;

    /**
     * Constructor.
     *
     * @param     object     An optional ObjectConfig object with configuration options.
     * Recognized key values include 'command_chain', 'charset',
     * (this list is not meant to be comprehensive).
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        // Set the connection
        if (isset($config->connection)) {
            $this->setConnection($config->connection);
        }

        // Set the default charset. http://dev.mysql.com/doc/refman/5.1/en/charset-connection.html
        if (!empty($config->charset)) {
            $this->setCharset($config->charset);
        }

        // Set the connection options
        $this->_options = $config->options;

        // Mixin the command interface
        $this->mixin('lib:command.mixin', $config);
    }

    /**
     * Destructor
     *
     * Free any resources that are open.
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param     object     An optional ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'options'          => array(),
            'charset'          => 'UTF8',
            'command_chain'    => 'lib:command.chain',
            'dispatch_events'  => true,
            'event_dispatcher' => 'event.dispatcher',
            'enable_callbacks' => false,
            'connection'       => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Reconnect to the db
     *
     * @return  DatabaseAdapterAbstract
     */
    public function reconnect()
    {
        $this->disconnect();
        $this->connect();

        return $this;
    }

    /**
     * Disconnect from db
     *
     * @return  DatabaseAdapterAbstract
     */
    public function disconnect()
    {
        $this->_connection = null;
        $this->_connected = false;

        return $this;
    }

    /**
     * Get the database name
     *
     * @return string    The database name
     */
    abstract function getDatabase();

    /**
     * Set the database name
     *
     * @param     string     The database name
     * @return  DatabaseAdapterAbstract
     */
    abstract function setDatabase($database);

    /**
     * Get the connection
     *
     * Provides access to the underlying database connection. Useful for when
     * you need to call a proprietary method such as postgresql's lo_* methods
     *
     * @return resource
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * Set the connection
     *
     * @param     resource     The connection resource
     * @return  DatabaseAdapterAbstract
     */
    public function setConnection($resource)
    {
        $this->_connection = $resource;
        return $this;
    }
    
    /**
     * Get character set
     * 
     * @return string
     */
    public function getCharset()
    {
        return $this->_charset;
    }
    
    /**
     * Set character set
     * 
     * @param string $charset The character set.
     * @return DatabaseAdapterAbstract
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
        
        return $this;
    }

    /**
     * Get the insert id of the last insert operation
     *
     * @return mixed The id of the last inserted row(s)
     */
    public function getInsertId()
    {
        return $this->_insert_id;
    }

    /**
     * Preform a select query.
     *
     * Use for SELECT and anything that returns rows.
     *
     * @param    DatabaseQuerySelect The query object.
     * @param   integer    The fetch mode. Controls how the result will be returned to the subject. This
     *                     value must be one of the Database::FETCH_* constants.
     * @param   string     The column name of the index to use.
     * @throws  \InvalidArgumentException If the query is not an instance of DatabaseQuerySelect or DatabaseQueryShow
     * @return  mixed     The return value of this function on success depends on the fetch type.
     *                    In all cases, FALSE is returned on failure.
     */
    public function select(DatabaseQueryInterface $query, $mode = Database::FETCH_ARRAY_LIST, $key = '')
    {
        if (!$query instanceof DatabaseQuerySelect && !$query instanceof DatabaseQueryShow) {
            throw new \InvalidArgumentException('Query must be an instance of DatabaseQuerySelect or DatabaseQueryShow');
        }

        $context  = $this->getCommandContext();
        $context->query     = $query;
        $context->operation = Database::OPERATION_SELECT;
        $context->mode      = $mode;

        if ($this->getCommandChain()->run('before.select', $context) !== false)
        {
            if ($result = $this->execute($context->query, Database::RESULT_USE))
            {
                switch ($context->mode)
                {
                    case Database::FETCH_ARRAY       :
                        $context->result = $this->_fetchArray($result);
                        break;

                    case Database::FETCH_ARRAY_LIST  :
                        $context->result = $this->_fetchArrayList($result, $key);
                        break;

                    case Database::FETCH_FIELD       :
                        $context->result = $this->_fetchField($result, $key);
                        break;

                    case Database::FETCH_FIELD_LIST  :
                        $context->result = $this->_fetchFieldList($result, $key);
                        break;

                    case Database::FETCH_OBJECT      :
                        $context->result = $this->_fetchObject($result);
                        break;

                    case Database::FETCH_OBJECT_LIST :
                        $context->result = $this->_fetchObjectList($result, $key);
                        break;

                    default :
                        $result->free();
                }
            }

            $this->getCommandChain()->run('after.select', $context);
        }

        return ObjectConfig::unbox($context->result);
    }

    /**
     * Insert a row of data into a table.
     *
     * @param  DatabaseQueryInsert The query object.
     * @return bool|integer  If the insert query was executed returns the number of rows updated, or 0 if
     *                          no rows where updated, or -1 if an error occurred. Otherwise FALSE.
     */
    public function insert(DatabaseQueryInsert $query)
    {
        $context = $this->getCommandContext();
        $context->operation = Database::OPERATION_INSERT;
        $context->query = $query;

        if ($this->getCommandChain()->run('before.insert', $context) !== false)
        {
            //Check if we have valid data to insert, if not return false
            if ($context->query->values)
            {
                //Execute the query
                $context->result = $this->execute($context->query);
                $context->affected = $this->_affected_rows;

                $this->getCommandChain()->run('after.insert', $context);
            }
            else $context->affected = false;
        }

        return $context->affected;
    }

    /**
     * Update a table with specified data.
     *
     * @param  DatabaseQueryUpdate The query object.
     * @return integer  If the update query was executed returns the number of rows updated, or 0 if
     *                     no rows where updated, or -1 if an error occurred. Otherwise FALSE.
     */
    public function update(DatabaseQueryUpdate $query)
    {
        $context = $this->getCommandContext();
        $context->operation = Database::OPERATION_UPDATE;
        $context->query     = $query;

        if ($this->getCommandChain()->run('before.update', $context) !== false)
        {
            if (!empty($context->query->values))
            {
                //Execute the query
                $context->result = $this->execute($context->query);
                $context->affected = $this->_affected_rows;

                $this->getCommandChain()->run('after.update', $context);
            }
            else $context->affected = false;
        }

        return $context->affected;
    }

    /**
     * Delete rows from the table.
     *
     * @param  DatabaseQueryDelete The query object.
     * @return integer     Number of rows affected, or -1 if an error occured.
     */
    public function delete(DatabaseQueryDelete $query)
    {
        $context = $this->getCommandContext();
        $context->operation = Database::OPERATION_DELETE;
        $context->query     = $query;

        if ($this->getCommandChain()->run('before.delete', $context) !== false)
        {
            //Execute the query
            $context->result = $this->execute($context->query);
            $context->affected = $this->_affected_rows;

            $this->getCommandChain()->run('after.delete', $context);
        }

        return $context->affected;
    }

    /**
     * Safely quotes a value for an SQL statement.
     *
     * If an array is passed as the value, the array values are quoted
     * and then returned as a comma-separated string; this is useful
     * for generating IN() lists.
     *
     * @param   mixed The value to quote.
     * @return string An SQL-safe quoted value (or a string of separated-
     *                and-quoted values).
     */
    public function quoteValue($value)
    {
        if (is_array($value))
        {
            //Quote array values, not keys, then combine with commas.
            foreach ($value as &$v)
            {
                if (is_null($v)) {
                    $v = 'NULL';
                } elseif (is_string($v)) {
                    $v = $this->_quoteValue($v);
                }
            }

            $value = implode(', ', $value);
        }
        else
        {
            if (is_null($value)) {
                $value = 'NULL';
            } elseif (is_string($value)) {
                $value = $this->_quoteValue($value);
            }
        }

        return $value;
    }

    /**
     * Quotes a single identifier name (table, table alias, table column,
     * index, sequence).  Ignores empty values.
     *
     * This function requires all SQL statements, operators and functions to be
     * uppercased.
     *
     * @param string|array The identifier name to quote.  If an array, quotes
     *                      each element in the array as an identifier name.
     * @return string|array The quoted identifier name (or array of names).
     *
     * @see _quoteIdentifier()
     */
    public function quoteIdentifier($spec)
    {
        if (is_array($spec))
        {
            foreach ($spec as $key => $val) {
                $spec[$key] = $this->quoteIdentifier($val);
            }

            return $spec;
        }

        // String spaces around the identifier
        $spec = trim($spec);

        // Quote all the lower case parts
        $spec = preg_replace_callback('/(?:\b|#)+(?<![`:@])([-a-zA-Z0-9.#_]*[a-z][-a-zA-Z0-9.#_]*)(?!`)\b/', array($this, '_quoteIdentifier'), $spec);

        return $spec;
    }

    /**
     * Fetch the first field of the first row
     *
     * @param   mysqli_result   The result object. A result set identifier returned by the select() function
     * @param   integer         The index to use
     * @return The value returned in the query or null if the query failed.
     */
    abstract protected function _fetchField($result, $key = 0);

    /**
     * Fetch an array of single field results
     *
     * @param   mysqli_result   The result object. A result set identifier returned by the select() function
     * @param   integer         The index to use
     * @return  array           A sequential array of returned rows.
     */
    abstract protected function _fetchFieldList($result, $key = 0);

    /**
     * Fetch the first row of a result set as an associative array
     *
     * @param   mysqli_result   The result object. A result set identifier returned by the select() function
     * @return array
     */
    abstract protected function _fetchArray($sql);

    /**
     * Fetch all result rows of a result set as an array of associative arrays
     *
     * If <var>key</var> is not empty then the returned array is indexed by the value
     * of the database key.  Returns <var>null</var> if the query fails.
     *
     * @param   mysqli_result   The result object. A result set identifier returned by the select() function
     * @param   string          The column name of the index to use
     * @return  array   If key is empty as sequential list of returned records.
     */
    abstract protected function _fetchArrayList($result, $key = '');

    /**
     * Fetch the first row of a result set as an object
     *
     * @param   mysqli_result  The result object. A result set identifier returned by the select() function
     * @param object
     */
    abstract protected function _fetchObject($result);

    /**
     * Fetch all rows of a result set as an array of objects
     *
     * If <var>key</var> is not empty then the returned array is indexed by the value
     * of the database key.  Returns <var>null</var> if the query fails.
     *
     * @param   mysqli_result  The result object. A result set identifier returned by the select() function
     * @param   string         The column name of the index to use
     * @return  array   If <var>key</var> is empty as sequential array of returned rows.
     */
    abstract protected function _fetchObjectList($result, $key = '');

    /**
     * Parse the raw table schema information
     *
     * @param   object  The raw table schema information
     * @return DatabaseSchemaTable
     */
    abstract protected function _parseTableInfo($info);

    /**
     * Parse the raw column schema information
     *
     * @param   object  The raw column schema information
     * @return DatabaseSchemaColumn
     */
    abstract protected function _parseColumnInfo($info);

    /**
     * Given a raw column specification, parse into datatype, size, and decimal scope.
     *
     * @param string The column specification; for example,
     * "VARCHAR(255)" or "NUMERIC(10,2)".
     *
     * @return array A sequential array of the column type, size, and scope.
     */
    abstract protected function _parseColumnType($spec);

    /**
     * Safely quotes a value for an SQL statement.
     *
     * @param   mixed   The value to quote
     * @return string An SQL-safe quoted value
     */
    abstract protected function _quoteValue($value);

    /**
     * Quotes an identifier name (table, index, etc). Ignores empty values.
     *
     * If the name contains a dot, this method will separately quote the
     * parts before and after the dot.
     *
     * @param string    The identifier name to quote.
     * @return string   The quoted identifier name.
     * @see quoteIdentifier()
     */
    protected function _quoteIdentifier($name)
    {
        $result = '';

        if (is_array($name)) {
            $name = $name[0];
        }

        $name = trim($name);

        //Special cases
        if ($name == '*' || is_numeric($name)) {
            return $name;
        }

        if ($pos = strrpos($name, '.'))
        {
            $table = $this->_quoteIdentifier(substr($name, 0, $pos));
            $column = $this->_quoteIdentifier(substr($name, $pos + 1));

            $result = "$table.$column";
        }
        else $result = $this->_identifier_quote . $name . $this->_identifier_quote;

        return $result;
    }
}
