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
	 * URL of the request regardless of the server
	 * 
	 * @var	KHttpUri
	 */
	protected static $_uri = null;
	
	/**
	 * Base path of the request.
	 * 
	 * @var	KHttpUri
	 */
	protected static $_base = null;
	
	/**
	 * Base path of the request.
	 * 
	 * @var	KHttpUri
	 */
	protected static $_referer = null;
	
	
	/**
	 * Get sanitized data from the request. 
	 * 
	 * @param	string				Variable identifier, prefixed by hash name eg post.foo.bar
	 * @param 	mixed				Filter(s), can be a KFilterInterface object, or array of filter names
	 * @param 	mixed				Default value when the variable doesn't exist
	 * @throws	KRequestException	When an invalid filter was passed
	 * @return 	mixed				The sanitized data 
	 */
	public static function get($identifier, $filter, $default = null)
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
		
		if(!($filter instanceof KFilterInterface))
		{
			$names = (array) $filter;
			
			$name   = array_shift($names);
			$filter = self::_createFilter($name);
			
			foreach($names as $name) {
				$filter->addFilter($this->_createFilter($name));
			}
		}
		
		return $filter->sanitize($result);
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
	 * Alias of referrer() 
	 * 
	 * 'referer' is the a commonly used misspelling of 'referrer'
	 * @see 	http://en.wikipedia.org/wiki/HTTP_referrer 
	 *  
	 * @param	boolean		Only allow internal url's
	 * @return  KHttpUri	A KHttpUri object
	 */
	public static function referer($isInternal = true) 
	{
		return self::referrer($isInternal);
	}
	
	/**
 	 * Returns the HTTP referer.
	 * 
	 * @param	boolean		Only allow internal url's
	 * @return  KHttpUri	A KHttpUri object
	 */
	public static function referrer($isInternal = true)
	{
		if(empty(self::$_referer))
		{
			$referer = KRequest::get('server.HTTP_REFERER', 'url');
			self::$_referer = KFactory::get('lib.koowa.http.uri', array('uri' => $referer));
		}
		
		if($isInternal) 
		{	
			if(!KFactory::get('lib.koowa.filter.internalurl')->validate(self::$_referer)) {
				return null;
			}
		}
		
		return self::$_referer;
	}
	
	/**
 	 * Return the URI of the request regardless of the server
	 *
	 * @return  KHttpUri	A KHttpUri object
	 */
	public static function url()
	{
		if(empty(self::$_uri))
		{
			/*
	     	 * Since we are assigning the URI from the server variables, we first need
	     	 * to determine if we are running on apache or IIS.  If PHP_SELF and REQUEST_URI
		 	 * are present, we will assume we are running on apache.
		 	 */
			if (!empty ($_SERVER['PHP_SELF']) && !empty ($_SERVER['REQUEST_URI'])) 
			{	
				/*
			 	 * To build the entire URI we need to prepend the protocol, and the http host
			 	 * to the URI string.
			 	 */
				$url = self::protocol().'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

				/*
			 	 * Since we do not have REQUEST_URI to work with, we will assume we are
			 	 * running on IIS and will therefore need to work some magic with the SCRIPT_NAME and
			 	 * QUERY_STRING environment variables.
			 	 */
			}
			else
			{
				// IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable... thanks, MS
				$url = self::protocol.'://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

				// If the query string exists append it to the URI string
				if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
					$url .= '?' . $_SERVER['QUERY_STRING'];
				}
			}
			
			// Sanitize the url since we can't trust the server var
			$url = KFactory::get('lib.koowa.filter.url')->sanitize($url);
			
			// Create the URI object
			self::$_uri = KFactory::tmp('lib.koowa.http.uri', array('uri' => $url));	
			
		}	
		
		return self::$_uri;
	}
	
	/**
	 * Returns the base path of the request.
	 *
	 * @return  string
	 */
	public static function base()
	{
		if(empty(self::$_base))
		{
			// Get the base request path
			if (strpos(php_sapi_name(), 'cgi') !== false && !empty($_SERVER['REQUEST_URI'])) {
				//Apache CGI
				$path =  rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			} else {
				//Others
				$path =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
			}
			
			// Sanitize the url since we can't trust the server var
			$path = KFactory::get('lib.koowa.filter.url')->sanitize($path);
			
			self::$_base = clone(self::url());
			self::$_base->setPath($path);
		}

		return self::$_base;
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
		
		if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
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
	public static function languages()
	{
		$accept		= KRequest::get('server.HTTP_ACCEPT_LANGUAGE', 'string');

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
	 * Parse the variable identifier
	 *
	 * @param 	string	Variable identifier
	 * @throws	KRequestException	When the hash could not be found
	 * @return 	array	0 => hash, 1 => parts
	 */
	protected static function _parseIdentifier($identifier)
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
	
	/**
	 * Create a filter based on it's name
	 *
	 * @param 	string	Variable name
	 * @throws	KRequestException	When the filter could not be found
	 * @return  KFilterInterface
	 */
	protected static function _createFilter($name)
	{
		try {
			$filter = KFactory::get('lib.koowa.filter.'.$name);
		} catch(KFactoryAdapterException $e) {
			throw new KRequestException('Invalid filter: '.$name);
		}
		
		return $filter;
	}
}