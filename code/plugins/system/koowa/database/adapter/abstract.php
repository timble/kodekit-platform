<?php
/**
 * @version		$Id: database.php 755 2009-04-24 07:42:19Z johan $
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Asbtract Database Adapter
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Adapter
 * @uses 		KPatternCommandChain
 */
class KDatabaseAdapterAbstract extends KObject
{
	/**
	 * Database operations
	 */
	const OPERATION_SELECT = 1;
	const OPERATION_INSERT = 2;
	const OPERATION_UPDATE = 4;
	const OPERATION_DELETE = 8;

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $_options = array();

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
	protected $_insertId;
	
	/**
	 * The affected row count
	 * 
	 * @var int
	 */
	protected $_affectedRows;

	/**
	 * Metadata cache
	 *
	 * @var array
	 */
	protected $_cache = null;
	
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * Recognized key values include 'command_chain'
	 * (this list is not meant to be comprehensive).
	 */
	public function __construct( array $options = array() )
	{
        // Initialize the options
        $this->_options  = $this->_initialize($options);
       
        // Mixin the command chain
        $this->mixin(new KMixinCommand($this, $this->_options['command_chain']));
        
		// Set the default charset. http://dev.mysql.com/doc/refman/5.1/en/charset-connection.html
		if (!empty($this->_options['charset'])) {
			$this->setCharset($this->_config['charset']);
		}
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
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(array $options)
    {
        $defaults = array(
            'command_chain' =>  null,
        	'charset'		=> 'UTF-8'
        );

        return array_merge($defaults, $options);
    }
    

	/**
	 * Get a database query object
	 *
	 * @return object KDatabaseQuery
	 */
	public function getQuery(array $options = array())
	{
		if(!isset($options['dbo'])) {
			$options['dbo'] = $this;
		} 
		
		$query = new KDatabaseQuery($options);
		return $query;
	}
	
	/**
	 * Connect to the db
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
	 */
	public function reconnect()
	{
		$this->disconnect();
		$this->connect();
	}
	
	/**
	 * Disconnect from db
	 */
	public function disconnect()
	{
		$this->_connection = null;
		$this->_active = false;
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
	 * @param $resource The connection resource
	 */
	public function setConnection($resource)
	{
		$this->_connection = $resource;
	}

	/**
     * Preforms a select query
     *
     * Use for SELECT and anything that returns rows.
     *
     * @param	string  $sql 	A full SQL query to run
     * @param	integer 		Offset
     * @param	integer			Limit
     * @return 	object A KRowset.
     */
	public function select($sql, $offset = 0, $limit = 0)
	{
		// Create the arguments object
		$args = new ArrayObject();
		$args['sql'] 		= $sql;
		$args['offset'] 	= $offset;	
		$args['limit'] 		= $limit;	
		$args['notifier']   = $this;
		$args['operation']	= self::OPERATION_SELECT;

		// Excute the insert operation
		if($this->getCommandChain()->run('database.before.select', $args) === true) {
			$args['result'] = $this->execute( $args['sql'], $args['offset'], $args['limit'] );
			$this->getCommandChain()->run('database.after.select', $args);
		}

		return $args['result'];
	}
	
	/**
     * Inserts a row of data into a table.
     *
     * Automatically quotes the data values
     *
     * @param string $table 	The table to insert data into.
     * @param array $data 		An associative array where the key is the colum name and
     * 							the value is the value to insert for that column.
     *
     * @return integer  		Number of rows affected
     */
	public function insert($table, array $data)
	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['table'] 		= $table;
		$args['data'] 		= $data;	
		$args['notifier']   = $this;
		$args['operation']	= self::OPERATION_INSERT;
		
		//Excute the insert operation
		if($this->getCommandChain()->run('database.before.insert', $args) === true) 
		{
			foreach($args['data'] as $key => $val)
			{
				$vals[] = $this->quote($val);
				$keys[] = '`'.$key.'`';
			}

			$sql = 'INSERT INTO '.$this->quoteName('#__'.$args['table'] )
				 . '('.implode(', ', $keys).') VALUES ('.implode(', ', $vals).')';
				 	
			$args['result'] = $this->_insertId;		
			$this->getCommandChain()->run('database.after.insert', $args);
		}
		
		return $args['result'];
	}

	/**
     * Updates a table with specified data based on a WHERE clause
     *
     * Automatically quotes the data values
     *
     * @param string $table		The table to update
     * @param array  $data  	An associative array where the key is the column name and
     * 							the value is the value to use ofr that column.
     * @param mixed $where		A sql string or KDatabaseQuery object to limit which rows are updated.
     *
     * @return integer Number of rows affected
     */
	public function update($table, array $data, $where = null)
	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['table'] 		= $table;
		$args['data']  		= $data;	
		$args['notifier']   = $this;
		$args['where']   	= $where;
		$args['operation']	= self::OPERATION_UPDATE;
	
		//Excute the update operation
		if($this->getCommandChain()->run('database.before.update', $args) ===  true) 	
		{
			foreach($args['data'] as $key => $val) {
				$vals[] = '`'.$key.'` = '.$this->quote($val);
			}

			//Create query statement
			$sql = 'UPDATE '.$this->quoteName('#__'.$args['table'])
			  	.' SET '.implode(', ', $vals)
			  	.' '.$args['where']
			;
			
			$args['result'] = $this->_affectedRows;
			$this->getCommandChain()->run('database.after.update', $args);
		}
		
        return $args['result'];
	}

