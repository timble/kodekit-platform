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
 * Bot Scout spam service filter class
 *
 * Performs an IP check against the Bot Scout service (http://www.botscout.com/).
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamServiceBotscout extends ComUsersFilterSpamServiceAbstract {

	protected function _validate($data) {

		$data = new KConfig($data);

		$email = $data->post->email;
		$ip = $data->ip;

		$url = 'http://botscout.com/test/?multi&mail=' . urlencode($email) . '&ip=' . urlencode($ip) . '&key=' . $this->_api_key;

		$response = $this->getService('com://admin/files.database.row.url', array('data' => array('file' => $url)))->load();

		if($response === false) {
			// Couldn't perform check, asume passed.
			return true;
		}

		// Cleanup string.
		$response = str_replace(array("\n", "\r", "\t", ' '), '', $response);

		$response = explode('|', $response);

		// We are only looking for a Y result
		if($response[0] == 'Y') {
			// Positive result.
			return false;
		}

		return true;
	}

}