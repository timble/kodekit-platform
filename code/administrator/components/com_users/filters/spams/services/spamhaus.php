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
 * Spamhaus spam service filter class.
 *
 * Performs an IP check against the Spamhaus Project service (http://www.spamhaus.org/).
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamServiceSpamhaus extends ComUsersFilterSpamServiceAbstract {

	protected function _initialize(KConfig $config) {
		$config->append(array('has_api_key' => false));
		parent::_initialize($config);
	}

	protected function _validate($data) {

		$data = new KConfig($data);

		$reverse_ip = $data->reverse_ip;

		// SBL check.
		$result = $this->_isDnsblBlocked($reverse_ip . '.sbl.spamhaus.org');
		if($result !== false) {
			// Positive result.
			$result = explode('.', $result);
			if($result[0] == '127') {
				return false;
			}
		}
		// XBL check.
		$result = $this->_isDnsblBlocked($reverse_ip . '.xbl.spamhaus.org');
		if($result !== false) {
			// Positive result.
			$result = explode('.', $result);
			if($result[0] == '127') {
				return false;
			}
		}
		return true;
	}
}