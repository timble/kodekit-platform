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
		
		$options  = $this->_initialize($options);
		
		if(isset($options['table'])) {
			$this->setTable($options['table']);
		}

		// Set the static states
		$this->_state
			->insert('limit'    , 'int', 0)
			->insert('offset'   , 'int', 0)
			->insert('order'    , 'cmd')
			->insert('direction', 'word', 'asc')
			->insert('search'   , 'string');
			
		
		//Get the table object
		$table = KFactory::get($this->getTable());
		
		//Set the table behaviors
		$table->addBehaviors($options['table_behaviors']);
			
		// Set the dynamic states based on the unique table keys
      	foreach($table->getUniqueKeys() as $key) {
      		$this->_state->insert($key->primary ? 'id' : $key->name, $key->filter, $key->default);
		}	
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
			'table'   			=> null,
			'table_behaviors'	=> array()
       	);
       	
        return array_merge($defaults, $options);
    }
    
	/**
     * Set the model state properties
     * 
     * This function overloads the KTableAbstract::set() function and only acts on state properties.
     *
     * @param   string|array|object	The name of the property, an associative array or an object
     * @param   mixed  				The value of the property
     * @return	KModelTable
     */
    public function set( $property, $value = null )
    {
    	// If limit has been changed, adjust offset accordingly
    	if($property == 'limit') {
    		$this->_state->offset = $value != 0 ? (floor($this->_state->offset / $value) * $value) : 0;
    	}
    	
    	parent::set($property, $value);
    
    	return $this;
    }
    
	/**
     * Set the model state properties
     * 
     * This function overloads the KModelAbstract::getState() function and calculates
     * the offset based on the list length.
     *
     * @return	KModelTable
     */
    public function getState()
    {
    	$limit  = $this->_state->limit;
    	$offset = $this->_state->offset;
    	
    	//If the offset is higher than the total recalculate the offset 
    	if($limit !== 0 && $offset !== 0)
    	{
    		$total = $this->getTotal();
    		
    		if($total !== 0 && $offset >= $total) { 
    			$this->_state->offset = floor(($total-1) / $limit) * $limit;
    		}
    	}
    	
    	return parent::getState();
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
	 * @param	mixed	An object that implements KFactoryIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a table identifier
	 * @return	KModelTable
	 */
	public function setTable($table)
	{
		$identifier = KFactory::identify($table);

		if($identifier->path[0] != 'table') {
			throw new KModelException('Identifier: '.$identifier.' is not a table identifier');
		}
		
		$this->_table = $identifier;
		return $this;
	}

    /**
     * Method to get a item object which represents a table row 
     * 
     * This method matches the model state against the table's unqiue keys. If a key
     * is found it is used to fetch the table row. If no state iformation can be used 
     * to retrieve the item an empty row will be returned instead
     * 
     * @return KDatabaseRow
     */
    public function getItem()
    {
        if (!isset($this->_item))
        {
        	$table = KFactory::get($this->getTable());
        	$query = null; 
        	
        	$keys = $table->getUniqueKeys();
        	if(!empty($keys))
        	{
        		$table = KFactory::get($this->getTable());
        		$query = $table->getDatabase()->getQuery();
        		
        		foreach($keys as $key)
         		{
         			$name = $key->primary ? 'id' : $key->name;
         			if($value = $this->_state->{$name}) 
         			{
         				$query->where('tbl.'.$key->name, '=', $value);
     				
         				//If the key is a primary key break loop
         				if($key->primary) break;
         			}
         		}
        	}
        	
        	//If we have a valid query get a rowset and retrieve the first row
        	if(!empty($query->where)) 
        	{
        		$this->_buildQueryFields($query);
        		$this->_buildQueryFrom($query);
        		$this->_buildQueryJoins($query);
        		$this->_buildQueryGroup($query);
        		$this->_buildQueryHaving($query);
        		
        		$row = $table->select($query)->current();
        	} 
        	else $row = false;
        	 
         	//Set the item, create an empty row if no data was returned from the database
        	$this->_item = ($row !== false) ? $row : KFactory::tmp($table->getRow(), array('table' => $table));
        }

        return $this->_item;
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
        	$query = $table->getDatabase()->getQuery();
        	
       	 	$this->_buildQueryFields($query);
        	$this->_buildQueryFrom($query);
        	$this->_buildQueryJoins($query);
        	$this->_buildQueryWhere($query);
        	$this->_buildQueryGroup($query);
        	$this->_buildQueryHaving($query);
        	$this->_buildQueryOrder($query);
        	$this->_buildQueryLimit($query);
        		
        	$this->_list = $table->select($query);
        }

        return $this->_list;
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
            $query = $table->getDatabase()->getQuery();

        	$this->_buildQueryFrom($query);
        	$this->_buildQueryJoins($query);
        	$this->_buildQueryWhere($query);
			
        	$this->_total = $table->count($query);
        }

        return $this->_total;
    }
    
    /**
     * Builds SELECT fields list for the query
     */
    protected function _buildQueryFields(KDatabaseQuery $query)
    {
		$query->select(array('tbl.*'));
    }

	/**
     * Builds FROM tables list for the query
     */
    protected function _buildQueryFrom(KDatabaseQuery $query)
    {
      	$name = KFactory::get($this->getTable())->getName();
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
    	foreach(KFactory::get($this->getTable())->getUniqueKeys() as $key)
      	{
      		$name = $key->primary ? 'id' : $key->name;
         	if($value = $this->_state->{$name}) 
         	{	
         		$query->where('tbl.'.$key->name, 'IN', $value);
         		break;
         	}
         }
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