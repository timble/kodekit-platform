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
 * Abstract User
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\User
 */
abstract class UserAbstract extends Object implements UserInterface
{
    /**
     * The user properties
     *
     * @var ObjectConfig
     */
    private $__properties;

    /**
     * Constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return UserAbstract
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the user properties
        $this->setProperties($config->properties);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'properties' => array(
                'id'         => 0,
                'email'      => '',
                'name'       => '',
                'language'   => '',
                'timezone'   => '',
                'roles'      => array(),
                'groups'     => array(),
                'password'   => '',
                'salt'       => '',
                'authentic'  => false,
                'enabled'    => true,
                'expired'    => false,
                'parameters' => array(),
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Set the user properties from an array
     *
     * @param  array $data An associative array of data
     * @return UserAbstract
     */
    public function setProperties($properties)
    {
        $this->__properties = new ObjectConfigJson($properties);
        return $this;
    }

    /**
     * Get the user properties
     *
     * @return ObjectConfigJson
     */
    public function getProperties()
    {
        return $this->__properties;
    }

    /**
     * Returns the id of the user
     *
     * @return int The id
     */
    public function getId()
    {
        return $this->getProperties()->id;
    }

    /**
     * Returns the email of the user
     *
     * @return string The email
     */
    public function getEmail()
    {
        return $this->getProperties()->email;
    }

    /**
     * Returns the name of the user
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->getProperties()->name;
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
        return $this->getProperties()->language;
    }

    /**
     * Returns the user timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->getProperties()->timezone;
    }

    /**
     * Returns the roles of the user
     *
     * @return array An array of role identifiers
     */
    public function getRoles()
    {
        return ObjectConfig::unbox($this->getProperties()->roles);
    }

    /**
     * Checks if the user has a role.
     *
     * @param  mixed|array $role A role name or an array containing role names.
     * @return bool True if the user has at least one of the provided roles, false otherwise.
     */
    public function hasRole($role)
    {
        $roles = (array) $role;
        return (bool) array_intersect($this->getRoles(), $roles);
    }

    /**
     * Returns the groups the user is part of
     *
     * @return array An array of group identifiers
     */
    public function getGroups()
    {
        return ObjectConfig::unbox($this->getProperties()->groups);
    }

    /**
     * Returns the hashed password used to authenticate the user.
     *
     * This should be the hashed password. On authentication, a plain-text password will be salted, encoded, and
     * then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->getProperties()->password;
    }

    /**
     * Verifies that a plain text password matches the users hashed password
     *
     * @param string $password The plain-text password to verify
     * @return bool Returns TRUE if the plain-text password and users hashed password, or FALSE otherwise.
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->getPassword());
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string The salt
     */
    public function getSalt()
    {
        return $this->getProperties()->salt;
    }

    /**
     * Returns the user parameters
     *
     * @return array The parameters
     */
    public function getParameters()
    {
        return  ObjectConfig::unbox($this->getProperties()->parameters);
    }

    /**
     * The user has been successfully authenticated
     *
     * @param  boolean $strict If true, checks if the user has been authenticated for this request explicitly
     * @return Boolean
     */
    public function isAuthentic($strict = false)
    {
        return $this->getProperties()->authentic;
    }

    /**
     * Checks whether the user account is enabled.
     *
     * @return Boolean
     */
    public function isEnabled()
    {
        return $this->getProperties()->enabled;
    }

    /**
     * Checks whether the user account has expired.
     *
     * @return Boolean
     */
    public function isExpired()
    {
        return $this->getProperties()->expired;
    }

    /**
     * Sets the user as authenticated for the request
     *
     * @return $this
     */
    public function setAuthentic()
    {
        $this->getProperties()->authentic = true;

        return $this;
    }

    /**
     * Get an user parameter
     *
     * @param   string  $name    Parameter name
     * @param   mixed   $default Default value when the parameter doesn't exist
     * @return  mixed   The value
     */
    public function get($name, $default = null)
    {
        $result = $this->getParameters()->get($name, $default);
        return $result;
    }

    /**
     * Set an user parameter
     *
     * @param   mixed   $name    Parameter name
     * @param   mixed   $value   Parameter value
     * @return UserAbstract
     */
    public function set($name, $value)
    {
        $this->getParameters()->set($name, $value);
        return $this;
    }

    /**
     * Check if a user parameter exists
     *
     * @param   mixed   $name    Parameter name
     * @return  boolean
     */
    public function has($name)
    {
        return $this->getParameters()->has($name);
    }

    /**
     * Removes an user parameter
     *
     * @param   mixed   $name    Parameter name
     * @return UserAbstract
     */
    public function remove($name)
    {
        $this->getParameters()->remove($name);
        return $this;
    }

    /**
     * Check if the user is equal
     *
     * @param  UserInterface $user
     * @return Boolean
     */
    public function equals(ObjectInterface $user)
    {
        if($user instanceof UserInterface)
        {
            if($user->getEmail() == $this->getEmail())
            {
                if($user->getPassword() == $this->getPassword()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the user data as an array
     *
     * @return array An associative array of data
     */
    public function toArray()
    {
        return ObjectConfig::unbox($this->getProperties());
    }
}
