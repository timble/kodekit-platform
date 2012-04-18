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
 * Honeypot spam filter class.
 *
 * Honeypot check.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamHoneypot extends ComUsersFilterSpamAbstract {

	protected function _validate($data) {

		$data = new KConfig($data);

		// Check if the predefined honeypot field is not empty, i.e. a bot filled it.
		if(!empty($data->post->poohcheck)) {
			return false;
		}

		return true;

	}

}