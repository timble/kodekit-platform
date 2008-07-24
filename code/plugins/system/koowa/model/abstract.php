<?php
/**
 * @version		$Id$
 * @package		Koowa_Model
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Model Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_Model
 * @uses		KPatternClass
 */
abstract class KModelAbstract extends KObject
{
	/**
	 * The base path
	 *
	 * @var		string
	 */
	protected $_basePath;

	/**
	 * Database Connector
	 *
	 * @var object
	 */
	protected $_db;

	/**
	 * A state object
	 *
	 * @var KRegistry object
	 */
	protected $_state;

    /**
     * List total
     *
     * @var integer
     */
    protected $_total;

    /**
     * Pagination object
     *
     * @var object
     */
    protected $_pagination;

	/**
	 * Model list data
	 *
	 * @var array
	 */
	protected $_list;

    /**
     * Model item data
     *
     * @var mixed
     */
    protected $_item;


	/**
	 * Constructor
     *
     * @param	array	Options
	 */
	public function __construct($options = array())
	{
        // Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KClass
        $this->mixin(new KPatternClass($this, 'Model'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Set a base path for use by the view
		$this->_basePath	= $options['base_path'];

		//set the model state
        // TODO move to KRegistry
        $this->_state = new JRegistry();
		$this->_state->merge($options['state']);
		$this->_setDefaultStates();


		//set the model dbo
		$this->_db = $options['dbo'] ? $options['dbo'] : KFactory::get('Database');
	}

    /**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize($options)
    {
        $defaults = array(
            'base_path'     => null,
            'dbo'           => null,
            'name'          => array(
                        'prefix'    => 'k',
                        'base'      => 'model',
                        'suffix'    => 'default'
                        ),
            'state'         => array()
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Method to set model state variables
	 *
	 * @param	string	The name of the property
	 * @param	mixed	The value of the property to set
	 * @return	this
	 */
	public function setState( $property, $value=null )
	{
		$this->_state->set($property, $value);
		return $this;
	}

	/**
	 * Method to get model state variables
	 *
	 * @param	string	Optional parameter name
	 * @return	object	The property where specified, the state object where omitted
	 */
	public function getState($property = null)
	{
		return $property === null ? $this->_state : $this->_state->get($property);
	}

	/**
	 * Method to get the database connector object
	 *
	 * @return	object KDatabase connector object
	 */
	public function getDBO()
	{
		return $this->_db;
	}

	/**
	 * Method to set the database connector object
	 *
	 * @param	object	$db	A KDatabase based object
	 * @return	void
	 */
	public function setDBO($db)
	{
		$this->_db = $db;
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * This function overrides the default model behavior and sets the table
	 * prefix based on the model prefix.
	 *
	 * @param	string  The table name
	 * @param	string  The class prefix
	 * @param	array	Options array for table. Optional.
	 * @return	object	The table
	 */
	public function getTable($name = '', $prefix = '', $options = array())
	{
		if ( empty( $prefix ) ) {
			$prefix = $this->getClassName('prefix');
		}

		if (empty($name)) {
			$name = KInflector::tableize($this->getClassName('suffix'));
		}

		//Make sure we are returning a DBO object
		if (!array_key_exists('dbo', $options))  {
			$options['dbo'] = $this->getDBO();;
		}

		$object = array(
			'type' 		=> 'table',
			'component'	=> $prefix,
			'name'		=> $name
		);

		$table = KFactory::getInstance($object, $options );

		return $table;
	}

    /**
     * Method to get a item object which represents a table row
     *
     * @return  object
     */
    public function getItem()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_item))
        {
            $this->_getItem();
        }

        return $this->_item;
    }

