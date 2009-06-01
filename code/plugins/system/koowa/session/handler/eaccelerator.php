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
 * eAccelerator Session Handler
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Session
 * @subpackage  Handler
*/
class KSessionHandlerEaccelerator extends KSessionHandlerAbstract implements KSessionHandlerInterface
{
	/**
	 * Open the session store
	 *
	 * @param string $save_path     The path to the session object.
	 * @param string $session_name  The name of the session.
	 * @return boolean  True on success, false otherwise.
	 */
	public function open($save_path, $session_name)
	{
		return true;
	}

	/**
	 * Close the session store
	 *
	 * @return boolean  True on success, false otherwise.
	 */
	public function close()
	{
		return true;
	}

 	/**
 	 * Read the data for a particular session identifier from the session store
 	 *
 	 * @param string $id  The session identifier.
 	 * @return string  The session data.
 	 */
	public function read($id)
	{
		$sess_id = 'sess_'.$id;
		return (string) eaccelerator_get($sess_id);
	}

	/**
	 * Write session data to the session store
	 *
	 * @param string $id            The session identifier.
	 * @param string $session_data  The session data.
	 * @return boolean  True on success, false otherwise.
	 */
	public function write($id, $session_data)
	{
		$sess_id = 'sess_'.$id;
		return eaccelerator_put($sess_id, $session_data, ini_get("session.gc_maxlifetime"));
	}

	/**
	  * Destroy the data for a particular session identifier in the session store
	  *
	  * @param string $id  The session identifier.
	  * @return boolean  True on success, false otherwise.
	  */
	function destroy($id)
	{
		$sess_id = 'sess_'.$id;
		return eaccelerator_rm($sess_id);
	}

	/**
	 * Garbage collect stale sessions from the session store
	 *
	 * @param integer $maxlifetime  The maximum age of a session.
	 * @return boolean  True on success, false otherwise.
	 */
	public function gc($maxlifetime)
	{
		eaccelerator_gc();
		return true;
	}

	/**
	 * Test to see if the SessionHandler is available.
	 *
	 * @return boolean  True on success, false otherwise.
	 */
	public static function test() {
		return (extension_loaded('eaccelerator') && function_exists('eaccelerator_get'));
	}
}
