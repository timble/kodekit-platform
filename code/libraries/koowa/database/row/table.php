<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Table Row Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 */
class KDatabaseRowTable extends KDatabaseRowAbstract
{
	/**
	 * Table object or identifier (APP::com.COMPONENT.table.NAME)
	 *
	 * @var	string|object
	 */
	protected $_table;

	/**
     * Object constructor 
     *
     * @param   object  An optional KConfig object with configuration options.
     */
	public function __construct(KConfig $config = null)
	{
		parent::__construct($config);

		// Set the table indentifier
		if(isset($config->table)) {
			$this->setTable($config->table);
		}
	}

	/**
	 * Initializes the options for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 * @return void
	 */
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'table'	=> null
		));

		parent::_initialize($config);
	}

	/**
	 * Get the identifier for the table with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getTable()
	{
		if(!$this->_table)
		{
			$identifier 		= clone $this->_identifier;
			$identifier->name	= KInflector::tableize($identifier->name);
			$identifier->path	= array('database', 'table');

			$this->_table = KFactory::get($identifier);
		}

		return $this->_table;
	}

	/**
	 * Method to set a table object attached to the rowset
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowException	If the identifier is not a table identifier
	 * @return	KDatabaseRowsetAbstract
	 */
	public function setTable($table)
	{
		if(!($table instanceof KDatabaseTableAbstract))
		{
			$identifier = KFactory::identify($table);

			if($identifier->path[0] != 'table') {
				throw new KModelException('Identifier: '.$identifier.' is not a table identifier');
			}

			$table = KFactory::get($identifier);
		}

		$this->_table = $table;
		return $this;
	}

	/**
	 * Load the row from the database using the data in the row
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function load()
	{
		$result = false;
		
		if($this->_new)
		{
            $table = $this->getTable();

		    $data  = $table->filter($this->getData(true), true);
		    $row   = $table->select($data, KDatabase::FETCH_ROW);

		    // Set the data if the row was loaded succesfully.
		    if(!$row->isNew())
		    {
			    $this->setData($row->toArray(), false);
			    $this->_modified = array();
			    $this->_new      = false;

			    $this->setStatus(KDatabase::STATUS_LOADED);
			    $result = true;
		    }
		    
		    $this->setStatus(KDatabase::STATUS_FAILED);
		}
	
		return $result;
	}

	/**
	 * Saves the row to the database.
	 *
	 * This performs an intelligent insert/update and reloads the properties
	 * with fresh data from the table on success.
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function save()
	{
		if($this->_new) {
		    $result = $this->getTable()->insert($this);;
		} else {
			$result = $this->getTable()->update($this);
		}

		return (bool) $result;
    }

	/**
	 * Deletes the row form the database.
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function delete()
	{
		$result = false;

		if(!$this->_new) {
		    $result = $this->getTable()->delete($this);
		}

		return (bool) $result;
	}

	/**
	 * Reset the row data using the defaults
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function reset()
	{
		$result = false;
	    
	    if($this->_data = $this->getTable()->getDefaults()) {
		    $this->setStatus(null);
		    $result = true;
		}
		
		return $result;
	}

	/**
	 * Count the rows in the database based on the data in the row
	 *
	 * @return integer
	 */
	public function count()
	{
		$data  = $this->getTable()->filter($this->getData(true), true);
		$count =  $this->getTable()->count($data);

		return $count;
	}

	/**
	 * Unset a row field
	 *
	 * This function will reset required column to their default value, not required
	 * fields will be unset.
	 *
	 * @param	string  The column name.
	 * @return	void
	 */
	public function __unset($column)
	{
		$field = $this->getTable()->getColumn($column);

		if(isset($field) && $field->required) {
			$this->_data[$column] = $field->default;
		} else {
			parent::__unset($column);
		}
	}

	/**
	 * Search the mixin method map and call the method or trigger an error
	 *
	 * This functions overloads KDatabaseRowAbstract::__call and implements 
	 * a just in time mixin strategy. Available table behaviors are only mixed 
	 * when needed.
	 *
	 * @param  string 	The function name
	 * @param  array  	The function arguments
	 * @throws BadMethodCallException 	If method could not be found
	 * @return mixed The result of the function
	 */
	public function __call($method, array $arguments)
	{ 
	    // If the method hasn't been mixed yet, load all the behaviors.
		if(!isset($this->_mixed_methods[$method]))
		{
			foreach($this->getTable()->getBehaviors() as $behavior) {
				$this->mixin($behavior);
			}
		}

		return parent::__call($method, $arguments);
	}
}