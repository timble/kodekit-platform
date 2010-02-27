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
 * Database Behavior Interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorModifiable extends KDatabaseBehaviorAbstract
{
	/**
	 * Get the methods that are available for mixin based
	 * 
	 * This functions allows for conditional mixing of the behavior. Only if 
	 * the mixer has a 'modified_by' property the behavior will allow to be 
	 * mixed in.
	 * 
	 * @param object The mixer requesting the mixable methods. 
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods = array();
		
		if(isset($mixer->modified_by)) {
			$methods = parent::getMixableMethods($mixer);
		}
	
		return $methods;
	}
	
	/**
	 * Set modified information
	 * 	
	 * Requires a modified_on and modified_by field in the table schema
	 * 
	 * @return boolean	False if failed.
	 */
	protected function _beforeTableUpdate(KCommandContext $context)
	{
		$row = $context['data']; //get the row data being inserted
		
		$row->modified_on = gmdate('Y-m-d H:i:s');
		$row->modified_by = (int) KFactory::get('lib.koowa.user')->get('id');
	
		return true;
	}
}