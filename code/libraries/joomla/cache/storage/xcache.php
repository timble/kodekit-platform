<?php
/**
 * @version		$id:$
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
 * XCache cache storage handler
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.5
 */
class JCacheStorageXCache extends JCacheStorage
{
	/**
	 * Get cached data by id and group
	 *
	 * @access	public
	 * @param	string	$id			The cache data id
	 * @param	string	$group		The cache data group
	 * @param	boolean	$checkTime	True to verify cache time expiration threshold
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	1.5
	 */
	function get($id, $group, $checkTime)
	{
		$cache_id = $this->_getCacheId($id, $group);

		//check if id exists
		if( !xcache_isset( $cache_id ) ){
			return false;
		}

		return xcache_get($cache_id);
	}
	
	/**
	 * Get all cached data
	 *
	 * requires the php.ini setting xcache.admin.enable_auth = Off
	 *
	 * @return	array data
	 * @since	Nooku Server 0.7
	 */
	public function keys()
	{
		$result = array();
	    
	    if(!ini_get('xcache.admin.enable_auth')) 
		{ 
		    $allinfo 	= xcache_list(XC_TYPE_VAR, 0);
            $keys 		= $allinfo['cache_list'];
            $secret 	= $this->_hash;
                  
            foreach ($keys as $key) 
            {
                $parts = explode('-',$key['name']);
                
			    if ($parts !== false && $parts[0] == $secret &&  $parts[1] == 'cache') 
			    {  
			        $data = array();
                    $data['name']  = $key['name'];
                    $data['hash']  = $parts[4];
                    $data['group'] = $parts[3];
                    $data['site']  = $parts[2];
                    $data['size']  = $key['size'];
                    $data['hits']  = $key['hits'];
                    $data['created_on']  = $key['ctime'];
                    $data['accessed_on'] = $key['atime'];
                    
				    $result[$data['hash']] = (object) $data;
			    }
		    }
		}
	
		return $result;
	}

	/**
	 * Store the data by id and group
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
		return xcache_set($cache_id, $data, $this->_lifetime);
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
		
		if( !xcache_isset( $cache_id ) ){
			return true;
		}

		return xcache_unset($cache_id);
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
		if( !xcache_isset( $key ) ){
			return true;
		}

		return xcache_unset($key);
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
		if(!ini_get('xcache.admin.enable_auth')) 
		{ 
	        $allinfo = xcache_list(XC_TYPE_VAR, 0);
		    $keys = $allinfo['cache_list'];
		    
		    $secret = $this->_hash;
		    foreach ($keys as $key) 
		    { 
		        if (strpos($key['name'], $secret.'-cache-'.$this->_site.'-'.$group.'-') === 0 xor $mode != 'group') {			      
		            xcache_unset($key['name']);
		        }
		    }
		    
		    return true;
		}
	
		return false;
	}
	
	/**
	 * Garbage collect expired cache data
	 *
	 * @return boolean  True on success, false otherwise.
	 * @since	Nooku Server 0.7
	 */
	public function gc()
	{
		// dummy, xcache has builtin garbage collector, turn it on in php.ini by changing default xcache.gc_interval setting from 0 to 3600 (=1 hour)
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
		return (extension_loaded('xcache'));
	}
}