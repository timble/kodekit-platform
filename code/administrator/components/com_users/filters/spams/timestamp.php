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
 * Timestamp spam filter class.
 *
 * Compares the current validation time againt the provided timestamp.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamTimestamp extends ComUsersFilterSpamAbstract 
{
	/**
	 * @var int The maximum period of time for considering a form as spammed.
	 */
	protected $_max_time;

	public function __construct(KConfig $config = null) 
	{
		if (!$config) {
			$config = new KConfig();
		}
		
		parent::__construct($config);

		$this->_max_time = $config->max_time;
	}

	protected function _initialize(KConfig $config) 
	{
		$config->append(array('max_time' => 5));
		parent::_initialize($config);
	}

	protected function _validate($data) 
	{
		$data = new KConfig($data);

		$post = $data->post;

		$timestamp = (string) $post->timestamp;
		$secret = (string) $data->secret;

		// Verify the provided timestamp
		$sha1 = sha1($timestamp . $secret );
		
		// Wrong hash, spammed.
		if($post->timestamp_secret != $sha1) {
			return false;
		}

		// Compare timestamps. Anything less than _max_time is considered as spam.
		if(time() - $timestamp < $this->_max_time) {
			return false;
		}

		return true;
	}
}