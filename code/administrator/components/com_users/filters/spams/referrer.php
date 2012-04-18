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
 * Referrer spam filter class.
 *
 * Checks for the existence of a referrer.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamReferrer extends ComUsersFilterSpamAbstract 
{
	protected function _validate($data) 
	{
		$data = new KConfig($data);

		$referrer = $data->referrer;

		if(empty($referrer)) {
			return false;
		}
		
		return true;
	}

}