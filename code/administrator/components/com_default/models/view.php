<?php
/**
 * @version     $Id: koowa.php 1296 2009-10-24 00:15:45Z johan $
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Default View Model
.*
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */
abstract class ComDefaultModelView extends KModelTable
{	
	/**
	 * Database View object or identifier (APP::com.COMPONENT.table.TABLENAME)
	 *
	 * @var	string|object
	 */
	protected $_view;
	
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
		
		// Set the table associated to the model
		$this->_view = $options['view'];
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
		
		$table 			= KInflector::tableize($this->_identifier->name);
		$package		= $this->_identifier->package;
		$application 	= $this->_identifier->application;
		
		$defaults = array(
			'view'    => $application.'::com.'.$package.'.table.'.$table
       	);
       	
        return array_merge($defaults, $options);
    }
    	
    /**
     * @todo this doesn't follow the new convention of returning identifiers instead of objects
     * 
     * Get a database view
     * 
     * @param 	array 	Options
     * @return	KDatabaseTableAbstract
     */
	public function getView(array $options = array())
	{
		if(!($this->_view instanceof KDatabaseTableAbstract || is_null($this->_view))) 
		{
			$package	= $this->_identifier->package;
			$name 		= $this->_identifier->name;
			
			$options['table_name'] = $package.'_view_'.$name;
			$options['primary']    = $package.'_'.KInflector::singularize($name).'_id';
			$options['database']   = $this->_db;
			
			try	{
				$this->_view = KFactory::tmp($this->_view, $options);
			} catch ( KDatabaseTableException $e ) { 
				$this->_view = null;
			}
		}
		
		return $this->_view;
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
        	if($table = $this->getView()) 
        	{
        		$query = null;
        	
         		foreach($table->getUniques() as $key)
         		{
         			if($value = $this->_state->{$key->name}) 
         			{
         				$query = $this->_buildQuery();
         				$query->where('tbl.'.$key->name, '=', $value);
         				break;
         			}
         		}
         				        	
        		$this->_item = $table->fetchRow($query);
        	} 
        	else $this->_item = null;
        }

        return parent::getItem();
    }

    public function getList()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_list))
        {
        	if($table = $this->getView()) 
        	{
        		$query = $this->_buildQuery();
        		$this->_list = $table->fetchRowset($query);
        	}
        	else $this->_list = array(); 
        }

        return parent::getList();
    }

    public function getTotal()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_total))
        {
            if($table = $this->getView())
            {
        		$query = $this->_buildCountQuery();
				$this->_total = $table->count($query);
            } 
            else $this->_total = 0; 
        }

        return parent::getTotal();
    }
    
	/**
     * Builds FROM tables list for the query
     */
    protected function _buildQueryFrom(KDatabaseQuery $query)
    {
      	$name = $this->getView()->getTableName();
    	$query->from($name.' AS tbl');
    }
}