<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Session
 * @subpackage  Handler
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * XCache Session Handler
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Session
 * @subpackage  Handler
 */
class KSessionHandlerXcache extends KSessionHandlerAbstract implements KSessionHandlerInterface
{
	/**
	 * Open the session handler
	 *
	 * @param 	string 	The path to the session object.
	 * @param 	string 	The name of the session.
	 * @return boolean  True on success, false otherwise.
	 */
	public function open($save_path, $session_name)
	{
		return true;
	}

	/**
	 * Close the session handler
	 *
	 * @return boolean  True on success, false otherwise.
	 */
	public function close()
	{
		return true;
	}

 	/**
 	 * Read the data for a particular session identifier
 	 *
 	 * @param 	string 	The session identifier.
 	 * @return 	string  The session data.
 	 */
	public function read($id)
	{
		$sess_id = 'sess_'.$id;

		//check if id exists
		if( !xcache_isset( $sess_id ) ){
			return;
		}

		return (string)xcache_get($sess_id);
	}

	/**
	 * Write session data to the session handler
	 *
	 * @param 	string 	The session identifier.
	 * @param 	string 	The session data.
	 * @return boolean  True on success, false otherwise.
	 */
	public function write($id, $session_data)
	{
		$sess_id = 'sess_'.$id;
		return xcache_set($sess_id, $session_data, ini_get("session.gc_maxlifetime")  );
	}

	/**
	  * Destroy the data for a particular session identifier
	  *
	  * @param 	string 	The session identifier.
	  * @return boolean  True on success, false otherwise.
	  */
	public function destroy($id)
	{
		$sess_id = 'sess_'.$id;

		if( !xcache_isset( $sess_id ) ){
			return true;
		}

		return xcache_unset($sess_id);
	}

	/**
	 * Garbage collect stale sessions from the session handler
	 *
	 * @param 	integer The maximum age of a session.
	 * @return boolean  True on success, false otherwise.
	 */
	public function gc($maxlifetime)
	{
		return true;
	}

	/**
	 * Test to see if the session handler is available.
	 *
	 * @return boolean  True on success, false otherwise.
	 */
	public static function test() {
		return (extension_loaded('xcache'));
	}
}
