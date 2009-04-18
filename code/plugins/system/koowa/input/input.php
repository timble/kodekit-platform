<?php
/**
 * @version    	$Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
 * @category	Koowa
 * @package    	Koowa_Input
 * @copyright  	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
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
	 * When an array of hashes is supplied, the hash will be prioritized in the 
	 * same order. Eg. array('post', 'get'). Use this (if you really have to) as
	 * a safer equivalent for $_REQUEST
	 * 
	 * When no sanitizers are supplied, the same filters as the validators will 
	 * be used.
	 * 
	 * @param	string			Variable name eg 'foo[bar]'
	 * @param 	string|array  	Hash(es) [COOKIE|ENV|FILES|GET|POST|SERVER]
	 * @param 	mixed			Validator(s), can be a KFilterInterface object, or array of objects 
	 * @param 	mixed			Sanitizer(s), can be a KFilterInterface object, or array of objects
	 * @param 	mixed			Default value when the variable doesn't exist
	 * @throws	KInputException	When the variable doesn't validate
	 * @return 	mixed			(Sanitized) variable 
	 */
	public static function get($var, $hashes, $validators, $sanitizers = array(), $default = null)
	{
		settype($hashes, 'array');

		// Is the hash in our list?
		foreach($hashes as $k => $hash) 
		{
			$hashes[$k] = strtoupper($hash);
			if(!in_array($hashes[$k], self::$_hashes)) {
				throw new KInputException('Unknown hash: '.$hash);
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
	 * @param 	mixed	Variable name eg 'foo[bar]'
	 * @param 	mixed	Variable value
	 * @param 	string|array  	Hash(es) [COOKIE|ENV|FILES|GET|POST|SERVER]
	 */
	public static function set($var, $value, $hash) 
	{
		// Is the hash in our list?
		$hash = strtoupper($hash);
		if(!in_array($hash, self::$_hashes)) {
			throw new KInputException('Unknown hash: '.$hash);
		}
		
		// add to hash in the superglobal
		$parts = self::_split($var);
		self::_setNested($GLOBALS['_'.$hash], $parts, $value);
		
		
		// Add to _REQUEST hash if original hash is get, post, or cookies
		// Even though we are not using $_REQUEST, other extensions do 
		if(in_array($hash, array('GET', 'POST', 'COOKIE'))) {
			self::_setNested($GLOBALS['_REQUEST'], $parts, $value);
		}
	}
	
	/**
	 * Split foo[bar][bar] into an array
	 *
	 * @param 	string	Variable name
	 * @return 	array
	 */
	protected function _split($varname)
	{
		$parts = array();
		$pattern = "/\[?([a-zA-Z0-9_-]+)\]?/";
		if(!preg_match_all($pattern, $varname, $parts)){
			throw KInputException("Couldn't split string to array: ".$varname);
		}
		return $parts[1];
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
	 * Get the request method
	 *
	 * @return 	string
	 */
	public static function getMethod()
	{
		return strtoupper($GLOBALS['_SERVER']['REQUEST_METHOD']);
	}
}