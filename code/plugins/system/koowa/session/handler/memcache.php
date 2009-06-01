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
 * Memcache Session Handler
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Session
 * @subpackage  Handler
 */
class KSessionHandlerMemcache extends KSessionHandlerAbstract implements KSessionHandlerInterface
{
	/**
	 * Resource for the current memcached connection.
	 *
	 * @var resource
	 */
	protected $_connection;

	/**
	 * Use compression?
	 *
	 * @var int
	 */
	protected $_compress = 0;

	/**
	 * Use persistent connections
	 *
	 * @var boolean
	 */
	protected $_persistent = false;
	
	/**
	 * Servers
	 *
	 * @var boolean
	 */
	protected $_servers = array();

	/**
	* Constructor
	*
	* @param 	array 	Optional parameters
	*/
	public function __construct( array $options = array() )
	{
		parent::__construct($options);

		if(isset($options['compression'])) {
			$this->_compress = $options['compression'];
		}
		
		if(isset($options['persistent'])) {
			$this->_compress = $options['persistent'];
		}
		
		if(isset($options['servers'])) {
			$this->_compress = $options['servers'];
		}
	}

	/**
	 * Open the session handler
	 *
	 * @param 	string 	The path to the session object.
	 * @param 	string 	The name of the session.
	 * @return boolean  True on success, false otherwise.
	 */
	public function open($save_path, $session_name)
	{
		$this->_connection = new Memcache();
		for ($i=0, $n=count($this->_servers); $i < $n; $i++)
		{
			$server = $this->_servers[$i];
			$this->_connection->addServer($server['host'], $server['port'], $this->_persistent);
		}
		
		return true;
	}

	/**
	 * Close the session handler
	 *
	 * @return boolean  True on success, false otherwise.
	 */
	public function close()
	{
		return $this->_connection->close();
	}

 	/**
 	 * Read the data for a particular session identifier from the session store
 	 *
 	 * @param 	string 	The session identifier.
 	 * @return string  The session data.
 	 */
	public function read($id)
	{
		$sess_id = 'sess_'.$id;
		$this->_setExpire($sess_id);
		return $this->_connection->get($sess_id);
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
		if ($this->_connection->get($sess_id.'_expire')) {
			$this->_connection->replace($sess_id.'_expire', time(), 0);
		} else {
			$this->_connection->set($sess_id.'_expire', time(), 0);
		}
		if ($this->_connection->get($sess_id)) {
			$this->_connection->replace($sess_id, $session_data, $this->_compress);
		} else {
			$this->_connection->set($sess_id, $session_data, $this->_compress);
		}
		
		return;
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
		$this->_connection->delete($sess_id.'_expire');
		return $this->_connection->delete($sess_id);
	}

	/**
	 * Garbage collect stale sessions from the session store
	 *
	 *	-- Not Applicable in memcache --
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
	public static function test()
	{
		return (extension_loaded('memcache') && class_exists('Memcache'));
	}

	/**
	 * Set expire time on each call since memcache sets it on cache creation.
	 *
	 * @param 	string 	Cache key to expire.
	 * @param 	integer Lifetime of the data in seconds.
	 */
	protected function _setExpire($key)
	{
		$lifetime	= ini_get("session.gc_maxlifetime");
		$expire		= $this->_connection->get($key.'_expire');

		// set prune period
		if ($expire + $lifetime < time()) {
			$this->_connection->delete($key);
			$this->_connection->delete($key.'_expire');
		} else {
			$this->_connection->replace($key.'_expire', time());
		}
	}
}