	/**
     * Deletes rows from the table based on a WHERE clause.
     *
     * @param string $table		The table to update
     * @param mixed  $where		A query string or a KDatabaseQuery object to limit which rows are updated.
     *
     * @return integer Number of rows affected
     */
	public function delete($table, $where)
	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['table'] 		= $table;
		$args['data']  		= null;	
		$args['notifier']   = $this;
		$args['where']   	= $where;
		$args['operation']	= self::OPERATION_DELETE;

		//Excute the delete operation
		if($this->getCommandChain()->run('database.before.delete', $args) ===  true) 
		{
			//Create query statement
			$sql = 'DELETE FROM '.$this->quoteName('#__'.$args['table'])
				  .' '.$args['where']
			;
			
			$args['result'] = $this->_affectedRows;
			$this->getCommandChain()->run('database.after.delete', $args);	
		}
		
		return $args['result'];
	}

	/**
	 * Use for INSERT, UPDATE, DELETE, and other queries that don't return rows.
	 * Returns number of affected rows.
	 *
	 * @param  string 	$sql 		The query to run.
	 * @return integer 	The number of rows affected by $sql.
	 */
	public function execute($sql)
	{
		//Replace the database table prefix
		$sql = $this->replacePrefix( $sql );
		
		try {
			$stmt = $this->_connection->query($sql); 
		} catch (Exception $e) {
			throw new KDatabaseException((string)$e->getMessage(), (int)$e->getCode());
		}
		
		$this->_affectedRows = $this->_connection->affected_rows;
		$this->_insertId     = $this->_connection->insert_id;
		
		return $stmt;
	}
	
	/**
     * Leave autocommit mode and begin a transaction.
     * 
     * @return void
     */
    abstract public function begin();
    
    /**
     * Commit a transaction and return to autocommit mode.
     * 
     * @return void
     */
    abstract public function commit();
    
    /**
     * Roll back a transaction and return to autocommit mode.
     * 
     * @return void
     */
    abstract public function rollback();
	
	
    /**
     * The database's date and time
     *
     * @return string	Date and time in yyyy-mm-dd hh:mm:ss format
     */
    public function getNow()
    {
        static $result;

        if(!isset($result))
        {
            $this->select('SELECT NOW()');
            $result = $this->loadResult();
        }

        return $result;
    }

	/**
	 * Retrieves information about the given tables
	 *
	 * @param 	array|string 	A table name or a list of table names
	 * @param	boolean			Only return field types, default true
	 * @return	array An array of fields by table
	 */
	public function getTableFields( $tables )
	{
		settype($tables, 'array'); //force to array
		$result = array();
		
		foreach ($tables as $tblval)
		{  
			$table = $tblval;

			if(!isset($this->_tables_cache[$tblval])) 
			{
				//Check the table if it already has a table prefix applied.
				if(strpos($tblval, $this->getObject()->getPrefix()) === false) 
				{
					if(substr($tblval, 0, 3) != '#__') {
						$table = '#__'.$tblval;
					}
				} 
				else 
				{
					$tblval = $this->replaceTablePrefix($tblval, '');
				}
			
				$this->select( 'SHOW FIELDS FROM ' . $this->quoteName($table));
				$fields = $this->loadObjectList();
				
				foreach ($fields as $field) {
					$this->_tables_cache[$tblval][$field->Field] = $field;
				}
			}
			
			//Add the requested table to the result
			$result[$tblval] = $this->_tables_cache[$tblval];
		}
		
		return $result;
	}
	
	/**
	 * Get the result of the SHOW TABLE STATUS statement
	 * 
	 * @param	string	LIKE clause, can cotnains a tablename with % wildcards
	 * @param 	string	WHERE clause (MySQL5+ only)
	 * @return	array	List of objects with table info
	 */
	public function getTableStatus($like = null, $where = null)
	{
		if(!empty($like)) {
			$like = ' LIKE '.$this->quote($like);
		}
		
		if(!empty($where)) {
			$where = ' WHERE '.$where;
		}
		
		$this->setQuery( 'SHOW TABLE STATUS'.$like.$where );
		return $this->loadObjectList('Name');
	}

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @param string $sql 		The SQL query string
	 * @param string $replace 	The table prefix to use as a replacement
	 * @param string $needle 	The needle to search for in the query string
	 * @return string	The SQL query string
	 */
	public function replaceTablePrefix( $sql, $replace, $needle = '#__' )
	{
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
     * @param 	mixed 	$value 	The value to quote.
     * 
     * @return string An SQL-safe quoted value (or a string of separated-
     * 				  and-quoted values).
     */
    public function quote($value)
    {
        if (is_array($value)) 
        {
            // quote array values, not keys, then combine with commas.
            foreach ($value as $k => $v) {
                $value[$k] = $this->quote($v);
            }
            return implode(', ', $value);
        } 
        else 
        {
        	if(!is_numeric($value)) {
        		return '\''.mysqli_real_escape_string( $this->_connection, $text ).'\'';
        	}
        	
        	return $value;
        }
    }
        
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
        
     	// `original` `alias`
        $pos = strrpos($spec, ' = ');
        if ($pos) 
        {
            // recurse to allow for "table.col"
            $orig = $this->quoteName(substr($spec, 0, $pos));
            // recurse to allow for "table.col"
            $alias = $this->quoteName(substr($spec, $pos + 3));
            return "$orig = $alias";
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
     * @param string $name The identifier name to quote.
     * @return string The quoted identifier name.
     * @see quoteName()
     */
    protected function _quoteName($name)
    {
        $name = trim($name);
        
        //Special cases
        if ($name == '*' || is_numeric($name)) {
            return $name;
        }
         
        return $this->_object->_nameQuote. $name.$this->_object->_nameQuote;
    }
    
}