<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * WINCACHE cache storage handler
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		Nooku Server 0.7
 */
class JCacheStorageWincache extends JCacheStorage
{
	/**
	 * Get cached data from WINCACHE by id and group
	 *
	 * @param	string	$id		The cache data id
	 * @param	string	$group		The cache data group
	 * @param	boolean	$checkTime	True to verify cache time expiration threshold
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	Nooku Server 0.7
	 */
	public function get($id, $group, $checkTime = true)
	{
		$cache_id = $this->_getCacheId($id, $group);
		$cache_content = wincache_ucache_get($cache_id);
		return $cache_content;
	}

	/**
	 * Get all cached data
	 *
	 * @return	array data
	 * @since	Nooku Server 0.7
	 */
	public function keys()
	{
		$allinfo 	= wincache_ucache_info();
		$keys 		= $allinfo['cache_entries'];
		$secret 	= $this->_hash;
		$result     = array();

		foreach ($keys as $key) 
		{
			$name  = $key['key_name'];
			$parts = explode('-',$name);
			
			if ($parts !== false && $parts[0] == $secret && $parts[1] == 'cache') 
			{
			    $data = array();
				$data['name']  = $key['key_name'];
				$data['hash']  = $parts[4];
				$data['group'] = $parts[3];
				$data['site']  = $parts[2];
				$data['size']  = isset($key['value_size']) ? $key['value_size'] : $key['size'] = '';
				$data['hits']  = $key['hitcount'];
			    $data['created_on']  = $key['age_seconds'];
			    $data['accessed_on'] = $key['use_check'];
				
				$result[$data['hash']] = (object) $data;
		    }
		}

		return $result;
	}

	/**
	 * Store the data to WINCACHE by id and group
	 *
	 * @param	string	$id	The cache data id
	 * @param	string	$group	The cache data group
	 * @param	string	$data	The data to store in cache
	 * @return	boolean	True on success, false otherwise
	 * @since	Nooku Server 0.7
	 */
	public function store($id, $group, $data)
	{
		$cache_id = $this->_getCacheId($id, $group);
		return wincache_ucache_set($cache_id, $data, $this->_lifetime);
	}

	/**
	 * Remove a cached data entry by id and group
	 *
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	boolean	True on success, false otherwise
	 * @since	Nooku Server 0.7
	 */
	public function remove($id, $group)
	{
		$cache_id = $this->_getCacheId($id, $group);
		return wincache_ucache_delete($cache_id);
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
		return wincache_ucache_delete($key);
	}

	/**
	 * Clean cache for a group given a mode.
	 *
	 * group mode		: cleans all cache in the group
	 * notgroup mode	: cleans all cache not in the group
	 *
	 * @param	string	$group	The cache data group
	 * @param	string	$mode	The mode for cleaning cache [group|notgroup]
	 * @return	boolean	True on success, false otherwise
	 * @since	Nooku Server 0.7
	 */
	public function clean($group, $mode = null)
	{
		$allinfo 	= wincache_ucache_info();
		$keys 		= $allinfo['cache_entries'];
		$secret 	= $this->_hash;

		foreach ($keys as $key) 
		{
			if (strpos($key['key_name'],  $secret.'-cache-'.$this->_site.'-'.$group.'-') === 0 xor $mode != 'group') {
				wincache_ucache_delete ($key['key_name']);
			}
		}
		return true;
	}

	/**
	 * Force garbage collect expired cache data as items are removed only on get/add/delete/info etc
	 *
	 * @return	boolean	True on success, false otherwise.
	 * @since	Nooku Server 0.7
	 */
	public function gc()
	{
		$lifetime	= $this->_lifetime;
		$allinfo 	= wincache_ucache_info();
		$keys 		= $allinfo['cache_entries'];
		$secret 	= $this->_hash;

		foreach ($keys as $key) 
		{
			if (strpos($key['key_name'], $secret.'-cache-')) {
				wincache_ucache_get($key['key_name']);
			}
		}
	}

	/**
	 * Test to see if the cache storage is available.
	 *
	 * @return boolean  True on success, false otherwise.
	 * @since	Nooku Server 0.7
	 */
	public function test()
	{
		$test = extension_loaded('wincache') && function_exists('wincache_ucache_get') && !strcmp(ini_get('wincache.ucenabled'), '1');
		return $test;
	}
}