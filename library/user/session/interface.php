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
 * User Session Interface
 *
 * Provides access to session-state values as well as session-level settings and lifetime management methods.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\User
 */
interface UserSessionInterface
{
    /**
     * Get the session life time
     *
     * @return integer The session life time in seconds
     */
    public function getLifetime();

    /**
     * Set the session life time
     *
     * This specifies the number of seconds after which data will expire. An expired session will be destroyed
     * automatically during session start.
     *
     * @param integer $lifetime The session lifetime in seconds
     * @return UserSessionContainerMetadata
     */
    public function setLifetime($lifetime);

    /**
     * Set the global session namespace
     *
     * This specifies namespace that is used when storing or retrieving attributes from the $_SESSION global. The
     * namespace prevents session conflicts when the session is shared.
     *
     * @param string $namespace The session namespace
     * @throws \LogicException When changing the namespace of an active session
     * @return UserSession
     */
    public function setNamespace($namespace);

    /**
     * Get the session namespace
     *
     * @return string The session namespace
     */
    public function getNamespace();

    /**
     * Get the session token, if a token isn't set yet one will be generated.
     *
     * @param   boolean $refresh If true, a new token to be created
     * @return  string  The session token
     */
    public function getToken($refresh = false);

    /**
     * Get the session id
     *
     * @return string The session id
     */
    public function getId();

    /**
     * Set the session id
     *
     * @param string $session_id
     * @throws \LogicException	When changing the id of an active session
     * @return UserSessionInterface
     */
    public function setId($session_id);

    /**
     * Get the session name
     *
     * @return string The session name
     */
    public function getName();

    /**
     * Set the session name
     *
     * @param  string $name
     * @throws \LogicException	When changing the name of an active session
     * @return UserSessionInterface
     */
    public function setName($name);

    /**
     * Method to set a session handler object
     *
     * @param mixed $handler An object that implements ObjectInterface, ObjectIdentifier object
     *                       or valid identifier string
     * @param array $config An optional associative array of configuration settings
     * @return UserSession
     */
    public function setHandler($handler, $config = array());

    /**
     * Get the session handler object
     *
     * @throws \UnexpectedValueException    If the identifier is not a session handler identifier
     * @return UserSessionHandlerInterface
     */
    public function getHandler();

    /**
     * Check if a container exists
     *
     * @param   string  $name  The name of the behavior
     * @return  boolean TRUE if the behavior exists, FALSE otherwise
     */
    public function hasContainer($name);

    /**
     * Get the session attribute container object
     *
     * If the container does not exist a container will be created on the fly.
     *
     * @param   mixed $name An object that implements ObjectInterface, ObjectIdentifier object
     *                      or valid identifier string
     * @throws \UnexpectedValueException    If the identifier is not a session container identifier
     * @return UserSessionContainerInterface
     */
    public function getContainer($name);

    /**
     * Starts the session storage and load the session data into memory
     *
     * @see  session_start()
     * @return UserSessionInterface
     * @throws \RuntimeException If something goes wrong starting the session.
     */
    public function start();

    /**
     * Force the session to be saved and closed.
     *
     * Session data is usually stored after your script terminated without the need to call DispatcherSession::save(),
     * but as session data is locked to prevent concurrent writes only one script may operate on a session at any time.
     *
     * When using framesets together with sessions you will experience the frames loading one by one due to this locking.
     * You can reduce the time needed to load all the frames by ending the session as soon as all changes to session
     * variables are done.
     *
     * @see  session_write_close()
     * @return UserSessionInterface
     */
    public function close();

    /**
     * Clear all session data in memory.
     *
     * @see session_unset()
     * @return UserSessionInterface
     */
    public function clear();

    /**
     * Frees all session variables and destroys all data registered to a session
     *
     * This method resets the $_SESSION variable and destroys all of the data associated with the current session in its
     * storage (file or DB). It forces new session to be started after this method is called. It does not unset the
     * session cookie.
     *
     * @see session_unset()
     * @see session_destroy()
     * @return UserSessionInterface
     */
    public function destroy();

    /**
     * Migrates the current session to a new session id while maintaining all session data
     *
     * Note : fork and destroy should not clear the session data in memory only delete the session data from
     * persistent storage.
     *
     * @param Boolean $destroy  If TRUE, destroy session when forking.
     * @param integer $lifetime Sets the cookie lifetime for the session cookie. A null value will leave the system
     *                          settings unchanged, 0 sets the cookie to expire with browser session. Time is in seconds,
     *                          and is not a Unix timestamp.
     * @see  session_regenerate_id()
     * @return UserSessionInterface
     * @throws \RuntimeException If an error occurs while regenerating this storage
     */
    public function fork($destroy = false, $lifetime = null);

    /**
     * Get a session attribute
     *
     * @param   string  $identifier Attribute identifier, eg .foo.bar
     * @param   mixed   $default    Default value when the attribute doesn't exist
     * @return  mixed   The value
     */
    public function get($identifier, $default = null);

    /**
     * Set a session attribute
     *
     * @param   mixed   $identifier Attribute identifier, eg foo.bar
     * @param   mixed   $value      Attribute value
     * @return User
     */
    public function set($identifier, $value);

    /**
     * Check if a session attribute exists
     *
     * @param   string  $identifier Attribute identifier, eg foo.bar
     * @return  boolean
     */
    public function has($identifier);

    /**
     * Removes an session attribute
     *
     * @param string $identifier Attribute identifier, eg foo.bar
     * @return UserSession
     */
    public function remove($identifier);
}