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
 * Honeypot spam service filter class.
 *
 * Performs an IP check against the Project Honey Pot Project service (http://www.projecthoneypot.org/).
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamServiceHoneypot extends ComUsersFilterSpamServiceAbstract {

	protected function _validate($data) {

		$data = new KConfig($data);

		$reverse_ip = $data->reverse_ip;

		// SBL check.
		$lookup = $this->_api_key . '.' . $reverse_ip . '.dnsbl.httpbl.org.';
		$result = gethostbyname($lookup);
		if($result != $lookup) {
			$result = explode('.', $result);
			if($result[0] == '127') {
				if($result[3] >= 2) {
					return false;
				}
			}
		}
		return true;
	}

}