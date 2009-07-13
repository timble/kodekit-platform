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
 * Database Session Handler
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Session
 * @subpackage  Handler
 */
class KSessionHandlerDatabase extends KSessionHandlerAbstract implements KSessionHandlerInterface
{
	protected $_table = null;
	
	/**
	 * Constructor
	 *
	 * @param array $options optional parameters
	 */
	public function __construct( array $options = array() )
	{	
		parent::__construct($options);
	}

	/**
	 * Open the session handler
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
 	 * @param string $id  The session identifier.
 	 * @return object  The session data.
 	 */
	public function read($id)
	{
		if(!$this->_table->getDatabase()->connected()) {
			return false;
		}

		$row = $this->_table->fetchRow($id);
		return $row->data;
	}

	/**
	 * Write session data
	 *
	 * @param string $id            The session identifier.
	 * @param string $session_data  The session data.
	 * @return boolean  True on success, false otherwise.
	 */
	public function write($id, $session_data)
	{
		if(!$this->_table->getDatabase()->connected()) {
			return false;
		}
		
		$row = $this->_table->fetcRow($id);
		$row->data = $session_data;
		$row->save();

		return true;
	}

	/**
	 * Destroy the data for a particular session identifier.
	 *
	 * @param string $id  The session identifier.
	 * @return boolean  True on success, false otherwise.
	 */
	public function destroy($id)
	{
		if(!$this->_table->getDatabase()->connected()) {
			return false;
		}

		$row = $this->_table->fetctRow($id);
		$row->delete();
		
		return true;
	}

	/**
	 * Garbage collect stale sessions
	 *
	 * @access public
	 * @param integer $maxlifetime  The maximum age of a session.
	 * @return boolean  True on success, false otherwise.
	 */
	public function gc($maxlifetime)
	{
		if(!$this->_table->getDatabase()->connected()) {
			return false;
		}
		
		$query = $this->_table->getDatabase()->getQuery()
			->where('time < \''. (int) time() - $maxLifetime .'\'');
		$this->_table->delete($query);
		
		return true;
	}
	
	/**
	 * Test to see if the session store is available.
	 *
	 * @return boolean  True on success, false otherwise.
	 */
	public static function test() 
	{
		return true;
	}
}
