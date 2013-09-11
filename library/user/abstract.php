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
 * Abstract User
 *
 * User is the user implementation used by the in-memory user provider. This object is tightly coupled to the session.
 * all data is stored and retrieved from the session attribute container, using a special 'user' namespace to avoid
 * conflicts.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\User
 */
abstract class UserAbstract extends Object implements UserInterface
{
    /**
     * Constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return User
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the user properties and attributes
        $this->values(ObjectConfig::unbox($config));
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
            'id'         => 0,
            'email'      => '',
            'name'       => '',
            'role'       => 0,
            'groups'     => array(),
            'password'   => '',
            'salt'       => '',
            'authentic'  => false,
            'enabled'    => true,
            'expired'    => false,
            'attributes' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Set the user data from an array
     *
     * @param  array $data An associative array of data
     * @return User
     */
    public function values(array $data)
    {
        //Re-initialize the object
        $data = new ObjectConfig($data);
        $this->_initialize($data);

        unset($data['mixins']);
        unset($data['object_manager']);
        unset($data['object_identifier']);

        return $this;
    }

    /**
     * Returns the id of the user
     *
     * @return int The id
     */
    public function getId()
    {
        return $this->getConfig()->id;
    }

    /**
     * Returns the email of the user
     *
     * @return string The email
     */
    public function getEmail()
    {
        return $this->getConfig()->email;
    }

    /**
     * Returns the name of the user
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->getConfig()->name;
    }

    /**
     * Returns the role of the user
     *
     * @return int The role id
     */
    public function getRole()
    {
        return $this->getConfig()->role;
    }

    /**
     * Returns the groups the user is part of
     *
     * @return array An array of group id's
     */
    public function getGroups()
    {
        return $this->getConfig()->groups;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text password will be salted, encoded, and
     * then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->getConfig()->password;
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
        return $this->getConfig()->salt;
    }

    /**
     * The user has been successfully authenticated
     *
     * @return Boolean
     */
    public function isAuthentic()
    {
        return $this->getConfig()->authentic;
    }

    /**
     * Checks whether the user account is enabled.
     *
     * @return Boolean
     */
    public function isEnabled()
    {
        return $this->getConfig()->enabled;
    }

    /**
     * Checks whether the user account has expired.
     *
     * @return Boolean
     */
    public function isExpired()
    {
        return $this->getConfig()->expired;
    }

    /**
     * Get an user attribute
     *
     * @param   string  $identifier Attribute identifier, eg .foo.bar
     * @param   mixed   $default Default value when the attribute doesn't exist
     * @return  mixed   The value
     */
    public function get($identifier, $default = null)
    {
        $attributes = $this->getConfig()->attributes;

        $result = $default;
        if(isset($attributes[$identifier])) {
            $result = $attributes[$identifier];
        }

        return $result;
    }

    /**
     * Set an user attribute
     *
     * @param   mixed   $identifier Attribute identifier, eg foo.bar
     * @param   mixed   $value      Attribute value
     * @return UserAbstract
     */
    public function set($identifier, $value)
    {
        $attributes = $this->getConfig()->attributes;
        $attributes[$identifier] = $value;

        return $this;
    }

    /**
     * Check if a user attribute exists
     *
     * @param   string  $identifier Attribute identifier, eg foo.bar
     * @return  boolean
     */
    public function has($identifier)
    {
        $attributes = $this->getConfig()->attributes;
        if(isset($attributes[$identifier])) {
            return true;
        }

        return false;
    }

    /**
     * Removes an user attribute
     *
     * @param string $identifier Attribute identifier, eg foo.bar
     * @return User
     */
    public function remove($identifier)
    {
        if(isset($attributes[$identifier])) {
            unset($attributes[$identifier]);
        }

        return $this;
    }

    /**
     * Get the user data as an array
     *
     * @return array An associative array of data
     */
    public function toArray()
    {
        return ObjectConfig::unbox($this->getConfig());
    }
}