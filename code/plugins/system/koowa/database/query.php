<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright	(C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Select Class for SQL select statement generation
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 */
class KDatabaseQuery extends KObject
{
	/**
	 * The operation to perform
	 *
	 * @var array
	 */
	public $operation = '';
	
	/**
	 * The columns
	 *
	 * @var array
	 */
	public $columns = array();
	
	/**
	 * The from element
	 *
	 * @var array
	 */
	public $from = array();

	/**
	 * The join element
	 *
	 * @var array
	 */
	public $join = array();

	/**
	 * The where element
	 *
	 * @var array
	 */
	public $where = array();

	/**
	 * The group element
	 *
	 * @var array
	 */
	public $group = array();

	/**
	 * The having element
	 *
	 * @var array
	 */
	public $having = array();

	/**
	 * The order element
	 *
	 * @var string
	 */
	public $order = array();

	/**
	 * The limit element
	 *
	 * @var integer
	 */
	public $limit = null;

	/**
	 * The limit offset element
	 *
	 * @var integer
	 */
	public $offset = null;
	
	/**
     * Data to bind into the query as key => value pairs.
     * 
     * @var array
     */
    protected $_bind = array();

	/**
	 * Database connector
	 *
	 * @var		object
	 */
	protected $_db;

	/**
	 * Object constructor
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @param	array An optional associative array of configuration settings.
	 *                Recognized key values include 'dbo' (this list is not
	 * 				  meant to be comprehensive).
	 */
	public function __construct( array $options = array() )
	{
        // Initialize the options
        $options  = $this->_initialize($options);

		//set the model dbo
		$this->_db    = $options['dbo'] ? $options['dbo'] : KFactory::get('lib.joomla.database');
	}


    /**
     * Initializes the options for the object
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize($options)
    {
        $defaults = array(
            'dbo'   => null
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Built a select query
	 *
	 * @param	array|string	A string or an array of field names
	 * @return object KDatabaseQuery
	 */
	public function select( $columns = '*')
	{
		settype($columns, 'array'); //force to an array
		
		//Quote the identifiers
		$columns = $this->_db->quoteName($columns);

		$this->operation = 'SELECT';
		$this->columns   = array_unique( array_merge( $this->columns, $columns ) );
		return $this;
	}
	
	/**
	 * Built a count query
	 *
	 * @return object KDatabaseQuery
	 */
	public function count()
	{
		$this->operation = 'SELECT COUNT(*) ';
		$this->columns    = array();
		return $this;
	}
	
	/**
	 * Make the query distinct
	 *
	 * @return object KDatabaseQuery
	 */
	public function distinct()
	{
		$this->operation = 'SELECT DISTINCT ';
		return $this;
	}

	/**
	 * Built the from clause of the query
	 *
	 * @param	array|string	A string or array of table names
	 * @return object KDatabaseQuery
	 */
	public function from( $tables )
	{
		settype($tables, 'array'); //force to an array
		
		//Prepent the table prefix 
		array_walk($tables, array($this, '_prefix'));
		
		//Quote the identifiers
		$tables = $this->_db->quoteName($tables);
		
		$this->from = array_unique( array_merge( $this->from, $tables ) );
		return $this;
	}
	
	/**
     * Built the join clause of the query
     * 
     * @param string 		$type  		The type of join; empty for a plain JOIN, or "LEFT", "INNER", etc.
     * @param string 		$table 		The table name to join to.
     * @param string|array 	$condition  Join on this condition.
     * @param array|string 	$cols  The columns to select from the joined table.
     * @return object KDatabaseQuery
     */
    public function join($type, $table, $condition)
    {     
		settype($condition, 'array'); //force to an array
    	
		$this->_prefix($table); //add a prefix to the table
    	
		//Quote the identifiers
		$table     = $this->_db->quoteName($table);
		$condition = $this->_db->quoteName($condition);
	    	
    	$this->join[] = array(
        	'type'  	=> strtoupper($type),
        	'table' 	=> $table,
        	'condition' => $condition,
        );
          
        return $this;
    }
	
	/**
	 * Built the where clause of the query
	 *
	 * @param   string 			The name of the property the constraint applies too
	 * @param	string  		The comparison used for the constraint
	 * @param	string|array	The value compared to the property value using the constraint
	 * @param	string			The where condition, defaults to 'AND'
	 * @return 	object 	KDatabaseQuery
	 */
	public function where( $property, $constraint, $value, $condition = 'AND' )
	{
		if(empty($property)) {
			return $this;
		}
		
		// Apply quotes to the property name
		$property = $this->_db->quoteName($property);
		
		// Apply quotes to the value
		$value    = $this->_db->quote($value);
		if($constraint == 'LIKE') {
			$value = addcslashes( $value, '%_' );
		}
		
       	//Create the where clause
        if(in_array($constraint, array('IN', 'NOT IN'))) {
        	$value = ' ( '.$value. ' ) ';
        }
		
		$where = $property.' '.$constraint.' '.$value;
        
		//Prepend the condition
        if(count($this->where)) {
            $where = $condition .' '. $where;
        } 
        
		$this->where = array_unique( array_merge( $this->where, array($where) ));
		return $this;
	}

	/**
	 * Built the group clause of the query
	 *
	 * @param	array|string	A string or array of ordering columns
	 * @return object KDatabaseQuery
	 */
	public function group( $columns )
	{
		settype($columns, 'array'); //force to an array
		
		//Quote the identifiers
		$columns = $this->_db->quoteName($columns);

		$this->group = array_unique( array_merge( $this->group, $columns));
		return $this;
	}

	/**
	 * Built the having clause of the query
	 *
	 * @param	array|string	A string or array of ordering columns
	 * @return object KDatabaseQuery
	 */
	public function having( $columns )
	{
		settype($columns, 'array'); //force to an array
		
		//Quote the identifiers
		$columns = $this->_db->quoteName($columns);

		$this->having = array_unique( array_merge( $this->having, $columns ));
		return $this;
	}

	/**
	 * Built the order clause of the query
	 *
	 * @param	array|string  $columns		A string or array of ordering columns
	 * @param	string		  $direction	Either DESC or ASC
	 * @return object KDatabaseQuery
	 */
	public function order( $columns, $direction = 'ASC' )
	{
		settype($columns, 'array'); //force to an array
		
		//Quote the identifiers
		$columns = $this->_db->quoteName($columns);
		
		foreach($columns as $column) 
		{
			$this->order[] = array(
        		'column'  	=> $column,
        		'direction' => $direction
        	);
		}

		return $this;
	}

	/**
	 * Built the limit element of the query
	 *
	 * @param integer $limit 	Number of items to fetch.
	 * @param integer $offset 	Offset to start fetching at.
	 * @return object KDatabaseQuery
	 */
	public function limit( $limit, $offset = null )
	{
		$this->limit  = $limit;
		$this->offset = $offset;
		return $this;
	}
	
	/**
     * Adds data to bind into the query.
     * 
     * @param 	mixed 	$key The replacement key in the query.  If this is an
     * 						 array or object, the $val parameter is ignored, 
     * 						 and all the key-value pairs in the array (or all 
     *   					 properties of the object) are added to the bind.
     * @param 	mixed 	$val The value to use for the replacement key.
     * @return object KDatabaseQuery
     */
    public function bind($key, $val = null)
    {
        if (is_array($key)) {
            $this->_bind = array_merge($this->_bind, $key);
        } elseif (is_object($key)) {
            $this->_bind = array_merge((array) $this->_bind, $key);
        } else {
            $this->_bind[$key] = $val;
        }
        
        return $this;
    }
    
    /**
     * Unsets bound data.
     * 
     * @param 	mixed 	$spec 	The key to unset.  If a string, unsets that one
     * 							bound value; if an array, unsets the list of values; 
     * 							if empty, unsets all bound values (the default).
     * @return object KDatabaseQuery
     */
    public function unbind($spec = null)
    {
        if (empty($spec)) {
            $this->_bind = array();
        } else {
            settype($spec, 'array');
            foreach ($spec as $key) {
                unset($this->_bind[$key]);
            }
        }
        
        return $this;
    }

	/*
	 * Callback for array_walk to prefix elements of array with given 
	 * prefix
	 * 
	 * @param string $data 	The data to be prefixed
	 */
	protected function _prefix(&$data)
	{	
		// Prepend the table modifier
		$data = '#__'.$data;
	}

	/**
	 * Render the query to a string
	 *
	 * @return	string	The completed query
	 */
	public function __toString()
	{
		$query = '';
		
		$query .= $this->operation.PHP_EOL;

		if (!empty($this->columns)) {
			$query .= implode(' , ', $this->columns).PHP_EOL;
		}

		if (!empty($this->from)) {
			$query .= ' FROM '.implode(' , ', $this->from).PHP_EOL;
		}
		
		if (!empty($this->join))
		{
			$joins = array();
            foreach ($this->join as $join) 
            {
            	$tmp = '';
                
            	if (! empty($join['type'])) {
                    $tmp .= $join['type'] . ' ';
                }
               
                $tmp .= 'JOIN ' . $join['table'];
                $tmp .= ' ON ' . implode(' AND ', $join['condition']);
           
                $joins[] = $tmp;
            }
            
            $query .= implode(PHP_EOL, $joins) .PHP_EOL;
		}

		if (!empty($this->where)) {
			$query .= ' WHERE '.implode(' ', $this->where).PHP_EOL;
		}

		if (!empty($this->_group)) {
			$query .= ' GROUP BY '.implode(' , ', $this->group).PHP_EOL;
		}

		if (!empty($this->_having)) {
			$query .= ' HAVING '.implode(' , ', $this->having).PHP_EOL;
		}
		
		if (!empty($this->order) ) 
		{
			$query .= 'ORDER BY ';
			
			$list = array();
            foreach ($this->order as $order) {
            	$list[] = $order['column'].' '.$order['direction'];
            }
            
            $query .= implode(' , ', $list) . PHP_EOL;
		}
	
		if (isset($this->limit)) {
			$query .= ' LIMIT '.$this->limit.' , '.$this->offset.PHP_EOL;
		}
		
		return $query;
	}
}