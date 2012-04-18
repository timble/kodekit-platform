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
 * Abstract spam service filter class.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

abstract class ComUsersFilterSpamServiceAbstract extends ComUsersFilterSpamAbstract {

	/**
	 * @var string The service API key.
	 */
	protected $_api_key;

	/**
	 * @var bool Indicates if the service requires an API key for using it.
	 */
	protected $_has_api_key;

	public function __construct(KConfig $config = null) {
		if (!$config) {
			$config = new KConfig();
		}

		parent::__construct($config);

		$this->_has_api_key = $config->has_api_key;

		if ($this->_has_api_key && !$config->api_key) {
			throw new KFilterException('The API key is missing');
		}

		$this->_api_key = $config->api_key;
	}

	protected function _initialize(KConfig $config) {
		$config->append(array('api_key' => null, 'has_api_key' => true));
		parent::_initialize($config);
	}

	/**
	 * DNSBL Test.
	 *
	 * @param string The lookup string.
	 * @return mixed False if test succeeds (no block), lookup result otherwise.
	 */
	protected function _isDnsblBlocked($lookup)
	{
		if($lookup[strlen($lookup) - 1] != '.') $lookup .= '.';
		$result = gethostbyname($lookup);
		if($result == $lookup) {
			return false;
		} else {
			return $result;
		}
	}
}