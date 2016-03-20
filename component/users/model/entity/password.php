<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

namespace Kodekit\Component\Users;

use Kodekit\Library;

/**
 * Password Model Entity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Component\Users
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
                if (password_verify($password, $this->hash))
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
        return password_hash($password, PASSWORD_DEFAULT);
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

    public function toArray()
    {
        $password = parent::toArray();

        // Unset sensible data.
        unset($password['hash']);
        unset($password['reset']);

        return $password;
    }

    /**
     * Return the hashed password
     *
     * @return  string  The query string.
     */
    final public function __toString()
    {
        return $this->hash;
    }
}
