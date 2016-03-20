<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * User Singleton
 *
 * User is the user implementation used by the in-memory user provider. This object is tightly coupled to the session.
 * all data is stored and retrieved from the session attribute container, using a special 'user' namespace to avoid
 * conflicts.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\User
 */
class User extends UserAbstract implements ObjectSingleton
{
    /**
     * User authentication status for this request
     *
     * @var bool
     */
    protected $_authentic = false;

    /**
     * Returns the id of the user
     *
     * @return int The id
     */
    public function getId()
    {
        return $this->getSession()->get('user.id');
    }

    /**
     * Returns the email of the user
     *
     * @return string The email
     */
    public function getEmail()
    {
        return $this->getSession()->get('user.email');
    }

    /**
     * Returns the name of the user
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->getSession()->get('user.name');
    }

    /**
     * Returns the user language tag
     *
     * Should return a properly formatted IETF language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getSession()->get('user.language');
    }

    /**
     * Returns the user timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->getSession()->get('user.timezone');
    }

    /**
     * Returns the roles of the user
     *
     * @return array An array of role identifiers
     */
    public function getRoles()
    {
        return $this->getSession()->get('user.roles');
    }

    /**
     * Returns the groups the user is part of
     *
     * @return array An array of group identifiers
     */
    public function getGroups()
    {
        return $this->getSession()->get('user.groups');
    }

    /**
     * Returns the hashed password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text password will be salted, encoded, and
     * then compared to this value.
     *
     * @return string The password or NULL if no password defined
     */
    public function getPassword()
    {
        return null; //return NULL by default
    }

    /**
     * Returns the user parameters
     *
     * @return array The parameters
     */
    public function getParameters()
    {
        return $this->getSession()->get('user.parameters');
    }

    /**
     * Checks whether the user is not logged in
     *
     * @param  boolean $strict If true, checks if the user has been authenticated for this request explicitly
     * @return boolean True if the user is not logged in, false otherwise
     */
    public function isAuthentic($strict = false)
    {
        $result = $this->getSession()->get('user.authentic');

        if ($strict) {
            $result = $result && $this->_authentic;
        }

        return $result;
    }

    /**
     * Checks whether the user is enabled.
     *
     * @return Boolean true if the user is not logged in, false otherwise
     */
    public function isEnabled()
    {
        return $this->getSession()->get('user.enabled');
    }

    /**
     * Checks whether the user account has expired.
     *
     * @return Boolean
     */
    public function isExpired()
    {
        return $this->getSession()->get('user.expired');
    }

    /**
     * Sets the user as authenticated for the request
     *
     * @return $this
     */
    public function setAuthentic()
    {
        $this->_authentic = true;

        $this->getSession()->set('user.authentic', true);

        return $this;
    }

    /**
     * Get the user session
     *
     * This function will create a session object if it hasn't been created yet.
     *
     * @return UserSessionInterface
     */
    public function getSession()
    {
        return $this->getObject('user.session');
    }

    /**
     * Get the user data as an array
     *
     * @return array An associative array of data
     */
    public function toArray()
    {
        return $this->getSession()->get('user');
    }

    /**
     * Set the user properties from an array
     *
     * @param  array $properties An associative array
     * @return User
     */
    public function setProperties($properties)
    {
        parent::setProperties($properties);

        //Set the user data
        $this->getSession()->set('user', ObjectConfig::unbox($properties));

        return $this;
    }

    /**
     * Get an user parameter
     *
     * @param string $name The parameter name
     * @param   mixed   $value      Default value when the attribute doesn't exist
     * @return  mixed   The value
     */
    public function get($name, $default = null)
    {
        return $this->getSession()->get('user.parameters'.$name, $default);
    }

    /**
     * Set an user parameter
     *
     * @param string $name The parameter name
     * @param  mixed $value The parameter value
     * @return User
     */
    public function set($name, $value)
    {
        $this->getSession()->set('user.parameters'.$name, $value);
        return $this;
    }

    /**
     * Check if a user parameter exists
     *
     * @param string $name The parameter name
     * @return  boolean
     */
    public function has($name)
    {
        return $this->getSession()->has('user.parameters'.$name);
    }

    /**
     * Removes an user parameter
     *
     * @param string $name The parameter name
     * @return User
     */
    public function remove($name)
    {
        $this->getSession()->remove('user.parameters'.$name);
        return $this;
    }

    /**
     * Get a user parameter
     *
     * @param   string $name  The parameter name.
     * @return  mixed The parameter value
     */
    final public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Set a user parameter
     *
     * @param   string $name  The parameter name.
     * @param   mixed  $value The parameter value.
     * @return  void
     */
    final public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Test existence of a use parameter
     *
     * @param  string $name The parameter name.
     * @return boolean
     */
    final public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * Unset a user parameter
     *
     * @param   string $name  The parameter name.
     * @return  void
     */
    final public function __unset($name)
    {
        $this->remove($name);
    }
}