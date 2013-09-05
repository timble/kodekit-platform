<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Password Database Row
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class DatabaseRowPassword extends Library\DatabaseRowTable
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        // TODO Remove when PHP 5.5 becomes a requirement.
        Library\ClassLoader::getInstance()->loadFile(JPATH_ROOT.'/component/users/legacy/password.php');
    }

    public function save()
    {
        $user = $this->getObject('com:users.model.users')
            ->email($this->id)
            ->getRow();

        // Check if referenced user actually exists.
        if ($user->isNew())
        {
            $this->setStatus(Library\Database::STATUS_FAILED);
            $this->setStatusMessage(\JText::sprintf('USER NOT FOUND', $this->id));
            return false;
        }

        if ($password = $this->password)
        {
            // Check the password length.
            $params = $this->getObject('application.extensions')->users->params;
            $length = $params->get('password_length', 5);

            if (strlen($password) < $length)
            {
                $this->setStatus(Library\Database::STATUS_FAILED);
                $this->setStatusMessage(\JText::sprintf('PASSWORD TOO SHORT', $length));
                return false;
            }

            if (!$this->isNew())
            {
                // Check if new and current hashes are the same.
                if ($this->verify($password))
                {
                    $this->setStatus(Library\Database::STATUS_FAILED);
                    $this->setStatusMessage(\JText::_('New and old passwords are the same'));
                    return false;
                }
            }

            // Reset expiration date.
            $this->resetExpiration(false);

            // Create hash.
            $this->hash = $this->getHash($password);

            // Clear reset.
            $this->reset = '';

            // Unset plain text password for allowing subsequent save calls.
            unset($this->password);
        }

        return parent::save();
    }

    /**
     * Generates a random password.
     *
     * @param int The length of the password.
     *
     * @return string The generated password.
     */
    public function getRandom($length = 8)
    {
        $bytes  = '';
        $return = '';

        if (function_exists('openssl_random_pseudo_bytes') && (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) {
            $bytes = openssl_random_pseudo_bytes($length + 1);
        }

        if ($bytes === '' && @is_readable('/dev/urandom') && ($handle = @fopen('/dev/urandom', 'rb')) !== false)
        {
            $bytes = fread($handle, $length + 1);
            fclose($handle);
        }

        if (strlen($bytes) < $length + 1)
        {
            $bytes        = '';
            $random_state = microtime();

            if (function_exists('getmypid')){
                $random_state .= getmypid();
            }

            for ($i = 0; $i < $length + 1; $i += 16)
            {
                $random_state = md5(microtime() . $random_state);
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

    public function getHash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Tests the current hash against a provided password.
     *
     * @param string $password The password to test.
     * @param string $hash     An optional hash to compare to. If no hash is provided, the current password hash
     *                         will be used instead.
     *
     * @return bool True if password matches the current hash, false otherwise.
     */
    public function verify($password, $hash = null)
    {
        $result = false;

        if (is_null($hash))
        {
            // Use current password hash instead.
            $hash = $this->hash;

            if (!$this->isNew())
            {
                // Check for MD5 hashes.
                if (strpos($hash, '$') === false)
                {
                    $parts = explode(':', $hash);
                    if ($parts[0] === md5($password . @$parts[1]))
                    {
                        // Valid password on existing record. Migrate to BCrypt.
                        $this->hash = $this->getHash($password);
                        $this->save();
                        $result = true;
                    }
                }
            }
        }

        if (!$result) {
            $result = password_verify($password, $hash);
        }

        return $result;
    }

    /**
     *  Sets the password for reset.
     *
     * @return mixed The plain text reset token, null if row is new.
     */
    public function setReset()
    {
        $token = null;
        if (!$this->isNew())
        {
            $token       = $this->getRandom(32);
            $this->reset = $this->getHash($token);
            $this->save();
        }

        return $token;
    }


    public function toArray()
    {
        $password = parent::toArray();
        unset($password['hash']);

        return $password;
    }
}