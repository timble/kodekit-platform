<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Metadata User Session Container
 *
 * Session container that stores session metadata and provides utility functions.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
 */
class UserSessionContainerMetadata extends UserSessionContainerAbstract
{
    /**
     * Maximum session lifetime
     *
     * @var integer The session maximum lifetime in seconds
     * @see isExpired()
     */
    protected $_lifetime;

    /**
     * Load the attributes from the $_SESSION global
     *
     * @return UserSessionContainerAbstract
     */
    public function load(array &$session)
    {
        parent::load($session);

        //Update the session timers
        $this->_updateTimers();

        return $this;
    }

    /**
     * Set the session life time
     *
     * This specifies the number of seconds after which data will expire. An expired session will be destroyed
     * automatically during session start.
     *
     * @param integer $lifetime The session lifetime in seconds
     * @return UserSessionContainerMetadata
     */
    public function setLifetime($lifetime)
    {
        $this->_lifetime = $lifetime;
        return $this;
    }

    /**
     * Get the session life time
     *
     * @return integer The session life time in seconds
     */
    public function getLifetime()
    {
        return $this->_lifetime;
    }

    /**
     * Get a session token, if a token isn't set yet one will be generated.
     *
     * @param   boolean $refresh If true, force a new token to be created
     * @return  string  The session token
     */
    public function getToken($refresh = false)
    {
        if ($this->token === null || $refresh)
        {
            $salt = $this->_createSalt(12);
            $name = session_name();

            $this->token = sha1($salt.$name);
        }

        return $this->token;
    }

    /**
     * Get a session secret, a secret should never be exposed publicly
     *
     * @param   boolean $refresh If true, force a new token to be created
     * @return  string  The session token
     */
    public function getSecret()
    {
        if ($this->secret === null)
        {
            $salt = $this->_createSalt(12);
            $name = session_name();

            $this->secret = sha1($salt . $name);
        }

        return $this->secret;
    }

    /**
     * Create a new session nonce
     *
     * @return  string  The session nonce
     */
    public function createNonce()
    {
        $secret  = $this->getSecret();
        $timeout = $this->getLifetime();

        $nonce = $this->_createNonce($secret, $timeout);
        $this->nonces[$nonce] = $nonce;

        return $nonce;
    }

    /**
     * Verify a session nonce
     *
     * Checks to see if the nonce has been generated before.  If so, validate it's syntax and remove it.
     *
     * @param string $nonce The nonce to verify
     * @return  bool Returns true if the nonce exists and is valid.
     */
    public function verifyNonce($nonce)
    {
        if(isset($this->nonces[$nonce]))
        {
            //Remove the nonce from the store
            unset($this->nonces[$nonce]);

            //Validate the nonce
            $secret = $this->getSecret();
            if($this->_validateNonce($secret, $nonce)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the session has expired
     *
     * @return boolean Returns TRUE if the session has expired
     */
    public function isExpired()
    {
        $curTime = $this->timer['now'];
        $maxTime = $this->timer['last'] + $this->_lifetime;

        return ($maxTime < $curTime);
    }

    /**
     * Create a random string
     *
     * @param   integer $length Length of string
     * @return  string  Generated string
     */
    protected function _createSalt($length = 32)
    {
        static $chars ='qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM01234567890';

        $max  = strlen($chars) - 1;
        $salt = '';

        for ($i = 0; $i < $length; ++$i) {
            $salt .= $chars[(mt_rand(0, $max))];
        }

        return $salt;
    }

    /**
     * Generate a Nonce.
     *
     * The generated nonce will contains three parts, separated by a colon. The first part is the individual salt.
     * The second part is the time until the nonce is valid. The third part is a HMAC hash of the salt, the time, and
     * a secret value.
     *
     * @link http://en.wikipedia.org/wiki/Hash-based_message_authentication_code
     *
     * @param string  $secret  String with at least 10 characters. The same value must be passed to _validateNonce().
     * @param integer $timeout the time in seconds until the nonce becomes invalid.
     * @throws \InvalidArgumentException If the secret is not valid.
     * @return string the generated Nonce.
     */
    public function _createNonce($secret, $timeout = 180)
    {
        if (is_string($secret) == false || strlen($secret) < 10) {
            throw new \InvalidArgumentException("Missing valid secret");
        }

        $salt = $this->_createSalt(12);

        $lifetime = time() + $timeout;
        $nonce    = $salt . ':' . $lifetime . ':' . hash_hmac( 'sha1', $salt.$lifetime,  $secret );

        return $nonce;
    }

    /**
     * Check a previously generated Nonce.
     *
     * The nonce should contains three parts, separated by a colon. The first part is the individual salt. The
     * second part is the time until the nonce is valid. The third part is a hmac hash of the salt, the time, and
     * a secret value.
     *
     * @param string  $secret  String with at least 10 characters. The same value must be passed to _validateNonce().
     * @returns bool Whether the Nonce is valid.
     */
    public static function _validateNonce($secret, $nonce)
    {
        if (is_string($nonce) == false) {
            return false;
        }

        $a = explode(':', $nonce);
        if (count($a) != 3) {
            return false;
        }

        $salt     = $a[0];
        $lifetime = intval($a[1]);
        $hash     = $a[2];
        $back     = hash_hmac( 'sha1', $salt.$lifetime,  $secret );

        if ($back != $hash) {
            return false;
        }

        if (time() > $lifetime) {
            return false;
        }

        return true;
    }

    /**
     * Update the session timers
     *
     * @return  void
     */
    protected function _updateTimers()
    {
        if (!isset($this->timer))
        {
            $start = time();

            $timer = array(
                'start' => $start,
                'last'  => $start,
                'now'   => $start
            );
        }
        else $timer = $this->timer;

        $timer['last'] = $timer['now'];
        $timer['now']  = time();

        $this->timer = $timer;
    }
}