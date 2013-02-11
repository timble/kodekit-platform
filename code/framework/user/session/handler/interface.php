<?php
/**
 * @package		Koowa_User
 * @subpackage  Session
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * User Session Handler Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_User
 * @subpackage  Session
 * @see         http://www.php.net/manual/en/function.session-set-save-handler.php
 */
interface KUserSessionHandlerInterface
{
    /**
     * Initialize the session handler backend
     *
     * @param   string  $save_path     The path to the session object
     * @param   string  $session_name  The name of the session
     * @return  boolean  True on success, false otherwise
     */
    public function open($save_path, $session_name);

    /**
     * Close the session handler backend
     *
     * @return  boolean  True on success, false otherwise
     */
    public function close();

    /**
     * Read session data for a particular session identifier from the session handler backend
     *
     * @param   string  $session_id  The session identifier
     * @return  string  The session data
     */
    public function read($session_id);

    /**
     * Write session data to the session handler backend
     *
     * @param   string  $session_id    The session identifier
     * @param   string  $session_data  The session data
     * @return  boolean  True on success, false otherwise
     */
    public function write($session_id, $session_data);

    /**
     * Destroy the data for a particular session identifier in the session handler backend
     *
     * @param   string  $session_id  The session identifier
     * @return  boolean  True on success, false otherwise
     */
    public function destroy($session_id);

    /**
     * Garbage collect stale sessions from the SessionHandler backend.
     *
     * @param   integer  $maxlifetime  The maximum age of a session
     * @return  boolean  True on success, false otherwise
     */
    public function gc($maxlifetime);

    /**
     * Is this handler registered with the PHP's session handler
     *
     * @return boolean  True on success, false otherwise
     */
    public function isRegistered();

    /**
     * Test to see if the session handler is available
     *
     * @return boolean  True on success, false otherwise
     */
    public function isSupported();
}