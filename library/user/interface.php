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
 * User Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\User
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
     * Returns the users language
     *
     * Should return a properly formatted IETF language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string The language tag
     */
    public function getLanguage();

    /**
     * Returns the users timezone
     *
     * @return string
     */
    public function getTimezone();

    /**
     * Returns the roles of the user
     *
     * @return array An array of role identifiers
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
     * @return array An array of group identifiers
     */
    public function getGroups();

    /**
     * Returns the hashed password used to authenticate the user.
     *
     * This should be the hashed password. On authentication, a plain-text password will be salted, encoded, and
     * then compared to this value.
     *
     * @return string The password
     */
    public function getPassword();

    /**
     * Verifies that a plain text password against the users hashed password
     *
     * @param string $password The plain-text password to verify
     * @return bool Returns TRUE if the plain-text password and users hashed password match, or FALSE otherwise.
     */
    public function verifyPassword($password);

    /**
     * Returns the user parameters
     *
     * @return array The parameters
     */
    public function getParameters();

    /**
     * The user has been successfully authenticated
     *
     * @param  boolean $strict If true, checks if the user has been authenticated for this request explicitly
     * @return boolean True if the user is not logged in, false otherwise
     */
    public function isAuthentic($strict = false);

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
     * Sets the user as authenticated for the request
     *
     * @return $this
     */
    public function setAuthentic();

    /**
     * Get an user parameter
     *
     * @param string $name The parameter name
     * @param   mixed   $value      Default value when the parameter doesn't exist
     * @return  mixed   The value
     */
    public function get($name, $default = null);

    /**
     * Set an user parameter
     *
     * @param string $name The parameter name
     * @param  mixed $value The parameter value
     * @return UserInterface
     */
    public function set($name, $value);

    /**
     * Check if a user parameter exists
     *
     * @param string $name The parameter name
     * @return  boolean
     */
    public function has($name);

    /**
     * Removes an user parameter
     *
     * @param string $name The parameter name
     * @return UserInterface
     */
    public function remove($name);

    /**
     * Get the user data as an array
     *
     * @return array An associative array of data
     */
    public function toArray();
}