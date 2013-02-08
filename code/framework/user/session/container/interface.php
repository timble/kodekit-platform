<?php
/**
 * @version     $Id: response.php 4675 2012-06-03 01:05:49Z johanjanssens $
 * @package     Koowa_Session
 * @subpackage  Container
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Session Container Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_User
 * @subpackage  Session
 */
interface KUserSessionContainerInterface
{
    /**
     * Get a an attribute
     *
     * @param   string  Attribute identifier, eg .foo.bar
     * @param   mixed   Default value when the attribute doesn't exist
     * @return  mixed   The value
     */
    public function get($identifier, $default = null);

    /**
     * Set an attribute
     *
     * @param   mixed   Attribute identifier, eg foo.bar
     * @param   mixed   Attribute value
     * @return KUserSessionContainerInterface
     */
    public function set($identifier, $value);

    /**
     * Check if an attribute exists
     *
     * @param   string  Attribute identifier, eg foo.bar
     * @return  boolean
     */
    public function has($identifier);

    /**
     * Removes an attribute
     *
     * @param string $identifier Attribute identifier, eg foo.bar
     * @return  KUserSessionContainerInterface
     */
    public function remove($identifier);

    /**
     * Clears out all attributes
     *
     * @return  KUserSessionContainerInterface
     */
    public function clear();

    /**
     * Get all attributes
     *
     * @return  array  An array of attributes
     */
    public function toArray();

    /**
     * Adds new attributes
     *
     * @param array $attributes An array of attributes
     * @return  KUserSessionContainerInterface
     */
    public function fromArray(array $attributes);

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
     * @return  KUserSessionContainerInterface
     */
    public function loadSession(array &$session = null);
}