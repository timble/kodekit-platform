<?php
/**
 * @version 	$Id: abstract.php 1528 2010-01-26 23:14:08Z johan $
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Database Creatable Behavior
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorCreatable extends KDatabaseBehaviorAbstract
{
	/**
	 * Set created information
	 * 	
	 * Requires an created_on and created_by field in the table schema
	 * 
	 * @return boolean	False if failed.
	 */
	protected function _beforeTableInsert(KCommandContext $context)
	{
		$context['data']['created_by']  = (int) KFactory::get('lib.koowa.user')->get('id');
		$context['data']['created_on']  = gmdate('Y-m-d H:i:s');
		
		return true;
	}
}