<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Table Rowset Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @uses 		KMixinClass
 */
class KDatabaseRowsetTable extends KDatabaseRowsetAbstract
{
	/**
	 * Table object or identifier (APP::com.COMPONENT.table.NAME)
	 *
	 * @var	string|object
	 */
	protected $_table;

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config = null)
	{
		parent::__construct($config);
		
		// Set the table indentifier.
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
	 * @throws	KDatabaseRowsetException	If the identifier is not a table identifier
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
	 * Reset the rowset
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function reset()
	{
		$result = parent::reset();
		
	    $this->_columns	   = array_keys($this->getTable()->getColumns());
	
		return $result;
	}

	/**
	 * Get an empty row
	 *
	 * @return	object	A KDatabaseRow object.
	 */
	public function getRow() 
	{
		return $this->getTable()->getRow();
	}

	/**
	 * Forward the call to each row
	 * 
	 * This functions overloads KDatabaseRowsetAbstract::__call and implements 
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