<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Model
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Table Model Class
 * 
 * Provides interaction with a database table
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Model
 */
class KModelTable extends KModelAbstract
{
	/**
	 * Database adapter
	 *
	 * @var object
	 */
	protected $_db;

	/**
	 * Table object or identifier (APP::com.COMPONENT.table.TABLENAME)
	 *
	 * @var	string|object
	 */
	protected $_table;

	/**
	 * Constructor
     *
     * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		
		// Initialize the options
		$options  = $this->_initialize($options);
		
		// Set the databse adapter
		$this->setDatabase($options['adapter']);
		
		// Set the table indentifier
		$this->setTable($options['table']);
				
		$this->_state
			->insert('id'       , 'int', 0)
			->insert('limit'    , 'int', 20)
			->insert('offset'   , 'int', 0)
			->insert('order'    , 'cmd')
			->insert('direction', 'word', 'asc')
			->insert('search'   , 'string');
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
		$options = parent::_initialize($options);
		
		$defaults = array(
            'adapter' => KFactory::get('lib.koowa.database'),
			'table'   => null,
       	);
       	
        return array_merge($defaults, $options);
    }
    
	/**
     * Set the model state properties
     * 
     * This function overloads the KObject::set() function and only acts on state properties.
     *
     * @param   string|array|object	The name of the property, an associative array or an object
     * @param   mixed  				The value of the property
     * @return	KModelTable
     */
    public function set( $property, $value = null )
    {
    	parent::set($property, $value);
    	
    	$limit  = $this->_state->limit;
    	$offset = $this->_state->offset;
    	$total  = $this->getTotal();
    	
    	// If limit has been changed, adjust offset accordingly
    	$offset = ($limit != 0 ? (floor($offset / $limit) * $limit) : 0);
    	
    	//If the offset is higher than the total, reset offset to total
    	if($total !== 0 && $offset >= $total) { 
    		$offset = floor(($total-1) / $limit) * $limit;
    	}
    	
    	$this->_state->offset = $offset;
    	
    	return $this;
    }

	/**
	 * Method to get the database adapter object
	 *
	 * @return KDatabaseAdapterAbstract
	 */
	public function getDatabase()
	{
		return $this->_db;
	}

	/**
	 * Method to set the database connector object
	 *
	 * @param	object	A KDatabaseAdapterAbstract object
	 * @return KDatabaseAdapterAbstract
	 */
	public function setDatabase($db)
	{
		$this->_db = $db;
		return $this;
	}

	/**
	 * Get the identifier for the table with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getTable()
	{
		if(!$this->_table)
		{
			$identifier 		= clone $this->_identifier;
			$identifier->name	= KInflector::tableize($identifier->name);
			$identifier->path	= array('table');
		
			$this->_table = $identifier;
		}
       	
		return $this->_table;
	}

	/**
	 * Method to set a table object attached to the model
	 *
	 * @param	object	An KIdentifier object or a KFactoryIdentifiable object
	 * @return	KModelTable
	 */
	public function setTable($table)
	{
		if(is_object($table)) 
		{
			if($model instanceof KIndentifier) {
				$this->_table = $table;
			}
			
			if(array_key_exists('KFactoryIdentifiable', class_implements($table))) {
				$this->_table = $table->getIdentifier();
			}
		} 
		
		return $this;
	}

    /**
     * Method to get a item object which represents a table row
     *
     * @return KDatabaseRow
     */
    public function getItem()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_item))
        {
        	$table = KFactory::get($this->getTable());
        	$query = $this->_buildQuery()->where('tbl.'.$table->getPrimaryKey(), '=', $this->_state->id);
        	$this->_item = $table->fetchRow($query);
        }

        return parent::getItem();
    }

    /**
     * Get a list of items which represnts a  table rowset
     *
     * @return KDatabaseRowset
     */
    public function getList()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_list))
        {
        	$table = KFactory::get($this->getTable());
        	$query = $this->_buildQuery();
        	$this->_list = $table->fetchRowset($query);
        }

        return parent::getList();
    }

    /**
     * Get the total amount of items
     *
     * @return  int
     */
    public function getTotal()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_total))
        {
            $table = KFactory::get($this->getTable());
            $query = $this->_buildCountQuery();
			$this->_total = $table->count($query);
        }

        return parent::getTotal();
    }


    /**
     * Builds a generic SELECT query
     *
     * @return  string  KDatabaseQuery
     */
    protected function _buildQuery()
    {
    	$query = $this->_db->getQuery();
        $query->select(array('tbl.*'));

        $this->_buildQueryFields($query);
        $this->_buildQueryFrom($query);
        $this->_buildQueryJoins($query);
        $this->_buildQueryWhere($query);
        $this->_buildQueryGroup($query);
        $this->_buildQueryHaving($query);
        $this->_buildQueryOrder($query);
        $this->_buildQueryLimit($query);
  
		return $query;
    }

 	/**
     * Builds a generic SELECT COUNT(*) query
     */
    protected function _buildCountQuery()
    {
        $query = $this->_db->getQuery();

        $this->_buildQueryFrom($query);
        $this->_buildQueryJoins($query);
        $this->_buildQueryWhere($query);

        return $query;
    }

    /**
     * Builds SELECT fields list for the query
     */
    protected function _buildQueryFields(KDatabaseQuery $query)
    {

    }

	/**
     * Builds FROM tables list for the query
     */
    protected function _buildQueryFrom(KDatabaseQuery $query)
    {
      	$name = KFactory::get($this->getTable())->getTableName();
    	$query->from($name.' AS tbl');
    }

    /**
     * Builds LEFT JOINS clauses for the query
     */
    protected function _buildQueryJoins(KDatabaseQuery $query)
    {

    }

    /**
     * Builds a WHERE clause for the query
     */
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {

    }
    
  	/**
     * Builds a GROUP BY clause for the query
     */
    protected function _buildQueryGroup(KDatabaseQuery $query)
    {
    	
    }

    /**
     * Builds a HAVING clause for the query
     */
    protected function _buildQueryHaving(KDatabaseQuery $query)
    {
    	
    }

    /**
     * Builds a generic ORDER BY clasue based on the model's state
     */
    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
    	$order      = $this->_state->order;
       	$direction  = strtoupper($this->_state->direction);

    	if($order) {
    		$query->order($order, $direction);
    	}

		if(in_array('ordering', KFactory::get($this->getTable())->getColumns())) {
    		$query->order('ordering', 'ASC');
    	}
    }

    /**
     * Builds LIMIT clause for the query
     */
    protected function _buildQueryLimit(KDatabaseQuery $query)
    {
		$query->limit($this->_state->limit, $this->_state->offset);
    }
}