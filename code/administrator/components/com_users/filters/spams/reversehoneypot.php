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
 * Reverse honeypot spam filter class.
 *
 * Performs a reverse honeypot check.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamReversehoneypot extends ComUsersFilterSpamAbstract 
{
	protected function _validate($data) 
	{
		$data = new KConfig($data);

		// Check if the predefined reverse honeypot field is not empty, i.e. a
		// bot not running JS left it filled.
		if(!empty($data->post->rpoohcheck)) {
			return false;
		}

		return true;
	}

}
