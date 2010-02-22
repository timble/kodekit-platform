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
	 * Set modified information
	 * 	
	 * Requires a modified_on and modified_by field in the table schema
	 * 
	 * @return boolean	False if failed.
	 */
	protected function _beforeTableUpdate(KCommandContext $context)
	{
		$context['data']['modified_on']  = gmdate('Y-m-d H:i:s');
		$context['data']['modified_by']  = (int) KFactory::get('lib.koowa.user')->get('id');
	
		return true;
	}
}