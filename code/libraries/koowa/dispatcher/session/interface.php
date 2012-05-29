<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @subpackage  Session
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Session Class
 *
 * Provides access to session-state values as well as session-level settings and lifetime management methods.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Session
 */
interface KDispatcherSessionInterface extends IteratorAggregate, Countable
{
    /**
     * Set the session life time
     *
     * This specifies the number of seconds after which data will be seen as 'garbage' and potentially cleaned up.
     * Garbage collection may occur during session start.
     *
     * @param integer $lifetime The session lifetime in seconds
     * @return KSession
     */
    public function setLifetime($lifetime);

    /**
     * Get the session life time
     *
     * @return integer The session life time in seconds
     */
    public function getLifetime();

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
     * @throws LogicException	When changing the id of an active session
     * @return \KDispatcherSessionInterface
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
     * @throws LogicException	When changing the name of an active session
     * @return \KDispatcherSessionInterface
     */
    public function setName($name);

    /**
     * Get current state of session
     *
     * @return string The session state
     */
    public function getState();

    /**
     * Method to set a session handler object
     *
     * @param mixed $hanlder An object that implements KObjectServiceable, KServiceIdentifier object
     * 					     or valid identifier string
     * @param array $config An optional associative array of configuration settings
     * @throws KDispatcherSessionException	If the identifier is not a session handler identifier
     * @return \KDispatcherSessionInterface
     */
    public function setHandler($handler, $config = array());

    /**
     * Get the session handler object
     *
     * @return	KDispatcherSessionHandlerInterface
     */
    public function getHandler();

    /**
     * Get a session token, if a token isn't set yet one will be generated.
     *
     * Tokens are used to secure forms from spamming attacks. Once a token has been generated a call to verifyToken will
     * check if the token matches, if not the session will be expired.
     *
     * @param   boolean $forceNew If true, force a new token to be created
     * @return  string  The session token
     */
    public function getToken($forceNew = false);

    /**
    /**
     * Method to determine if a token exists in the session. If not the session will be destroyed
     *
     * @param  string  $token         Token to be verified
     * @param  boolean $forceDestroy  If true, force destruction of the session storage if the token cannot be found
     * @param  boolean True on success, false on failure.
     */
    public function hasToken($token, $forceDestroy = true);

    /**
     * Load all the session data into memory
     *
     * @see  session_start()
     * @return \KDispatcherSessionInterface
     * @throws \KDispatcherSessionException If something goes wrong starting the session.
     */
    public function load();

    /**
     * Force the session to be saved and closed.
     *
     * Session data is usually stored after your script terminated without the need to call KDispatcherSession::save(),
     * but as session data is locked to prevent concurrent writes only one script may operate on a session at any time.
     *
     * When using framesets together with sessions you will experience the frames loading one by one due to this locking.
     * You can reduce the time needed to load all the frames by ending the session as soon as all changes to session
     * variables are done.
     *
     * @see  session_write_close()
     * @return \KDispatcherSessionInterface
     */
    public function save();

    /**
     * Clear all session data in memory.
     *
     * @see session_unset()
     * @return \KDispatcherSessionInterface
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
     * @return \KDispatcherSessionInterface
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
     * @return \KDispatcherSessionInterface
     * @throws \KDispatcherSessionException If an error occurs while regenerating this storage
     */
    public function fork($destroy = false, $lifetime = null);
}