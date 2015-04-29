<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * User Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
 */
interface UserInterface extends ObjectEquatable
{
    /**
     * Returns the id of the user
     *
     * @return int The id
     */
    public function getId();

    /**
     * Returns the email of the user
     *
     * @return string The email
     */
    public function getEmail();

    /**
     * Returns the name of the user
     *
     * @return string The name
     */
    public function getName();

    /**
     * Returns the roles of the user
     *
     * @return array The role ids
     */
    public function getRoles();

    /**
     * Checks if the user has a role.
     *
     * @param  mixed|array $role A role name or an array containing role names.
     * @return bool True if the user has at least one of the provided roles, false otherwise.
     */
    public function hasRole($role);

    /**
     * Returns the groups the user is part of
     *
     * @return array An array of group id's
     */
    public function getGroups();

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text password will be salted, encoded, and
     * then compared to this value.
     *
     * @return string The password
     */
    public function getPassword();

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string The salt
     */
    public function getSalt();

    /**
     * The user has been successfully authenticated
     *
     * @return Boolean
     */
    public function isAuthentic();

    /**
     * Checks whether the user account is enabled.
     *
     * @return Boolean
     */
    public function isEnabled();

    /**
     * Checks whether the user credentials have expired.
     *
     * @return Boolean
     */
    public function isExpired();

    /**
     * Get an user attribute
     *
     * @param   string  $identifier Attribute identifier, eg .foo.bar
     * @param   mixed   $default Default value when the attribute doesn't exist
     * @return  mixed   The value
     */
    public function get($identifier, $default = null);

    /**
     * Set an user attribute
     *
     * @param   mixed   $identifier Attribute identifier, eg foo.bar
     * @param   mixed   $value      Attribute value
     * @return User
     */
    public function set($identifier, $value);

    /**
     * Check if a user attribute exists
     *
     * @param   string  $identifier Attribute identifier, eg foo.bar
     * @return  boolean
     */
    public function has($identifier);

    /**
     * Removes an user attribute
     *
     * @param string $identifier Attribute identifier, eg foo.bar
     * @return User
     */
    public function remove($identifier);

    /**
     * Get the user data as an array
     *
     * @return array An associative array of data
     */
    public function toArray();


}