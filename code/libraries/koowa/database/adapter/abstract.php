<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Database Adapter
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @uses 		KPatternCommandChain
 */
abstract class KDatabaseAdapterAbstract extends KObject implements KObjectIdentifiable
{
	/**
	 * Active state of the connection
	 *
	 * @var boolean
	 */
	protected $_active = null;

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
	 * The table prefix
	 *
	 * @var string
	 */
	protected $_table_prefix = '';

	/**
	 * Quote for named objects
	 *
	 * @var string
	 */
	protected $_name_quote = '`';

	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 * Recognized key values include 'command_chain', 'charset', 'table_prefix',
	 * (this list is not meant to be comprehensive).
	 */
	public function __construct( KConfig $config = null )
	{
        //If no config is passed create it
		if(!isset($config)) $config = new KConfig();
		
		// Initialize the options
        parent::__construct($config);

		// Set the default charset. http://dev.mysql.com/doc/refman/5.1/en/charset-connection.html
		if (!empty($config->charset)) {
			//$this->setCharset($config->charset);
		}

		// Set the table prefix
		$this->_table_prefix = $config->table_prefix;
		
		 // Mixin the command chain
        $this->mixin(new KMixinCommandchain(new KConfig(
        	array('mixer' => $this, 'command_chain' => $config->command_chain)
        )));
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
     * @param 	object 	An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'command_chain' =>  new KCommandChain(),
        	'charset'		=> 'UTF-8',
        	'table_prefix'  => 'jos_'
        ));
        
        parent::_initialize($config);
    }
    
	/**
	 * Get the object identifier
	 * 
	 * @return	KIdentifier	
	 * @see 	KObjectIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Get a database query object
	 *
	 * @return KDatabaseQuery
	 */
	public function getQuery(KConfig $config = null)
	{
		if(!isset($config)) {
			$config = new KConfig(array('adapter', $this));
		}
		
		return new KDatabaseQuery($config);
	}

	/**
	 * Connect to the db
	 * 
	 * @return  KDatabaseAdapterAbstract
	 */
	abstract public function connect();

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @return      boolean
	 */
	abstract public function active();

	/**
	 * Reconnect to the db
	 * 
	 * @return  KDatabaseAdapterAbstract
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
	 * @return  KDatabaseAdapterAbstract
	 */
	public function disconnect()
	{
		$this->_connection = null;
		$this->_active = false;
		
		return $this;
	}

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
	 * @param 	resource 	The connection resource
	 * @return  KDatabaseAdapterAbstract
	 */
	public function setConnection($resource)
	{
		$this->_connection = $resource;
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
	 * Fetch the first field of the first row
	 *
	 * @return scalar The value returned in the query or null if the query failed.
	 */
	abstract public function fetchField($sql);

	/**
	 * Fetch an array of single field results
	 *
	 * @return array
	 */
	abstract public function fetchFieldList($sql);

	/**
     * Fetch the current row as an associative array
     *
     * @param	string  The SQL query. Data inside the query should be properly escaped. 
     * @return array
     */
	abstract public function fetchArray($sql);

	/**
	 * Fetch all result rows as an array of associative arrays
	 *
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string  The SQL query. Data inside the query should be properly escaped. 
	 * @param 	string 	The column name of the index to use
	 * @return 	array 	If key is empty as sequential list of returned records.
	 */
	abstract public function fetchArrayList($sql, $key = '');

	/**
	 * Fetch the current row of a result set as an object
	 *
	 * @param	string  The SQL query. Data inside the query should be properly escaped. 
	 * @param object
	 */
	abstract public function fetchObject($sql);

	/**
	 * Fetch all rows of a result set as an array of objects
	 *
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * of the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @param	string  The SQL query. Data inside the query should be properly escaped. 
	 * @param 	string 	The column name of the index to use
	 * @return 	array 	If <var>key</var> is empty as sequential array of returned rows.
	 */
	abstract public function fetchObjectList($sql, $key='' );

	/**
	 * Retrieves the column schema information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 	An associative array of columns by table
	 */
	abstract public function fetchTableColumns($tables);
	
	/**
	 * Retrieves the table schema information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @return	array 	An associative array of table information by table
	 */
	abstract public function fetchTableInfo($tables);
	
	/**
     * Preforms a select query
     *
     * Use for SELECT and anything that returns rows.  
     *
     * @param	string  	A full SQL query to run. Data inside the query should be properly escaped. 
     * @param	integer 	The result maode, either the constant KDatabase::RESULT_USE or KDatabase::RESULT_STORE 
     * 						depending on the desired behavior. By default, KDatabase::RESULT_STORE is used. If you 
     * 						use KDatabase::RESULT_USE all subsequent calls will return error Commands out of sync 
     * 						unless you free the result first. 
     * @return  mixed 		If successfull returns a result object otherwise FALSE
     */
	public function select($sql, $mode = KDatabase::RESULT_STORE)
	{
		$context = $this->getCommandChain()->getContext();
		$context->caller    = $this;
		$context->sql	 	= $sql;
		$context->operation = KDatabase::OPERATION_SELECT;

		// Excute the insert operation
		if($this->getCommandChain()->run('database.before.select', $context) === true) {
			$context->result = $this->execute( $context->sql, $mode);
			$this->getCommandChain()->run('database.after.select', $context);
		}

		return $context->result;
	}

	/**
     * Inserts a row of data into a table.
     *
     * Automatically quotes the data values
     *
     * @param string  	The table to insert data into.
     * @param array 	An associative array where the key is the colum name and
     * 					the value is the value to insert for that column.
     * @return integer  If successfull the new rows primary key value, false is no row was inserted.
     */
	public function insert($table, array $data)
	{
		$context = $this->getCommandChain()->getContext();
		$context->caller    = $this;
		$context->table 	= $table;
		$context->data 		= $data;
		$context->operation	= KDatabase::OPERATION_INSERT;

		//Excute the insert operation
		if($this->getCommandChain()->run('database.before.insert', $context) === true)
		{
			//Check if we have valid data to insert, if not return false
			if(!empty($context->data)) 
			{
				foreach($context->data as $key => $val)
				{
					$vals[] = $this->quoteValue($val);
					$keys[] = '`'.$key.'`';
				}

				$sql = 'INSERT INTO '.$this->quoteName('#__'.$context->table )
					 . '('.implode(', ', $keys).') VALUES ('.implode(', ', $vals).')';
				 
				//Execute the query
				$this->execute($sql);
			
				$context->insert_id = $this->_insert_id;
				$this->getCommandChain()->run('database.after.insert', $context);
			}
			else $context->insert_id = false;
		}

		return $context->insert_id;
	}

	/**
     * Updates a table with specified data based on a WHERE clause
     *
     * Automatically quotes the data values
     *
     * @param string 	The table to update
     * @param array  	An associative array where the key is the column name and
     * 				 	the value is the value to use ofr that column.
     * @param mixed 	A sql string or KDatabaseQuery object to limit which rows are updated.
     * @return integer  If successfull the Number of rows affected, otherwise false
     */
	public function update($table, array $data, $where = null)
	{
		$context = $this->getCommandChain()->getContext();
		$context->caller    = $this;
		$context->table 	= $table;
		$context->data  	= $data;
		$context->where   	= $where;
		$context->operation	= KDatabase::OPERATION_UPDATE;

		//Excute the update operation
		if($this->getCommandChain()->run('database.before.update', $context) ===  true)
		{
			if(!empty($context->data)) 
			{				
				foreach($context->data as $key => $val) {
					$vals[] = '`'.$key.'` = '.$this->quoteValue($val);
				}
				
				//Create query statement
				$sql = 'UPDATE '.$this->quoteName('#__'.$context->table)
			  		.' SET '.implode(', ', $vals)
			  		.' '.$context->where
				;
						
				//Execute the query
				$this->execute($sql);

				$context->affected = $this->_affected_rows;
				$this->getCommandChain()->run('database.after.update', $context);
			}
			else $context->affected = false;
		}

        return $context->affected;
	}

	/**
     * Deletes rows from the table based on a WHERE clause.
     *
     * @param string The table to update
     * @param mixed  A query string or a KDatabaseQuery object to limit which rows are updated.
     * @return integer Number of rows affected
     */
	public function delete($table, $where)
	{
		$context = $this->getCommandChain()->getContext();
		$context->caller    = $this;
		$context->table 	= $table;
		$context->data  	= null;
		$context->where   	= $where;
		$context->operation	= KDatabase::OPERATION_DELETE;

		//Excute the delete operation
		if($this->getCommandChain()->run('database.before.delete', $context) ===  true)
		{
			//Create query statement
			$sql = 'DELETE FROM '.$this->quoteName('#__'.$context->table)
				  .' '.$context->where
			;

			//Execute the query
			$this->execute($sql);

			$context->affected = $this->_affected_rows;
			$this->getCommandChain()->run('database.after.delete', $context);
		}

		return $context->affected;
	}

	/**
	 * Use and other queries that don't return rows
	 *
	 * @param  string 	The query to run. Data inside the query should be properly escaped. 
	 * @param  integer 	The result maode, either the constant KDatabase::RESULT_USE or KDatabase::RESULT_STORE 
     * 					depending on the desired behavior. By default, KDatabase::RESULT_STORE is used. If you 
     * 					use KDatabase::RESULT_USE all subsequent calls will return error Commands out of sync 
     * 					unless you free the result first.
	 * @throws KDatabaseException
	 * @return boolean 	For SELECT, SHOW, DESCRIBE or EXPLAIN will return a result object. 
	 * 					For other successful queries  return TRUE. 
	 */
	public function execute($sql, $mode = KDatabase::RESULT_STORE )
	{	
		//Replace the database table prefix
		$sql = $this->replaceTablePrefix( $sql );
	
		$result = $this->_connection->query($sql, $mode);
		if($result === false) {
			throw new KDatabaseException($this->_connection->error.' of the following query : '.$sql, $this->_connection->errno);
		}

		$this->_affected_rows = $this->_connection->affected_rows;
		$this->_insert_id     = $this->_connection->insert_id;

		return $result;
	}

	/**
	 * Set the table prefix
	 *
	 * @param string The table prefix
	 * @return KDatabaseAdapterAbstract
	 */
	public function setTablePrefix($prefix)
	{
		$this->_table_prefix = $prefix;
		return $this;
	}

 	/**
	 * Get the table prefix
	 *
	 * @return string The table prefix
	 */
	public function getTablePrefix()
	{
		return $this->_table_prefix;
	}

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @param 	string 	The SQL query string
	 * @param 	string 	The table prefix to use as a replacement
	 * @param 	string 	The needle to search for in the query string
	 * @return string	The SQL query string
	 */
	public function replaceTablePrefix( $sql, $replace = null, $needle = '#__' )
	{
		$replace = $replace ? $replace : $this->getTablePrefix();
		$sql = trim( $sql );

		$escaped = false;
		$quoteChar = '';

		$n = strlen( $sql );

		$startPos = 0;
		$literal = '';
		while ($startPos < $n)
		{
			$ip = strpos($sql, $needle, $startPos);
			if ($ip === false) {
				break;
			}

			$j = strpos( $sql, "'", $startPos );
			$k = strpos( $sql, '"', $startPos );

			if (($k !== FALSE) && (($k < $j) || ($j === FALSE))) {
				$quoteChar	= '"';
				$j			= $k;
			} else {
				$quoteChar	= "'";
			}

			if ($j === false) {
				$j = $n;
			}

			$literal .= str_replace( $needle, $replace, substr( $sql, $startPos, $j - $startPos ) );
			$startPos = $j;

			$j = $startPos + 1;

			if ($j >= $n) {
				break;
			}

			// quote comes first, find end of quote
			while (TRUE)
			{
				$k = strpos( $sql, $quoteChar, $j );
				$escaped = false;
				if ($k === false) {
					break;
				}
				$l = $k - 1;
				while ($l >= 0 && $sql{$l} == '\\') {
					$l--;
					$escaped = !$escaped;
				}
				if ($escaped) {
					$j	= $k+1;
					continue;
				}
				break;
			}

			if ($k === FALSE) {
				// error in the query - no end quote; ignore it
				break;
			}

			$literal .= substr( $sql, $startPos, $k - $startPos + 1 );
			$startPos = $k+1;
		}

		if ($startPos < $n) {
			$literal .= substr( $sql, $startPos, $n - $startPos );
		}

		return $literal;
	}

    /**
     * Safely quotes a value for an SQL statement.
     *
     * If an array is passed as the value, the array values are quoted
     * and then returned as a comma-separated string; this is useful
     * for generating IN() lists.
     *
     * @param 	mixed The value to quote.
     * @return string An SQL-safe quoted value (or a string of separated-
     * 				  and-quoted values).
     */
    public function quoteValue($value)
    {
        if (is_array($value))
        {
            // quote array values, not keys, then combine with commas.
            foreach ($value as $k => $v) {
                $value[$k] = $this->quoteValue($v);
            }

            $value = implode(', ', $value);
        }
        else
        {
        	if(is_string($value) && !is_null($value)) {
        		$value = $this->_quoteValue($value);
        	}
        }

        return $value;
    }
    
    /**
	 * Parse the raw table schema information
	 *
	 * @param  	object 	The raw table schema information
	 * @return KDatabaseSchemaTable
	 */
	abstract protected function _parseTableInfo($info);


	/**
	 * Parse the raw column schema information
	 *
	 * @param  	object 	The raw column schema information
	 * @return KDatabaseSchemaColumn
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
     * @param 	mixed 	The value to quote
     * @return string An SQL-safe quoted value
     */
    abstract public function _quoteValue($value);

   	/**
     * Quotes a single identifier name (table, table alias, table column,
     * index, sequence).  Ignores empty values.
     *
     * If the name contains ' AS ', this method will separately quote the
     * parts before and after the ' AS '.
     *
     * If the name contains a space, this method will separately quote the
     * parts before and after the space.
     *
     * If the name contains a dot, this method will separately quote the
     * parts before and after the dot.
     *
     * @param string|array $spec The identifier name to quote.  If an array,
     * quotes each element in the array as an identifier name.
     *
     * @return string|array The quoted identifier name (or array of names).
     *
     * @see _quoteName()
     */
    public function quoteName($spec)
    {
    	if (is_array($spec))
        {
            foreach ($spec as $key => $val) {
                $spec[$key] = $this->quoteName($val);
            }
            return $spec;
        }

        // no extraneous spaces
        $spec = trim($spec);

        // `original` AS `alias`
        $pos = strrpos($spec, ' AS ');
        if ($pos)
        {
        	// recurse to allow for "table.col"
            $orig  = $this->quoteName(substr($spec, 0, $pos));
            // use as-is
            $alias = $this->_quoteName(substr($spec, $pos + 4));

            return "$orig AS $alias";
        }

     	// `original` = `alias`
        $pos = strrpos($spec, ' = ');
        if ($pos)
        {
            // recurse to allow for "table.col"
            $orig = $this->quoteName(substr($spec, 0, $pos));
            // recurse to allow for "table.col"
            $alias = $this->quoteName(substr($spec, $pos + 3));
            return "$orig = $alias";
        }
        
         // `original` > `alias`
	   	$pos = strrpos($spec, ' > ');
	  	if ($pos)
	  	{
	   		// recurse to allow for "table.col"	
	      	$orig = $this->quoteName(substr($spec, 0, $pos));
            // recurse to allow for "table.col"
            $alias = $this->quoteName(substr($spec, $pos + 3));
            return "$orig > $alias";
        }
        
        // `original` < `alias`
        $pos = strrpos($spec, ' < ');
        if ($pos)
        {
            // recurse to allow for "table.col"
            $orig = $this->quoteName(substr($spec, 0, $pos));
            // recurse to allow for "table.col"
            $alias = $this->quoteName(substr($spec, $pos + 3));
            return "$orig < $alias";
        }
        
        // `original` `alias`
        $pos = strrpos($spec, ' ');
        if ($pos)
        {
            // recurse to allow for "table.col"
            $orig = $this->quoteName(substr($spec, 0, $pos));
            // use as-is
            $alias = $this->_quoteName(substr($spec, $pos + 1));
            return "$orig $alias";
        }

        // `table`.`column`
        $pos = strrpos($spec, '.');
        if ($pos)
        {
            // use both as-is
            $table = $this->_quoteName(substr($spec, 0, $pos));
            $col   = $this->_quoteName(substr($spec, $pos + 1));
            return "$table.$col";
        }

        // `name`
        return $this->_quoteName($spec);
    }

    /**
     * Quotes an identifier name (table, index, etc). Ignores empty values.
     *
     * @param string 	The identifier name to quote.
     * @return string 	The quoted identifier name.
     * @see quoteName()
     */
    protected function _quoteName($name)
    {
        $name = trim($name);

        //Special cases
        if ($name == '*' || is_numeric($name)) {
            return $name;
        }

        return $this->_name_quote. $name.$this->_name_quote;
    }
}