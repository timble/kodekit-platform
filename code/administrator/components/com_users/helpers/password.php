<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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
class ComUsersHelperPassword extends KObject
{
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
	    $bytes  = '';
	    $return = '';
        
        if (function_exists('openssl_random_pseudo_bytes') && (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) {
            $bytes = openssl_random_pseudo_bytes($length + 1);
        }
        
        if ($bytes === '' && @is_readable('/dev/urandom') && ($handle = @fopen('/dev/urandom', 'rb')) !== false) {
            $bytes = fread($handle, $length + 1);
            fclose($handle);
        }
        
        if (strlen($bytes) < $length + 1) 
        {
            $bytes = '';
            $random_state = microtime();
            
            if (function_exists('getmypid')) {
                $random_state .= getmypid();
            }
        
            for ($i = 0; $i < $length + 1; $i += 16) {
                $random_state = md5(microtime().$random_state);
                $bytes .= md5($random_state, true);
            }
            
            $bytes = substr($bytes, 0, $length + 1);
        }
        
        $salt  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $shift = ord($bytes[0]);
        
        for ($i = 1; $i <= $length; ++$i) 
        {
            $return .= $salt[($shift + ord($bytes[$i])) % strlen($salt)];
            $shift += ord($bytes[$i]);
        }
        
        return $return;
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

	/**
	 * Encrypts password.
	 *
	 * @param string The password.
	 * @param string The salt.
	 * @return string Encrypted password.
	 */
	public function encrypt($password, $salt = null)
	{
		$salt = is_null($salt) ? $this->getRandom(32) : $salt;
		$password = $this->getCrypted($password, $salt);
		
		return $password . ':' . $salt;
	}
}