<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Users Password Helper Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersHelperPassword extends KObject implements KObjectIdentifiable
{
	/**
	 * Gets the object identifier.
	 *
	 * @return	KIdentifier
	 * @see 	KObjectIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Gets the encrypted password.
	 *
	 * @param  string  The plan password.
	 * @param  string  The salt to use to encrypt the password.
	 * @return string  The encrypted password.
	 */
	public function getCrypted($password, $salt = '')
	{
		return md5($password.$salt);
	}

	/**
	 * Generates a random password.
	 *
	 * @param  int     The length of the password.
	 * @return string  The generated password.
	 */
	public function getRandom($length = 8)
	{
		$salt		= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password	= '';

		$stat = @stat(__FILE__);

		if(empty($stat) || !is_array($stat)) {
			$stat = array(php_uname());
		}

		mt_srand(crc32(microtime().implode('|', $stat)));

		for($i = 0; $i < $length; $i ++) {
			$password .= $salt[mt_rand(0, strlen($salt) -1)];
		}

		return $password;
	}

    /**
     * Provides a secure hash based on a seed.
     *
     * @param  string Seed string.
     * @return string
     */
    public function getHash($seed)
    {
        $secret = JFactory::getConfig()->getValue('config.secret');
        return md5($secret.$seed);
    }

    /**
     * Returns a salt.
     *
     * @param string The seed to get the salt from (probably a previously generated password).
     * 			     Defaults to generating a new seed.
     * @return string  The generated or extracted salt.
     */
    public function getSalt($seed)
    {
        if($seed) {
            $result = substr(preg_replace('|^{crypt}|i', '', $seed), 0, 2);
        } else {
            $result = substr(md5(mt_rand()), 0, 2);
        }

        return $result;
    }
}