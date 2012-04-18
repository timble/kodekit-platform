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
 * Abstract spam filter class.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

abstract class ComUsersFilterSpamAbstract extends KFilterAbstract 
{
	protected $_walk = false;

	protected function _sanitize($value) {
		// Nothing to do.
	}

	/**
	 * Email domain getter.
	 *
	 * @param  string The email address.
	 * @return string The domain from the email address.
	 */
	protected function _getEmailDomain($email)
	{
		$domain = strstr((string) $email, '@');
		if($domain === false) {
			return '';
		}
		return substr($domain, 1);

	}
}