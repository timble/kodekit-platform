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
 * Database Lockable Behavior
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorLockable extends KDatabaseBehaviorAbstract
{
	/**
	 * Get the methods that are available for mixin based
	 * 
	 * This function conditionaly mixies the behavior. Only if the mixer 
	 * has a 'locked_by' property the behavior will be mixed in.
	 * 
	 * @param object The mixer requesting the mixable methods. 
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods = array();
		
		if(isset($mixer->locked_by)) {
			$methods = parent::getMixableMethods($mixer);
		}
		
		return $methods;
	}
	
	/**
	 * Lock a row
	 *
	 * Requires an 'locked_on' and 'locked_by' column
	 *
	 * @return 	KDatabaseRowAbstract
	 */
	public function lock()
	{
		//Prevent lock take over, only an unlocked row and be locked
		if(isset($this->locked) && !$this->locked) 
		{
			$this->locked_by = (int) KFactory::get('lib.koowa.user')->get('id');
			$this->locked_on = gmdate('Y-m-d H:i:s');
			
			$this->save();
		}
		
		return $this->_mixer;
	}

	/**
	 * Unlock a row
	 *
	 * Requires an locked_on and locked_by column to be present in the table
	 *
	 * @return 	KDatabaseRowAbstract
	 */
	public function unlock()
	{
		if(isset($this->locked)) 
		{
			$this->locked_by = 0;
			$this->locked_on = 0;
			$this->locked    = false;
		
			$this->save();
		}
	
		return $this->_mixer;
	}
	
	/**
	 * Insert a virtual 'locked' property for each row. 
	 * 
	 * This function adds a virtual 'locked' property to each row which holds the row's locked 
	 * state. If the row was locked by the logged in user the locked property will be false, 
	 * otherwise true
	 * 
	 * @return void
	 */
	protected function _afterTableSelect(KCommandContext $context)
	{	
		$userid = KFactory::get('lib.koowa.user')->get('id');

		//Force to an array
		if($context->mode == KDatabase::FETCH_ROW) {
			$rowset = array($context->data);
		} else {
			$rowset = $context->data;
		}

		//Add virtual locked property
		foreach($rowset as $row)
		{
			if(isset($row->locked_by) && $row->locked_by != 0 && $row->locked_by != $userid) {
				$row->locked = true;
			} else {
				$row->locked = false;
			}
		}
	}
	
	/**
	 * Checks if a row can be updated
	 * 
	 * This function determines if a row can be updated based on it's locked_by information.
	 * If a row is locked, and not by the logged in user, the function will return false, 
	 * otherwise it will return true 
	 * 
	 * @return boolean True if row can be updated, false otherwise 
	 */
	protected function _beforeTableUpdate(KCommandContext $context)
	{
		$row    = $context->data;
		$userid = KFactory::get('lib.koowa.user')->get('id');
		
		if(isset($row->locked_by) && $row->locked_by != 0) 
		{
			if($row->locked_by != $userid) {
				return false;	
			}
		}
	
		return true;
	}
}