<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Blacklist spam filter class.
 *
 * Performs a check against the the internal blacklist table.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamBlacklist extends ComUsersFilterSpamAbstract {

	protected function _validate($data) 
	{
		$data = new KConfig($data);

		$ip = $data->ip;

		$spammer = $this->getService('com://admin/users.database.row.spammer')
			            ->setData(array('ip' => $ip));
		
		// Record found
		if($spammer->load()) {
			return false;
		}
		
		return true;
	}

}