    /**
     * Get a list of items
     *
     * @return  array   List of objects
     */
    public function getList()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_list))
        {
            $query = $this->_buildQuery();
            $this->_list = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
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
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    /**
     * Get a Pagination object
     *
     * @return  JPagination
     */
    public function getPagination()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_pagination))
        {
            Koowa::import('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    /**
     * Get a list of filters
     *
     * @return  array
     */
    public function getFilters()
    {
        static $filters;

        if (is_null($filters))
        {
            $filters['limit']       = $this->getState('limit');
            $filters['limitstart']  = $this->getState('limitstart');
            $filters['order']       = $this->getState('order');
            $filters['order_Dir']   = $this->getState('order_Dir');
            $filters['filter']      = $this->getState('filter');
        }

        return $filters;
    }
    
    /**
     * Get the primary key's name
     *
     * @return	string
     */
    public function _getPrimaryKey() 
    {
    	$name       = $this->getClassName();
        return $name['prefix'] .'_'. KInflector::singularize($name['suffix']) .'_id';
    }

    /**
     * Builds a generic SELECT query
     *
     * @return  string  SELECT query
     */
    protected function _buildQuery()
    {
        $query  = 'SELECT '
                . $this->_buildQueryFields().' '
                . $this->_buildQueryFrom().' '
                . $this->_buildQueryJoins().' '
                . $this->_buildQueryWhere().' '
                . $this->_buildQueryOrder();
                
		return $query;
    }
    
    /**
     * Builds SELECT fields list for the query
     *
     * @return  string  Fields list
     */
    protected function _buildQueryFields()
    {
    	$keyname = $this->_getPrimaryKey();
        return "tbl.*, tbl.`$keyname` AS id";
    }
    
	/**
     * Builds FROM tables list for the query
     *
     * @return  string  FROm tables list
     */
    protected function _buildQueryFrom()
    {
    	$name       = $this->getClassName();
        $tablename  = $name['prefix'] .'_'. KInflector::tableize($name['suffix']);
        return "FROM `#__$tablename` AS tbl";
    }

    /**
     * Builds LEFT JOINS clauses for the query
     *
     * @return  string  LEFT JOIN clauses
     */
    protected function _buildQueryJoins()
    {
        return '';
    }

    /**
     * Builds a WHERE clause for the query
     *
     * @return  string  WHERE clause
     */
    protected function _buildQueryWhere()
    {
        // TODO a generic WHERE clause based on filters?
        return 'WHERE 1';
    }

    /**
     * Builds a generic ORDER BY clasue based on the model's state
     *
     * @return  string  ORDER BY clause or empty
     */
    protected function _buildQueryOrder()
    {
        static $orderby;

        if (!isset($orderby))
        {
            // Assemble the clause pieces
            $order      = $this->getState('order');
            $order_Dir  = strtoupper($this->getState('order_Dir'));

            // Assemble the clause
            $orderby    = $order ? 'ORDER BY '.$order.' '.$order_Dir : '';
        }

        return $orderby;
    }

	/**
	 * Returns an object list
	 *
	 * @param	string The query
	 * @param	int Offset
	 * @param	int The number of records
	 * @return	array
	 */
	protected function _getList( $query, $limitstart=0, $limit=0 )
	{
		$this->_db->select( $query, $limitstart, $limit );
		$result = $this->_db->loadObjectList($this->_getPrimaryKey());
		if($err = $this->_db->errorMsg()) {
			throw new KModelException($err);
		}
		return $result;
	}
	
	/**
	 * Returns an item
	 *
	 * @param	object	Item
	 */
	protected function _getItem()
	{
		//Get the table
		$table = $this->getTable();

		if ($this->getState('id'))
        {
        	$this->_db->select('SELECT *, '.$table->getPrimaryKey().' as id FROM '.'#__'.$table->getTableName().' WHERE '.$table->getPrimaryKey().' = '.(int)$this->getState('id'));
            $this->_item = $this->_db->loadObject();
        }
        else
        {
        	foreach($table->getFields() as $field)
			{
            	//If this is the primary key, change it's name to id
                if($field->primary) {
                	$field->name = 'id';
                }
                $this->_item[$field->name] = $field->default;
                // TODO implement this when $field->phptype or something similar is implemented
                // settype($this->_item[$field->name], $field->type);
            }

            settype($this->_item, 'object');
        }
        
        return $this->_item;
	}

	/**
	 * Returns a record count for the query
	 *
	 * @param	string The query
	 * @return	int
	 */
	protected function _getListCount( $query )
	{
		$this->_db->select( $query );
		$this->_db->query();

		return $this->_db->getNumRows();
	}

    /**
     * Set default states
     */
    protected function _setDefaultStates()
    {
        $app        = KFactory::get('Application');

        $ns         = $this->getClassName('prefix').'.'.$this->getClassName('suffix');

        // Get the display environment variables
        $limit      = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest($ns.'limitstart', 'limitstart', 0, 'int');
        $order      = $app->getUserStateFromRequest($ns.'filter_order', 'filter_order', '', 'cmd');
        $order_Dir  = $app->getUserStateFromRequest($ns.'filter_order_Dir', 'filter_order_Dir', 'ASC', 'word');
        $filter     = $app->getUserStateFromRequest($ns.'filter', 'filter', '', 'string');
        $id         = JRequest::getInt( 'id', null );

        // Push the environment states into the object
        $this->setState('limit',        $limit);
        $this->setState('limitstart',   $limitstart);
        $this->setState('order',        $order);
        $this->setState('order_Dir',    $order_Dir);
        $this->setState('filter',       $filter);
        $this->setState('id',           $id);
    }

}