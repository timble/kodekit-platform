<?php
/**
 * @version		$Id$
 * @package     Koowa_Database
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_Database
 */
class KDatabaseAbstract extends KPatternProxy
{
	/**
	 * The for offset for the limit
	 *
	 * @var int
	 */
	protected $_offset;

	/**
	 * The limit for the query
	 *
	 * @var int
	 */
	protected $_limit = 0;

	/**
	 * Automatically execute the query
	 *
	 * @var boolean
	 */
	protected $_autoexec = true;
	
	/**
	 * The commandchain
	 *
	 * @var	object
	 */
	protected $_commandChain = null;

	/**
	 * Constructor
	 *
	 * @param	object	$dbo 	The database object to proxy
	 * @return	void
	 */
	public function __construct($db)
	{
		parent::__construct($db);
		
		 //Create the command chain
        $this->_commandChain = new KPatternCommandChain();
        $this->_commandChain->enqueue(new KCommandEvent());
	}

	/**
	 * Proxy the database connector setQuery() method
	 *
	 * @return	mixed	Database connector return value
	 */
	public function setQuery($sql, $offset = 0, $limit = 0, $prefix = '#__')
	{
		$result = false;

		$query  = explode(' ', trim($sql));

		switch( strtoupper($query[0]))
		{
			case 'INSERT' :
			{
				$parser = new KDatabaseQueryParser();
				if(!$query  = $parser->parse($this->replaceTablePrefix($sql, '', $prefix))) {
					$this->select($sql);
					break;
				}

				$table = $query['table_names'][0];

                if(!isset($query['column_names'] ))
                {
                    // the column names weren't specified, get them from the table's metadata
                    // TODO is there a more performant way?
                    $fields = $this->getTableFields($table);
                    $query['column_names'] = array_keys($fields[$table]);
                }

                // Make a list of field names and their values
                $data  = array();
                foreach($query['column_names'] as $key => $column_name) {
                    $data[$column_name] = $query['values'][$key]['value'];
                }
				$this->_autoexec = false;
				$result = $this->insert($table, $data);
				$this->_autoexec = true;				
			} break;

			case 'UPDATE' :
			{
				$parser = new KDatabaseQueryParser();
				if(!$query  = $parser->parse($this->replaceTablePrefix($sql, '', $prefix))) {
					$this->select($sql);
					break;
				}

				$where = substr($sql, strpos($sql, 'WHERE'));
				$table = $query['table_names'][0];

				$data  = array();
				foreach($query['column_names'] as $key => $column_name) {
					$data[$column_name] = $query['values'][$key]['value'];
				}

				//force to true in case we where not able to determine the rows affected
				$this->_autoexec = false;
				$result = $this->update($table, $data, $where);
				$this->_autoexec = true;
			} break;

			case 'DELETE'  :
			{
				$parser = new KDatabaseQueryParser();
				if(!$query  = $parser->parse($this->replaceTablePrefix($sql, '', $prefix))) {
					$this->select($sql);
					break;
				}

				$where = substr($sql, strpos($sql, 'WHERE'));
				$table = $query['table_names'][0];

				$result = $this->delete($table, $where);
			} break;

			default :
			{
				$this->_autoexec = false; //turn off autoexecuting of queries
				$result = $this->select( $sql, $offset, $limit );
				$this->_autoexec = true; //turn on autoexecuting of queries
			}
		}

		return $result;
	}

	/**
	 * Proxy the database connector insertObject() method
	 */
	public function insertObject( $table, &$object, $keyName = NULL )
	{
		$data = array();
		foreach (get_object_vars( $object ) as $k => $v)
		{
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			$data[$k] = $v;
		}

		if($result = $this->insert( $this->replaceTablePrefix($table, '', '#__'), $data ))
		{
			$id = $this->insertid();
			if ($keyName && $id) {
				$object->$keyName = $id;
			}

			return true;
		}

		return false;
	}

	/**
	 * Proxy the database connector updateObject() method
	 */
	public function updateObject( $table, &$object, $keyName, $updateNulls=true )
	{
		$data = array();
		foreach (get_object_vars( $object ) as $k => $v)
		{
			if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
				continue;
			}
			if( $k == $keyName ) { // PK not to be updated
				$where = 'WHERE '.$keyName.' = '.$this->Quote( $v );
				continue;
			}
			if ($v === null)
			{
				if ($updateNulls) {
					$val = 'NULL';
				} else {
					continue;
				}
			} else {
				$val = $v;
			}
			$data[$k] = $val;
		}

