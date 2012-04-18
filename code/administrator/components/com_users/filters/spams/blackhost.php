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
 * Blacklisted host spam filter class
 *
 * Performs a check against the black hosts table (blacklisted hosts).
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamBlackhost extends ComUsersFilterSpamAbstract 
{
	protected function _validate($data) 
	{
		$data = new KConfig($data);

		$email  = $data->post->email;
		$domain = $this->_getEmailDomain($email);
		
		$host = $this->getService('com://admin/users.database.row.blackhost')
		            ->setData(array('name' => $domain));

		// Domain is blacklisted.
		if($host->load()) {
			return false;
		}
		
		return true;
	}
}