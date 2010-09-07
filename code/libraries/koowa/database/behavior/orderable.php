<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Database Orderable Behavior
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorOrderable extends KDatabaseBehaviorAbstract
{

	/**
	 * Get the methods that are available for mixin based
	 *
	 * This functions conditionaly mixes the behavior. Only if the mixer
	 * has a 'ordering' property the behavior will be mixed in.
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
	 * Override to add a custom WHERE clause
	 *
	 * @param 	KDatabaseQuery $query
	 * @example	$query->where('category_id', '=', $this->id);
	 */
	public function _buildQueryWhere(KDatabaseQuery $query)
	{
	}

	/**
	 * Move the row up or down in the ordering
	 *
	 * Requires an 'ordering' column
	 *
	 * @param	integer	Amount to move up or down
	 * @return 	KDatabaseRowAbstract
	 * @throws 	KDatabaseBehaviorException
	 */
	public function order($change)
	{
		//force to integer
		settype($change, 'int');

		if($change !== 0)
		{
			$old = (int) $this->ordering;
			$new = $this->ordering + $change;
			$new = $new <= 0 ? 1 : $new;

			$query = KFactory::tmp('lib.koowa.database.query');
			$this->_buildQueryWhere($query);

			$update =  'UPDATE `#__'.KFactory::get($this->getTable())->getBase().'` ';
			if($change < 0) 
			{
				$update .= 'SET ordering = ordering+1 ';
				$query->where('ordering', '>=', $new)
					  ->where('ordering', '<', $old);
			} 
			else 
			{
				$update .= 'SET ordering = ordering-1 ';
				$query->where('ordering', '>', $old)
					  ->where('ordering', '<=', $new);
			}
			
			$update .= (string) $query;

			KFactory::get($this->getTable())->getDatabase()->execute($update);

			$this->ordering = $new;
			$this->save();
			$this->reorder();
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
		$table	= KFactory::get($this->getTable());
		$db 	= $table->getDatabase();
		$query 	= KFactory::tmp('lib.koowa.database.query');
		$this->_buildQueryWhere($query);

		$db->execute("SET @order = 0");
		$db->execute(
			 'UPDATE #__'.$table->getBase().' '
			.'SET ordering = (@order := @order + 1) '
			.(string) $query
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
    	$row = $context->data; //get the row data being inserted

    	if(isset($row->ordering) && $row->ordering <= 0)
    	{
        	$select = 'SELECT MAX(ordering) FROM `#__'.$context->table.'`';
        	$query 	= KFactory::tmp('lib.koowa.database.query');
			$this->_buildQueryWhere($query);
			$select .= (string) $query;
    		$row->ordering = (int) $context->caller->getDatabase()->select($select, KDatabase::FETCH_FIELD) + 1;
        }
    }

    /**
     * Changes the rows ordering if the virtual order field is set. Order is
     * relative to the row's current position.
     *
     * @param	KCommandContext Context
     */
    protected function _beforeTableUpdate(KCommandContext $context)
    {
    	$row = $context->data;

    	if(isset($row->order) && isset($row->ordering)) {
        	$row->order($row->order);
        }
    }

    /**
     * Clean up the ordering after an item was deleted
     *
     * @param	KCommandContext Context
     */
    protected function _afterTableDelete(KCommandContext $context)
    {
    	$context->data->reorder();
    }
}