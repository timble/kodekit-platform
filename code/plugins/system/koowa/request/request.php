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
	 * Accepted request hashes
	 * 
	 * @var	array
	 */
	protected static $_hashes = array('COOKIE', 'ENV', 'FILES', 'GET', 'POST', 'SERVER', 'REQUEST');
	
	/**
	 * Accepted request methods
	 * 
	 * @var	array
	 */
	protected static $_methods = array('GET', 'HEAD', 'OPTIONS', 'POST', 'PUT', 'DELETE', 'CLI');
	
	/**
	 * Accepted request types
	 * 
	 * @var	array
	 */
	protected static $_types = array('AJAX', 'FLASH');
	
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
 	 * Returns the HTTP referer, or the default if the referrer is not set.
	 *
	 * @return  string
	 */
	public static function referer()
	{
		$referer = KRequest::get('server.HTTP_REFERER', 'internalurl');
		
		if (!empty($referer))
		{
			/*if (strpos($ref, url::base(FALSE)) === 0)
			{
				// Remove the base URL from the referrer
				$ref = substr($ref, strlen(url::base(true)));
			}*/
		}
	
		return $referer;
	}

	/**
	 * Returns the current request protocol, based on $_SERVER['https']. In CLI
	 * mode, NULL will be returned.
	 *
	 * @return  string
	 */
	public static function protocol()
	{
		if (PHP_SAPI === 'cli') {
			return NULL;
		}
		
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
			return 'https';
		} else {
			return 'http';
		}
	}
	
	/**
 	 * Return the accepted languages
 	 *
 	 * @return	array locale
 	 */
	public function languages()
	{
		$accept		= KRequest::get('server.HTTP_ACCEPT_LANGUAGE', 'raw', null);

		$languages  = substr( $accept, 0, strcspn($accept, ';' ) );
		$languages	= explode( ',', $languages );
		$languages  = array_map('strtolower', $languages);
		
		return $languages;
	}
	
	/**
	 * Returns current request method.
	 *
	 * @return  string
	 */
	public static function method()
	{
		if(PHP_SAPI != 'cli') 
		{
			$method   =  strtoupper($_SERVER['REQUEST_METHOD']);
	
			if($method == 'POST')
			{
				if(isset($_SERVER['X-HTTP-Method-Override'])) {
					$method =  strtoupper($_SERVER['X-HTTP-Method-Override']);
				}
			}
		} else $method = 'CLI';
		
		if ( ! in_array($method, self::$_methods)) {
			throw new KRequestException('Unknown method : '.$method);
		}
        
	  	return $method;
	}
	
	/**
	 * Return the current request type.
	 *
	 * @return  string
	 */
	public static function type()
	{
		$type = '';
		
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			$type = 'AJAX';
		}
		
		if( isset($_SERVER['HTTP_X_FLASH_VERSION'])) {
			$type = 'FLASH';
		}
		
		if ( ! in_array($type, self::$_type)) {
			throw new KRequestException('Unknown type : '.$method);
		}
		
		return $type;
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
		$parts = array();
		$hash  = $identifier;
		
		// Validate the variable format
		if(strpos($identifier, '.') !== false) 
		{
			// Split the variable name into it's parts
			$parts = explode('.', $identifier);
		
			// Validate the hash name
			$hash 	= array_shift($parts);
		}
		
		$hash = strtoupper($hash);
		
		if(!in_array($hash, self::$_hashes)) {
			throw new KRequestException("Unknown hash '$hash' in '$identifier'");
		}
		
		return array($hash, $parts);
	}
}