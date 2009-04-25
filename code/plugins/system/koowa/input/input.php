<?php
/**
 * @version    	$Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
 * @category	Koowa
 * @package    	Koowa_Input
 * @copyright  	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license    	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link 		http://www.koowa.org
 */

/**
 * Request class
 *
 * Allows to get input from GET, POST, COOKIE, ENV, SERVER
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Input
 * @uses 		KInflector
 * @uses		KFilter
 * @static
 */
class KInput
{
	/**
	 * List of accepted hashes
	 * 
	 * @var	array
	 */
	protected static $_hashes = array('COOKIE', 'ENV', 'FILES', 'GET', 'POST', 'SERVER');
	
	
	/**
	 * Get a validated and optionally sanitized variable from the request. 
	 * 
	 * Multiple hashes (separated by dots) are handled as FIFO. Use this (if you really have to) as
	 * a safer equivalent for $_REQUEST. Available hashes: [COOKIE|ENV|FILES|GET|POST|SERVER]
	 * 
	 * When no sanitizers are supplied, the same filters as the validators will 
	 * be used.
	 * 
	 * 
	 * 
	 * @param	string			Variable name, prefixed by hash name eg post::foo.bar or post.get::foo
	 * @param 	mixed			Validator(s), can be a KFilterInterface object, or array of objects 
	 * @param 	mixed			Sanitizer(s), can be a KFilterInterface object, or array of objects
	 * @param 	mixed			Default value when the variable doesn't exist
	 * @throws	KInputException	When the variable doesn't validate
	 * @return 	mixed			(Sanitized) variable 
	 */
	public static function get($var, $validators, $sanitizers = array(), $default = null)
	{
		list($hashes, $parts) = self::_split($var);

		// Is the hash in our list?
	 	foreach($hashes as $k => $hash) 
		{
			if(!in_array($hash, self::$_hashes)) {
				throw new KInputException("Unknown hash '$hash' in '$var'");
			}		
		}
		
		
		// find $var in the hashes
		$result	= null;
		foreach($hashes as $hash) 
		{
			if($result = self::_getNested($GLOBALS['_'.$hash], self::_split($var))) {
				break;
			}			
		}		

		// return the default value if $var wasn't set in any of the hashes
		if(is_null($result)) {
			return $default; 	
		}

		// trim values
		if(is_scalar($result)) {
			$result = trim($result);
		} else {
			array_walk_recursive($result, 'trim');
		}
		
	
		// if $validators or $sanitizers is an object, turn it into an array of objects
		// don't use settype because it will convert objects to arrays
		$validators = is_array($validators) ? $validators : (empty($validators) ? array() : array($validators));
		
		// if no sanitizers are given, use the validators
		$sanitizers = empty($sanitizers) ? $validators : (is_array($sanitizers) ? $sanitizers : array($sanitizers));
		
		// validate the variable
		foreach($validators as $filter)
		{
			//Create the filter if needed
			if(is_string($filter)) {
				$filter = KFactory::tmp('lib.koowa.filter.'.$filter);
			}
		
			if(!($filter instanceof KFilterInterface)) {
				throw new KInputException('Invalid filter passed: '.get_class($filter));
			}
	
			if(!$filter->validate($result)) 
			{
				$filtername = KInflector::getPart(get_class($filter), -1);
				throw new KInputException('Input is not a valid '.$filtername);
			}			 
		}
		
		// sanitize the variable
		foreach($sanitizers as $filter)
		{
			//Create the filter if needed
			if(is_string($filter)) {
				$filter = KFactory::tmp('lib.koowa.filter.'.$filter);
			}
		
			if(!($filter instanceof KFilterInterface)) {
				throw new KInputException('Invalid filter passed: '.get_class($filter));
			}
			
			$result = $filter->sanitize($result);		 
		}
		
		return $result;
	}
	
	/**
	 * Set a variable in the request
	 *
	 * @param 	mixed	Variable name eg 'post::foo.bar'
	 * @param 	mixed	Variable value
	 */
	public static function set($var, $value) 
	{		
		list($hashes, $parts) = self::_split($var);
		
	 	foreach($hashes as $k => $hash) 
		{
			// Is the hash in our list?
			if(!in_array($hash, self::$_hashes)) {
				throw new KInputException("Unknown hash '$hash' in '$var'");
			}	
			
			// add to hash in the superglobal
			self::_setNested($GLOBALS['_'.$hash], $parts, $value);	
			
			// Add to _REQUEST hash if original hash is get, post, or cookies
			// Even though we are not using $_REQUEST, other extensions do 
			if(in_array($hash, array('GET', 'POST', 'COOKIE'))) {
				self::_setNested($GLOBALS['_REQUEST'], $parts, $value);
			}
		}					
		
	}
	
	/**
	 * Split hash.hash::foo.bar into arrays
	 *
	 * @param 	string	Variable name
	 * @return 	array	0=>hash, 1=>parts
	 */
	protected function _split($varname)
	{
		if(strpos($varname, '::') === false) {
			 throw new KInputException("KInput identifier needs to be of the format 'hash::foo.bar', you provided: ".$varname);
		}
		
		list($hashes, $name) = explode('::', $varname, 2);
		$name 	= explode('.', $name);
		$hashes = explode('.', $hashes);
		array_walk($hashes, 'strtolower');		
		
		return array($hashes, $name);
	}
	
	/**
	 * Get a value from nested array
	 *
	 * @param 	array	The array to search in
	 * @param	array	A list of keys (foo, bar)
	 * @return 	mixed	The value of array[foo][bar], or null
	 */
	protected function _getNested($array, $keys)
	{
		$tmp = $array;
		foreach($keys as $key)
		{
			if(array_key_exists($key, $tmp)) {
				$tmp = $tmp[$key];
			} else {
				return null;
			}
		}
		return $tmp;
	}
	
	
	protected function _setNested(&$array, $keys, $value)
	{
		foreach(array_reverse($keys, true) as $key) {
			$value = array($key => $value);
		}
		
		$array = array_merge($array, $value);
	}
	
	/**
	 * Check for the existence of a value in a nested array
	 *
	 * @param 	array	The array to search in
	 * @param	array	A list of keys (foo, bar)
	 * @return 	bool
	 */
	protected function _hasNested($array, $keys)
	{
		foreach($keys as $key)
		{
			if(array_key_exists($key, $array)) {
				return true;;
			}
		}
		return false;
	}
	
	/**
	 * Check if a variable exists in the hash(es)
	 *
	 * @param	string  Variable name hash::foo.bar
	 * @return 	boolean
	 */
	public static function has($var)
	{
		list($hashes, $parts) = self::_split($var);
		
		// Is the hash in our list?
		foreach($hashes as $k => $hash) 
		{
			if(!in_array($hashes[$k], self::$_hashes)) {
				throw new KInputException('Unknown hash: '.$hash);
			}		
		}
		
		// find $var in the hashes
		$result = null;
		foreach($hashes as $hash) 
		{
			foreach($parts as $part)
			{
				if(array_key_exists($part, $GLOBALS['_'.$hash])) {
					return true;;
				}
			}
		}
		
		return false; 
	}
	
	/**
	 * Get the request method
	 *
	 * @return 	string
	 */
	public static function getMethod()
	{
		return strtoupper($GLOBALS['_SERVER']['REQUEST_METHOD']);
	}
}