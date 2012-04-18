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
 * User agent spam filter class.
 *
 * Checks if the user agent server data variable is not empty.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamUseragent extends ComUsersFilterSpamAbstract 
{
	protected function _validate($data) 
	{
		$data = new KConfig($data);

		$user_agent = $data->user_agent;
		
		if(empty($user_agent)) {
			return false;
		}
		
		return true;
	}
}