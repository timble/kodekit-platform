<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Password Model Entity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Nooku\Component\Users
 */
class ModelEntityPassword extends Library\ModelEntityRow
{
    /**
     * Minimum password length for new passwords.
     *
     * @var integer
     */
    protected $_length;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the minimum passowrd length
        $this->_length = (int) $config->length;

        // TODO Remove when PHP 5.5 becomes a requirement.
        require_once \Nooku::getInstance()->getRootPath().'/component/users/legacy/password.php';
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'length' => 5
        ));

        parent::_initialize($config);
    }

    /**
     * Minimum password length getter.
     *
     * @return int The minimum password length.
     */
    public function getLength()
    {
        return $this->_length;
    }

    public function save()
    {
        if ($password = $this->password)
        {
            // Check the password length.
            if (strlen($password) < $this->getLength())
            {
                $message = $this->getObject('translator')->translate(
                    'You need to provide a password with at least {number} characters.',
                    array('number' => $this->getLength())
                );

                $this->setStatus(self::STATUS_FAILED);
                $this->setStatusMessage($message);
                return false;
            }

            if (!$this->isNew())
            {
                // Check if new and current hashes are the same.
                if ($this->verifyPassword($password))
                {
                    $this->setStatus(self::STATUS_FAILED);
                    $this->setStatusMessage($this->getObject('translator')
                                            ->translate('New and old passwords are the same'));
                    return false;
                }
            }

            // Reset expiration date.
            if ($this->isExpirable()) {
                $this->resetExpiration(false);
            }

            // Create hash.
            $this->hash = $this->createHash($password);

            // Clear reset.
            $this->reset = '';

            // Unset plain text password for allowing subsequent save calls.
            unset($this->password);
        }

        return parent::save();
    }

    /**
     * Generates a password hash
     *
     * @param int $length The length of the random password.
     * @return string The generated password.
     */
    public function createHash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Generates a password.
     *
     * @param int $length The length of the random password.
     * @return string The generated password.
     */
    public function createPassword($length = null)
    {
        if (is_null($length))
        {
            $length = $this->getLength();
        }

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

    /**
     * Tests the current hash against a provided password.
     *
     * @param string $password The password to test.
     * @param string $hash     An optional hash to compare to. If no hash is provided, the current password hash
     *                         will be used instead.
     *
     * @return bool True if password matches the current hash, false otherwise.
     */
    public function verifyPassword($password, $hash = null)
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
                        $this->hash = $this->createHash($password);
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
    public function resetPassword()
    {
        $token = null;
        if (!$this->isNew())
        {
            $token       = $this->createPassword(32);
            $this->reset = $this->createHash($token);
            $this->save();
        }

        return $token;
    }


    public function toArray()
    {
        $password = parent::toArray();

        // Unset sensible data.
        unset($password['hash']);
        unset($password['reset']);

        return $password;
    }
}
