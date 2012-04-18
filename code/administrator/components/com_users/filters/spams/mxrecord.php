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
 * MX record spam filter class.
 *
 * Performs an MX record check.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamMxrecord extends ComUsersFilterSpamAbstract 
{
	protected function _validate($data) 
	{
		$data = new KConfig($data);

		if(!function_exists('getmxrr')) {
			throw new KFilterException('getmxrr function not available');
		}

		$email = $data->post->email;

		// No domain to check, assume passed.
		if(!$domain = $this->_getEmailDomain($email)) {
			return true;
		}

		if($domain[strlen($domain) - 1] != '.') {
		    $domain .= '.';
		}

		if(getmxrr($domain, $mxhosts) === false) 
		{
			// No MX records. Look for A RR (RFC2821)
			if(checkdnsrr($domain, 'A') !== false) 
			{
				// Valid MX
				return true;
			}
			
			// No MX record found.
			return false;
		}
		return true;
	}
}