		return $this->update( $this->replaceTablePrefix($table, '', '#__'), $data, $where);
	}

	/**
	 * Get a database query object
	 *
	 * @return object KDatabaseQuery
	 */
	public function getQuery()
	{
		$query = new KDatabaseQuery();
		return $query;
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
		$result = $this->_object->setQuery( $sql, $offset, $limit );
		return $result;
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
	public function insert($table, $data)
	{
		foreach($data as $key => $val)
		{
			$vals[] = $this->_object->Quote($val);
			$keys[] = '`'.$key.'`';
		}

		$sql = 'INSERT INTO '.$this->_object->nameQuote('#__'.$table)
			 . '('.implode(', ', $keys).') VALUES ('.implode(', ', $vals).')';

		
		//Create the arguments object
		$args = new stdClass();
		$args->table  = $table;
		$args->data   = $data;	
		$args->class  = get_class($this);
		$args->result = null;

		//Excute the insert operation
		if($this->_commandChain->execute('onBeforeDatabaseInsert', $args) === true) {
			$args->result = $this->execute($sql);
			$this->_commandChain->execute('onAfterDatabaseInsert', $args);
			return $args->result;
		}
		
		return false;
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
	public function update($table, $data, $where = null)
	{
		foreach($data as $key => $val) {
			$vals[] = '`'.$key.'` = '.$this->_object->Quote($val);
		}

		//Create query statement
		$sql = 'UPDATE '.$this->_object->nameQuote('#__'.$table)
			  .' SET '.implode(', ', $vals)
			  .' '.$where
		;
		
		//Create the arguments object
		$args = new stdClass();
		$args->table  = $table;
		$args->data   = $data;	
		$args->class  = get_class($this);
		$args->result = null;

		//Excute the update operation
		if($this->_commandChain->execute('onBeforeDatabaseUpdate', $args) ===  true) {
			$args->result = $this->execute($sql);
			$this->_commandChain->execute('onAfterDatabaseUpdate', $args);
			return $args->result;
		}
		
        return false;
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
		//Create query statement
		$sql = 'DELETE FROM '.$this->_object->nameQuote('#__'.$table)
			  .' '.$where
		;

		//Create the arguments object
		$args = new stdClass();
		$args->table  = $table;
		$args->data   = null;	
		$args->class  = get_class($this);
		$args->result = null;
		
		//Excute the delete operation
		if($this->_commandChain->execute('onBeforeDatabaseDelete', $args) ===  true) {
			$args->result = $this->execute($sql);
			$this->_commandChain->execute('onAfterDatabaseDelete', $args);
			return $args->result;
		}
		
		return false;
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
		$result = 0;

		//Replace the database table prefix
		$this->_object->_sql = $this->replacePrefix( $sql );

		// Force to zero just in case
		$this->_object->_limit = 0;
        $this->_object->_offset = 0;

		//If autoexec is on, execute the query and return the affected rows
		if($this->_autoexec)
		{
			if (!$this->query())
            {
				$this->setError($this->_object->getErrorMsg());
				return false;
			}

			//Force affected rows to 1 in case query was successfull and no rows where returned
			if(!$result = $this->_object->getAffectedRows()) {
				$result = 1;
			}
		}

		return $result;
	}

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
            $result = $this->_object->loadResult();
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
		
			//Check the table if it already has a table prefix applied.
			if(substr($tblval, 0, 3) != '#__') {
				$table = '#__'.$tblval;
			}
		
			$this->select( 'SHOW FIELDS FROM ' . $this->nameQuote($table));
			$fields = $this->loadObjectList();

			foreach ($fields as $field) {
				$result[$tblval][$field->Field] = $field;
			}
		}
		return $result;
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
     * Alias of JDatabase getErrorMsg, following Koowa standards
     *
     * @return 	string	Last error Message
     */
    public function getError()
    {
    	return $this->getObject()->getErrorMsg();
    }
    

	/**
	 * Get the result of the SHOW TABLE STATUS statement
	 *
	 * @param 	string	WHERE clause
	 * @return	array	List of objects with table info
	 */
	public function getTableStatus($where = null)
	{
		if(!empty($where)) {
			$where = ' WHERE '.$where;
		}
		
		$this->setQuery( 'SHOW TABLE STATUS'.$where );
		return $this->loadObjectList();
	}
}