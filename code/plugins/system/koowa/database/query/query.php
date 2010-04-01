<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Select Class for database select statement generation
 *
 * @author		Johan Janssens <johan@koowa.org>
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
	public $limit = 0;

	/**
	 * The limit offset element
	 *
	 * @var integer
	 */
	public $offset = 0;
	
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
	protected $_adapter;

	/**
	 * Object constructor
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct( KConfig $config = null)
	{
        //If no config is passed create it
		if(!isset($config)) $config = new KConfig();
		
		parent::__construct($config);

		//set the model adapter
		$this->_adapter = $config->adapter;
	}


    /**
     * Initializes the options for the object
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'adapter' => KFactory::get('lib.koowa.database')
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Gets the database adapter for this particular KDatabaseQuery object.
     *
     * @return KDatabaseAdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }
    

	/**
	 * Built a select query
	 *
	 * @param	array|string	A string or an array of column names
	 * @return 	KDatabaseQuery
	 */
	public function select( $columns = '*')
	{
		settype($columns, 'array'); //force to an array
		
		//Quote the identifiers
		$columns = $this->_adapter->quoteName($columns);

		$this->operation = 'SELECT';
		$this->columns   = array_unique( array_merge( $this->columns, $columns ) );
		return $this;
	}
	
	/**
	 * Built a count query
	 *
	 * @return KDatabaseQuery
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
	 * @return KDatabaseQuery
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
	 * @return 	KDatabaseQuery
	 */
	public function from( $tables )
	{
		settype($tables, 'array'); //force to an array
		
		//Prepent the table prefix 
		array_walk($tables, array($this, '_prefix'));
		
		//Quote the identifiers
		$tables = $this->_adapter->quoteName($tables);
		
		$this->from = array_unique( array_merge( $this->from, $tables ) );
		return $this;
	}
	
	/**
     * Built the join clause of the query
     * 
     * @param string 		The type of join; empty for a plain JOIN, or "LEFT", "INNER", etc.
     * @param string 		The table name to join to.
     * @param string|array 	Join on this condition.
     * @return KDatabaseQuery
     */
    public function join($type, $table, $condition)
    {     
		settype($condition, 'array'); //force to an array
    	
		$this->_prefix($table); //add a prefix to the table
    	
		//Quote the identifiers
		$table     = $this->_adapter->quoteName($table);
		$condition = $this->_adapter->quoteName($condition);
	    	
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
	 * @return 	KDatabaseQuery
	 */
	public function where( $property, $constraint, $value = null, $condition = 'AND' )
	{
		if(empty($property)) {
			return $this;
		}
		
		$constraint	= strtoupper($constraint);
		$condition	= strtoupper($condition);
		
		// Apply quotes to the property name
		$property = $this->_adapter->quoteName($property);
		
		// Apply quotes to the value
		$value    = $this->_adapter->quoteValue($value);
		
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
	 * @return 	KDatabaseQuery
	 */
	public function group( $columns )
	{
		settype($columns, 'array'); //force to an array
		
		//Quote the identifiers
		$columns = $this->_adapter->quoteName($columns);

		$this->group = array_unique( array_merge( $this->group, $columns));
		return $this;
	}

	/**
	 * Built the having clause of the query
	 *
	 * @param	array|string	A string or array of ordering columns
	 * @return 	KDatabaseQuery
	 */
	public function having( $columns )
	{
		settype($columns, 'array'); //force to an array
		
		//Quote the identifiers
		$columns = $this->_adapter->quoteName($columns);

		$this->having = array_unique( array_merge( $this->having, $columns ));
		return $this;
	}

	/**
	 * Built the order clause of the query
	 *
	 * @param	array|string  A string or array of ordering columns
	 * @param	string		  Either DESC or ASC
	 * @return 	KDatabaseQuery
	 */
	public function order( $columns, $direction = 'ASC' )
	{
		settype($columns, 'array'); //force to an array
		
		//Quote the identifiers
		$columns = $this->_adapter->quoteName($columns);
		
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
	 * @param 	integer Number of items to fetch.
	 * @param 	integer Offset to start fetching at.
	 * @return 	KDatabaseQuery
	 */
	public function limit( $limit, $offset = 0 )
	{
		$this->limit  = $limit;
		$this->offset = $offset;
		return $this;
	}
	
	/**
     * Adds data to bind into the query.
     * 
     * @param 	mixed 	The replacement key in the query.  If this is an
     * 					array or object, the $val parameter is ignored, 
     * 					and all the key-value pairs in the array (or all 
     *   				properties of the object) are added to the bind.
     * @param 	mixed 	The value to use for the replacement key.
     * @return 	KDatabaseQuery
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
     * @param 	mixed 	The key to unset.  If a string, unsets that one
     * 					bound value; if an array, unsets the list of values; 
     * 					if empty, unsets all bound values (the default).
     * @return 	KDatabaseQuery
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
	 * Callback for array_walk to prefix elements of array with given prefix
	 * 
	 * @param string The data to be prefixed
	 */
	protected function _prefix(&$data)
	{	
		// Prepend the table modifier
		$prefix = $this->_adapter->getTablePrefix();
		$data = $prefix.$data;
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

		if (!empty($this->group)) {
			$query .= ' GROUP BY '.implode(' , ', $this->group).PHP_EOL;
		}

		if (!empty($this->having)) {
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
		
		if (!empty($this->limit)) {
			$query .= ' LIMIT '.$this->offset.' , '.$this->limit.PHP_EOL;
		}
			
		return $query;
	}
}