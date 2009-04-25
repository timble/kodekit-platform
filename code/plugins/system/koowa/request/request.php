<?php
/**
 * @version    	$Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
 * @category	Koowa
 * @package    	Koowa_Request
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
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Request
 * @uses		KFilter
 * @uses		KInflector
 * @uses		KFactory
 * @static
 */
class KRequest
{
	/**
	 * List of accepted hashes
	 * 
	 * @var	array
	 */
	protected static $_hashes = array('COOKIE', 'ENV', 'FILES', 'GET', 'POST', 'SERVER'
		, 'REQUEST' // @deprecated, will be removed soon
	);
	
	/**
	 * Get a validated and optionally sanitized variable from the request. When no sanitizers are supplied, 
	 * the same filters as the validators will be used. 
	 * 
	 * @param	string				Variable identifier, prefixed by hash name eg post.foo.bar
	 * @param 	mixed				Validator(s), can be a KFilterInterface object, or array of objects 
	 * @param 	mixed				Sanitizer(s), can be a KFilterInterface object, or array of objects
	 * @param 	mixed				Default value when the variable doesn't exist
	 * @throws	KRequestException	When the variable doesn't validate
	 * @return 	mixed				(Sanitized) variable 
	 */
	public static function get($identifier, $validators, $sanitizers = array(), $default = null)
	{
		list($hash, $keys) = self::_parseIdentifier($identifier);
			
		$result = $GLOBALS['_'.$hash];
		foreach($keys as $key)
		{
			if(array_key_exists($key, $result)) {
				$result = $result[$key];
			} else {
				$result = null;
				break;
			}
		}
		
		// If the value is null return the default
		if(is_null($result)) {
			return $default; 	
		}
		
		// Trim the result
		if(!is_scalar($result)) {
			array_walk_recursive($result, 'trim');
		} else {
			$result = trim($result);
		}
		
		/*
		 * Validate the result
		 */
			
		// If $validators is an object, turn it into an array of objects don't use settype because it will convert objects to arrays
		$validators = is_array($validators) ? $validators : (empty($validators) ? array() : array($validators));
			
		foreach($validators as $filter)
		{
			//Create the filter if needed
			if(is_string($filter)) {
				$filter = KFactory::tmp('lib.koowa.filter.'.$filter);
			}
		
			if(!($filter instanceof KFilterInterface)) {
				throw new KRequestException('Invalid filter passed: '.get_class($filter));
			}
	
			if(!$filter->validate($result)) 
			{
				$filtername = KInflector::getPart(get_class($filter), -1);
				throw new KRequestException('Input is not a valid '.$filtername);
			}			 
		}
		
		/*
		 * Sanitize the result
		 */
		
		// If no sanitizers are specified, use the validators
		// If $sanitizers is an object, turn it into an array of objects don't use settype because it will convert objects to arrays
		$sanitizers = empty($sanitizers) ? $validators : (is_array($sanitizers) ? $sanitizers : array($sanitizers));
		
		foreach($sanitizers as $filter)
		{
			//Create the filter if needed
			if(is_string($filter)) {
				$filter = KFactory::tmp('lib.koowa.filter.'.$filter);
			}
		
			if(!($filter instanceof KFilterInterface)) {
				throw new KRequestException('Invalid filter passed: '.get_class($filter));
			}
			
			$result = $filter->sanitize($result);		 
		}
		
		return $result;
	}
	
	/**
	 * Set a variable in the request
	 *
	 * @param 	mixed	Variable identifier, prefixed by hash name eg post.foo.bar
	 * @param 	mixed	Variable value
	 */
	public static function set($identifier, $value) 
	{		
		list($hash, $keys) = self::_parseIdentifier($identifier);
		
		// Add to _REQUEST hash if original hash is get, post, or cookies
		// Even though we are not using $_REQUEST, other extensions do 
		if(in_array($hash, array('GET', 'POST', 'COOKIE'))) {
			self::set('request.'.implode('.', $keys), $value);
		}
			
		foreach(array_reverse($keys, true) as $key) {
			$value = array($key => $value);
		}
		
		$GLOBALS['_'.$hash] = array_merge($GLOBALS['_'.$hash], $value);
	}
	
	/**
	 * Check if a variable exists based on an identifier
	 *
	 * @param	string  Variable identifier, prefixed by hash name eg post.foo.bar
	 * @return 	boolean
	 */
	public static function has($identifier)
	{
		list($hashes, $keys) = self::_parseIdentifier($identifier);
		
		// find $var in the hashe
		foreach($keys as $key)
		{
			if(array_key_exists($part, $GLOBALS['_'.$hash])) {
				return true;;
			}
		}
		
		return false; 
	}

	/**
	 * Parse the a variable name
	 *
	 * @param 	string	Variable name
	 * @throws	KRequestException	When the identifier isn't valid or the hash could not be found
	 * @return 	array	0 => hash, 1 => parts
	 */
	protected function _parseIdentifier($identifier)
	{
		// Validate the variable format
		if(strpos($identifier, '.') === false) {
			 throw new KRequestException("Identifier needs to be of the format 'hash.foo.bar', you provided: ".$identifier);
		}
		
		// Split the variable name into it's parts
		$parts = explode('.', $identifier);
		
		// Validate the hash name
		$hash 	= strtoupper(array_shift($parts));
		if(!in_array($hash, self::$_hashes)) {
			throw new KRequestException("Unknown hash '$hash' in '$identifier'");
		}
			
		return array($hash, $parts);
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