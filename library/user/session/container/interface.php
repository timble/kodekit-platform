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
 * Session Container Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
    public function values(array $attributes);

    /**
     * Get all attributes
     *
     * @return  array  An array of attributes
     */
    public function toArray();

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
     * Load the attributes from the $_SESSION global
     *
     * After starting a session, PHP retrieves the session data through the session handler and populates $_SESSION
     * with the result automatically. This function will load the attributes from the $_SESSION global by reference.
     *
     * @param array|null $session   The session attributes to load
     * @return  UserSessionContainerInterface
     */
    public function loadSession(array &$session = null);
}