<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Database Orderable Behavior (WIP)
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorOrderable extends KDatabaseBehaviorAbstract
{
	/**
	 * Get the methods that are available for mixin based
	 * 
	 * This functions allows for conditional mixing of the behavior. Only 
	 * if the mixer has a 'ordering' property the behavior will allow to 
	 * be mixed in.
	 * 
	 * @param object The mixer requesting the mixable methods. 
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods = array();
		
		if(isset($mixer->ordering)) {
			$methods = parent::getMixableMethods($mixer);
		}
	
		return $methods;
	}
	
	/**
	 * Move the row up or down in the ordering
	 *
	 * Requires an ordering field to be present in the table
	 *
	 * @param	integer	Amount to move up or down
	 * @return 	KDatabaseRowAbstract
	 * @throws 	KDatabaseBehaviorException
	 */
	public function order($change)
	{
		if (!isset($this->ordering)) {
			throw new KDatabaseBehaviorException("The table ".KFactory::get($this->getTable())->getName()." doesn't have a 'ordering' column.");
		}

		//force to integer
		settype($change, 'int');

		if($change !== 0)
		{
			$old = $this->ordering;
			$new = $this->ordering + $change;
			$new = $new <= 0 ? 1 : $new;

			$query =  'UPDATE `#__'.KFactory::get($this->getTable())->getName().'` ';

			if($change < 0) {
				$query .= 'SET ordering = ordering+1 WHERE '.$new.' <= ordering AND ordering < '.$old;
			} else {
				$query .= 'SET ordering = ordering-1 WHERE '.$old.' < ordering AND ordering <= '.$new;
			}

			KFactory::get($this->getTable())->getDatabase()->execute($query);

			$this->ordering = $new;
			$this->save();

			KFactory::get($this->getTable())->order();
		}

		return $this->_mixer;
	}
	
	/**
	 * Resets the order of all rows
	 *
	 * @return	KDatabaseTableAbstract
	 */
	public function reorder()
	{
		if (!in_array('ordering', $this->getColumns())) {
			throw new KDatabaseBehaviorException("The table ".$this->getBase()." doesn't have a 'ordering' column.");
		}

		$this->_database->execute("SET @order = 0");
		$this->_database->execute(
			 'UPDATE #__'.$this->getBase().' '
			.'SET ordering = (@order := @order + 1) '
			.'ORDER BY ordering ASC'
		);

		return $this;
	}
 	
 	/**
     * Saves the row to the database.
     *
     * This performs an intelligent insert/update and reloads the properties 
     * with fresh data from the table on success.
     *
     * @return KDatabaseRowAbstract
     */
    protected function _beforeTableInsert(KCommandContext $context)
    {
    	$row = $context['data']; //get the row data being inserted
    	
    	if(isset($row->ordering) && $row->ordering <= 0) 
    	{
        	$query = 'SELECT MAX(ordering) FROM `#__'.$context['table'];
    		$row->ordering = (int) $context['caller']->getDatabase()->fetchResult($query) + 1;
        }
    }
}