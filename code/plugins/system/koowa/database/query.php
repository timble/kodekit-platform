<?php
/**
 * @version		$Id$
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
 * @package     Koowa_Database
 * @subpackage  Query
 */
class KDatabaseQuery extends KObject
{
	/**
	 * The select element
	 *
	 * @var array
	 */
	protected $_select = array();

	/**
	 * The from element
	 *
	 * @var array
	 */
	protected $_from = array();

	/**
	 * The join element
	 *
	 * @var array
	 */
	protected $_join = array();

	/**
	 * The where element
	 *
	 * @var array
	 */
	protected $_where = array();

	/**
	 * The group element
	 *
	 * @var array
	 */
	protected $_group = array();

	/**
	 * The having element
	 *
	 * @var array
	 */
	protected $_having = array();

	/**
	 * The order element
	 *
	 * @var string
	 */
	protected $_order = array();

	/**
	 * The limit element
	 *
	 * @var integer
	 */
	protected $_limit = null;

	/**
	 * The limit offset element
	 *
	 * @var integer
	 */
	protected $_offset = null;

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
     * @see getInstance()
	 */
	public function __construct( $options = array() )
	{
        // Initialize the options
        $options  = $this->_initialize($options);

		//set the model dbo
		$this->_db = $options['dbo'] ? $options['dbo'] : KFactory::get('Database');
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
     * Get a KDatabaseQuery object, always creating it
     *
     * @param	array	Options array
     * @return 	object	KDatabaseQuery
     */
    public static function getInstance($options = array())
    {
        return new KDatabaseQuery($options);
    }

	/**
	 * Built a select query
	 *
	 * @param	array|string	A string or an array of field names
	 * @return object KDatabaseQuery
	 */
	public function select( $columns )
	{
		settype($columns, 'array'); //force to an array

		$this->_select = array_unique( array_merge( $this->_select, $columns ) );
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

		$this->_from = array_unique( array_merge( $this->_from, $tables ) );
		return $this;
	}

	/**
	 * Built the join clause of the query
	 *
	 * @param	string
	 * @param	array|string
	 * @return object KDatabaseQuery
	 */
	public function join( $type = 'LEFT', $conditions )
	{
		settype($conditions, 'array'); //force to an array

		$this->_join[strtoupper($type)] = array_unique( array_merge( $this->_join, $conditions ) );
		return $this;
	}

	/**
	 * Built the where clause of the query
	 *
	 * Automatically quotes the data values. If constraint is 'IN' the data values will not be quoted.
	 *
	 * @param   string 	The name of the property the constraint applies too
	 * @param	string  The comparison used for the constraint
	 * @param	string	The value compared to the property value using the constraint
	 * @return 	object 	KDatabaseQuery
	 */
	public function where( $property, $constraint, $value )
	{
		// Apply quotes to the property name
		$property = $this->_db->nameQuote($property);

		//Apply quotes to the propety value
		if($constraint != 'IN') {
			$value = $this->_db->Quote($value);
		}

		//Create the constraint
		$where = $property.' '.$constraint.' '.$value;

		$this->_where = array_unique( array_merge( $this->_where, array($where) ));
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

		$this->_group = array_unique( array_merge( $this->_group, array($columns ) ));
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

		$this->_having = array_unique( array_merge( $this->_having, array($columns ) ));
		return $this;
	}

	/**
	 * Built the order clause of the query
	 *
	 * @param	array|string	A string or array of ordering columns
	 * @return object KDatabaseQuery
	 */
	public function order( $columns )
	{
		settype($columns, 'array'); //force to an array

		$this->_order = array_unique( array_merge( $this->_order, array( $columns ) ));
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
		$this->_limit  = $limit;
		$this->_offset = $offset;
		return $this;
	}

	/**
	 * Render the query to a string
	 *
	 * @return	string	The completed query
	 */
	public function __toString()
	{
		$query = '';

		if (!empty($this->_select)) {
			$query .= 'SELECT '.implode(',', $this->_select);
		}

		if (!empty($this->_from)) {
			$query .= ' FROM '.implode(',', $this->_from);
		}

		if (!empty($this->_where)) {
			$query .= ' WHERE '.implode(' AND ', $this->_where);
		}

		if (!empty($this->_group)) {
			$query .= ' GROUP BY '.implode(',', $this->_group);
		}

		if (!empty($this->_having)) {
			$query .= ' HAVING '.implode(',', $this->_having);
		}

		if (!empty($this->_order)) {
			$query .= ' ORDER BY '.implode(',', $this->_order);
		}

		if (isset($this->_limit)) {
			$query .= ' LIMIT '.$this->_limit.','.$this->_offset;
		}

		return $query;
	}

    /**
     * Alias of __toString()
     */
    public function toString()
    {
        return $this->__toString();
    }
}