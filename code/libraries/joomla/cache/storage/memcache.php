<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Memcache cache storage handler
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.5
 */
class JCacheStorageMemcache extends JCacheStorage
{
	/**
	 * Resource for the current memcached connection.
	 * @var resource
	 */
	var $_db;

	/**
	 * Use compression?
	 * @var int
	 */
	var $_compress = null;

	/**
	 * Use persistent connections
	 * @var boolean
	 */
	var $_persistent = false;

	/**
	 * Constructor
	 *
	 * @access protected
	 * @param array $options optional parameters
	 */
	function __construct( $options = array() )
	{
		if (!$this->test()) {
			return JError::raiseError(404, "The memcache extension is not available");
		}
		parent::__construct($options);

		$params =& JCacheStorageMemcache::getConfig();
		$this->_compress  = (isset($params['compression'])) ? $params['compression'] : 0;
		$this->_db        =& JCacheStorageMemcache::getConnection();
		
	    // memcahed has no list keys, we do our own accounting, initalise key index
		if (self::$_db->get($this->_hash.'-index') === false) 
		{
			$empty = array();
			self::$_db->set($this->_hash.'-index', $empty , $this->_compress, 0);
		}
	}

	/**
	 * return memcache connection object
	 *
	 * @static
	 * @access private
	 * @return object memcache connection object
	 */
	function &getConnection() 
	{
		static $db = null;
		if(is_null($db)) 
		{
			$params =& JCacheStorageMemcache::getConfig();
			$persistent	= (isset($params['persistent'])) ? $params['persistent'] : false;
			// This will be an array of loveliness
			$servers	= (isset($params['servers'])) ? $params['servers'] : array();

			// Create the memcache connection
			$db = new Memcache;
			foreach($servers AS $server) {
				$db->addServer($server['host'], $server['port'], $persistent);
			}
		}
		return $db;
	}

	/**
	 * Return memcache related configuration
	 *
	 * @static
	 * @access private
	 * @return array options
	 */
	function &getConfig() 
	{
		static $params = null;
		if(is_null($params)) 
		{
			$config =& JFactory::getConfig();
			$params = $config->getValue('config.memcache_settings');
			if (!is_array($params)) {
				$params = unserialize(stripslashes($params));
			}

			if (!$params) {
				$params = array();
			}
			$params['hash'] = $config->getValue('config.secret');
		}
		
		return $params;
	}

	/**
	 * Get cached data from memcache by id and group
	 *
	 * @access	public
	 * @param	string	$id			The cache data id
	 * @param	string	$group		The cache data group
	 * @param	boolean	$checkTime	True to verify cache time expiration threshold
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	1.5
	 */
	function get($id, $group, $checkTime = true)
	{
		$cache_id = $this->_getCacheId($id, $group);
		return $this->_db->get($cache_id);
	}
	
	/**	
	 * Get all cached data
	 *
	 * @return	array data
	 * @since	Nooku Server 0.7
	 */
	public function keys()
	{
		$keys   = self::$_db->get($this->_hash.'-index');
		$secret = $this->_hash;

		$result = array();

		if (!empty($keys))
		{
			foreach ($keys as $key) 
			{
				if (empty($key)) {
					continue;
				}
				
				$parts = explode('-',$key->name);

				if ($parts !== false && $parts[0]==$secret &&  $parts[1] == 'cache') 
				{
					$data = array();
				    $data['name']  = $key->name;
				    $data['hash']  = $parts[4];
				    $data['group'] = $parts[3];
				    $data['site']  = $parts[2];
				    $data['size']  = $key->size;
				    $data['hits']  = $key->hits;
			        $data['created_on']  = '';
			        $data['accessed_on'] = '';
					
					$result[$data['hash']] = (object) $data;
				}
			}
		}

		return $result;
	}

