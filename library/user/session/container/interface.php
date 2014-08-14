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
 * Session Container Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
 */
interface UserSessionContainerInterface
{
    /**
     * Get a an attribute
     *
     * @param   string $identifier  Attribute identifier, eg .foo.bar
     * @param   mixed  $default     Default value when the attribute doesn't exist
     * @return  mixed   The value
     */
    public function get($identifier, $default = null);

    /**
     * Set an attribute
     *
     * @param   mixed   $identifier Attribute identifier, eg foo.bar
     * @param   mixed   $value      Attribute value
     * @return UserSessionContainerInterface
     */
    public function set($identifier, $value);

    /**
     * Check if an attribute exists
     *
     * @param   string  $identifier Attribute identifier, eg foo.bar
     * @return  boolean
     */
    public function has($identifier);

    /**
     * Removes an attribute
     *
     * @param string $identifier Attribute identifier, eg foo.bar
     * @return  UserSessionContainerInterface
     */
    public function remove($identifier);

    /**
     * Clears out all attributes
     *
     * @return  UserSessionContainerInterface
     */
    public function clear();

    /**
     * Adds new attributes
     *
     * @param array $attributes An array of attributes
     * @return  UserSessionContainerInterface
     */
    public function add(array $attributes);

    /**
     * Load the attributes by reference
     *
     * After starting a session, PHP retrieves the session data through the session handler and populates $_SESSION
     * with the result automatically. This function can load the attributes from the $_SESSION global by reference
     * by passing the $_SESSION to this function.
     *
     * @param array|null $session   The session attributes to load
     * @return  UserSessionContainerInterface
     */
    public function load(array &$session);

    /**
     * Set the session attributes namespace
     *
     * @param string $namespace The session attributes namespace
     * @return UserSessionContainerAbstract
     */
    public function setNamespace($namespace);

    /**
     * Get the session attributes namespace
     *
     * @return string The session attributes namespace
     */
    public function getNamespace();

    /**
     * Get the session attributes separator
     *
     * @return string The session attribute separator
     */
    public function getSeparator();

    /**
     * Get all attributes
     *
     * @return  array  An array of attributes
     */
    public function toArray();
}