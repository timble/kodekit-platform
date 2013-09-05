<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Metadata User Session Container
 *
 * Session container that stores session metadata and provides utility functions.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
        if ($this->token === null || $refresh) {
            $this->token = $this->_createToken(12);
        }

        return $this->token;
    }

    /**
     * Load the attributes from the $_SESSION global
     *
     * @return UserSessionContainerAbstract
     */
    public function loadSession(array &$session = null)
    {
        parent::loadSession($session);

        //Update the session timers
        $this->_updateTimers();

        return $this;
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
     * Create a token-string
     *
     * @param   integer $length Length of string
     * @return  string  Generated token
     */
    protected function _createToken($length = 32)
    {
        static $chars = '0123456789abcdef';

        $max   = strlen($chars) - 1;
        $token = '';
        $name  = session_name();

        for ($i = 0; $i < $length; ++$i) {
            $token .= $chars[(rand(0, $max))];
        }

        return md5($token . $name);
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