	/**
	 * Store the data to memcache by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	string	$data	The data to store in cache
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	function store($id, $group, $data)
	{
		$cache_id = $this->_getCacheId($id, $group);

		if (!$this->lockindex()) {
			return false;
		}

		$index = self::$_db->get($this->_hash.'-index');
		if ($index === false) {
			$index = array();
		}

		$tmparr = new stdClass;
		$tmparr->name = $cache_id;
		$tmparr->size = strlen($data);
		$index[] = $tmparr;
		
		self::$_db->replace($this->_hash.'-index', $index , 0, 0);
		$this->unlockindex();

		// prevent double writes, write only if it doesn't exist else replace
		if (!self::$_db->replace($cache_id, $data, $this->_compress, $this->_lifetime)) {
			self::$_db->set($cache_id, $data, $this->_compress, $this->_lifetime);
		}

		return true;
	}

	/**
	 * Remove a cached data entry by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	function remove($id, $group)
	{
		$cache_id = $this->_getCacheId($id, $group);

		if (!$this->lockindex()) {
			return false;
		}

		$index = self::$_db->get($this->_hash.'-index');
		if ($index === false) {
			$index = array();
		}

		foreach ($index as $key => $value) {
			if ($value->name == $cache_id) unset ($index[$key]);
			break;
		}
		self::$_db->replace($this->_hash.'-index', $index, 0, 0);
		$this->unlockindex();

		return self::$_db->delete($cache_id);
	}
	
	/**
	 * Delete a cached data entry by key
	 *
	 * @access	public
	 * @param	string	$key
	 * @return	boolean	True on success, false otherwise
	 * @since	Nooku Server 0.7
	 */
	function delete($key)
	{
		if (!$this->lockindex()) {
			return false;
		}

		$index = self::$_db->get($this->_hash.'-index');
		if ($index === false) {
			$index = array();
		}

		foreach ($index as $key => $value) 
		{
			if ($value->name == $key) unset ($index[$key]);
			break;
		}
		
		self::$_db->replace($this->_hash.'-index', $index, 0, 0);
		$this->unlockindex();

		return self::$_db->delete($key);
	}

	/**
	 * Clean cache for a group given a mode.
	 *
	 * group mode		: cleans all cache in the group
	 * notgroup mode	: cleans all cache not in the group
	 *
	 * @access	public
	 * @param	string	$group	The cache data group
	 * @param	string	$mode	The mode for cleaning cache [group|notgroup]
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	function clean($group, $mode)
	{
		if (!$this->lockindex()) {
			return false;
		}

		$index = self::$_db->get($this->_hash.'-index');
		if ($index === false) {
			$index = array();
		}

		$secret = $this->_hash;
		foreach ($index as $key=>$value) 
		{
			if (strpos($value->name,  $secret.'-cache-'.$this->_site.'-'.$group.'-')===0 xor $mode != 'group') 
			{
				self::$_db->delete($value->name,0);
				unset ($index[$key]);
			}
		}
		self::$_db->replace($this->_hash.'-index', $index , 0, 0);
		$this->unlockindex();
		return true;
	}

	/**
	 * Garbage collect expired cache data
	 *
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function gc()
	{
		return true;
	}

	/**
	 * Test to see if the cache storage is available.
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function test()
	{
	    if ((extension_loaded('memcache') && class_exists('Memcache')) != true ) {
			return false;
		}

		$config = JFactory::getConfig();
		$host = $config->get('memcache_server_host', 'localhost');
		$port = $config->get('memcache_server_port', 11211);

		$memcache = new Memcache;
		$memcachetest = @$memcache->connect($host, $port);

		 if (!$memcachetest) {
		 	return false;
		 } else {
		 	return true;
		 }
	}
	
	/**
	 * Lock cache index
	 *
	 * @return boolean  True on success, false otherwise.
	 * @since	Nooku Server 0.7s
	 */
	private function lockindex()
	{
		$looptime 	= 300;
		$data_lock 	= self::$_db->add($this->_hash.'-index_lock', 1, false, 30);

		if ($data_lock === FALSE) {

			$lock_counter = 0;

			// loop until you find that the lock has been released.  that implies that data get from other thread has finished
			while ( $data_lock === FALSE ) {

				if ( $lock_counter > $looptime ) {
					return false;
					break;
				}

				usleep(100);
				$data_lock = self::$_db->add($this->_hash.'-index_lock', 1, false, 30);
				$lock_counter++;
			}
		}

		return true;
	}

	/**
	 * Unlock cache index
	 *
	 * @return	boolean	True on success, false otherwise.
	 * @since	Nooku Server 0.7
	 */
	private function unlockindex()
	{
		return self::$_db->delete($this->_hash.'-index_lock');
	}
}
