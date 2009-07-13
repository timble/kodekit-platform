<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Session
 * @subpackage  Handler
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.koowa.org
 */

/**
 * Ssession Handler Interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Session
 * @subpackage  Handler
 * 
 * @see http://www.php.net/manual/en/function.session-set-save-handler.php
 */
interface KSessionHandlerInterface
{
	/**
	 * Open the session handler.
	 *
	 * @param 	string 	The path to the session object.
	 * @param 	string 	The name of the session.
	 * @return boolean  True on success, false otherwise.
	 * @throws KSessionHandlerException
	 */
	public function open($save_path, $session_name);

	/**
	 * Close the session handler.
	 *
	 * @return boolean  True on success, false otherwise.
	 * @throws KSessionHandlerException
	 */
	public function close();

 	/**
 	 * Read the data for a particular session identifier
 	 *
 	 * @param 	string	The session identifier.
 	 * @return string  The session data.
 	 * @throws KSessionHandlerException
 	 */
	public function read($id);

	/**
	 * Write session data
	 *
	 * @param 	string 	The session identifier.
	 * @param 	string	The session data.
	 * @return boolean  True on success, false otherwise.
	 * @throws KSessionHandlerException
	 */
	public function write($id, $session_data);

	/**
	 * Destroy the data for a particular session identifier
	 *
	 * @param 	string	The session identifier.
	 * @return boolean  True on success, false otherwise.
	 * @throws KSessionHandlerException
	 */
	public function destroy($id);

	/**
	 * Garbage collect stale sessions
	 *
	 * @param 	integer The maximum age of a session.
	 * @return boolean  True on success, false otherwise.
	 * @throws KSessionHandlerException
	 */
	public function gc($maxlifetime);

	/**
	 * Test to see if the session handler is available
	 *
	 * @return boolean  True on success, false otherwise.
	 * @throws KSessionHandlerException
	 */
	public static function test();
}
