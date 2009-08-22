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
		//Set the database adapter
		$this->_db = isset($options['adapter']) ? $options['adapter'] : KFactory::get('lib.koowa.database');

		parent::__construct($options);

		// set the table associated to the model
		if(isset($options['table'])) {
			$this->_table = $options['table'];
		}
		else
		{
			$table 			= KInflector::tableize($this->_identifier->name);
			$package		= $this->_identifier->package;
			$application 	= $this->_identifier->application;
			$this->_table   = $application.'::com.'.$package.'.table.'.$table;
		}
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
	 * @return this
	 */
	public function setDatabase($db)
	{
		$this->_db = $db;
		return $this;
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param	array	Options array for view. Optional.
	 * @return	object	The table object
	 */
	public function getTable(array $options = array())
	{
		if(!is_object($this->_table)) {
			$this->_table = KFactory::get($this->_table, $options);
		}

		return $this->_table;
	}

	/**
	 * Method to set a table object or identifier
	 *
	 * @param	string|object The table identifier to be used in KFactory or a table object
	 * @return	this
	 */
	public function setTable($identifier)
	{
		$this->_table = $identifier;
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
            $table = $this->getTable();
        	$query = $this->_buildQuery()->where('tbl.'.$table->getPrimaryKey(), '=', $this->getState('id'));
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
        	$table = $this->getTable();
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
            $table = $this->getTable();
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
    	$name = $this->getTable()->getTableName();
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
     * Builds a generic ORDER BY clasue based on the model's state
     */
    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
    	$order      = $this->getState('order');
       	$direction  = strtoupper($this->getState('direction', 'ASC'));

    	if($order) {
    		$query->order($order, $direction);
    	}

		if(in_array('ordering', $this->getTable()->getColumns())) {
    		$query->order('ordering', 'ASC');
    	}
    }

    /**
     * Builds LIMIT clause for the query
     */
    protected function _buildQueryLimit(KDatabaseQuery $query)
    {
		$query->limit($this->getState('limit'), $this->getState('offset'));
    }


}