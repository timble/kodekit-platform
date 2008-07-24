<?php
/**
 * @version     $Id:uri.php 275 2008-06-19 10:41:12Z mjaz $
 * @package     Koowa_Http
 * @subpackage  Client
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * HTTP URI class
 * 
 * @author      Laurens Vandeput <laurens@joomlatools.org>
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Http
 * @subpackage  Uri
 * @version     1.0
 */
class KHttpUri extends KObject 
{
	/**
	 * URI scheme
	 *
	 * @var	string
	 */
	protected $_scheme;
	
	/**
	 * URI host
	 *
	 * @var	string
	 */
	protected $_host;
	
	/**
	 * URI port
	 *
	 * @var	string
	 */
	protected $_port;
	
	/**
	 * URI user
	 *
	 * @var	string
	 */
	protected $_user;
	
	/**
	 * URI pass
	 *
	 * @var	string
	 */
	protected $_pass;
	
	/**
	 * URI path
	 *
	 * @var	string
	 */
	protected $_path;
	
	/**
	 * URI query
	 *
	 * @var	array
	 */
	protected $_query;
	
	/**
	 * URI fragment
	 *
	 * @var	string
	 */
	protected $_fragment;
	
	/**
	 * Constructor
	 *
	 * @param	string	Uri
	 */
	public function __construct($uri) 
	{
		$this->setURI($uri);
	}
	
	/**
	 * Returns an new instance of KHttpUri
	 *
	 * @param	string	Uri
	 * @return 	KHttpUri
	 */
	public function getInstance($uri)
	{
		$isntance =  new KHttpUri($uri);
		return $instance;
	}
	
	/**
	 * Get a query variable
	 *
	 * @param	string	Key
	 * @param 	mixed	Default value
	 * @return 	mixed	Value
	 */
	public function get($key, $default = null)
	{
		return isset($this->_query[$key]) ? $this->_query[$key] : $default;
	}
	
	/**
	 * Set a query variable
	 *
	 * @param	string	Key	
	 * @param 	mixed	Value
	 * @return	this
	 */
	public function set($key, $value)
	{
		$this->_query[$key] = $value;
		return $this;
	}
	
	/**
	 * Get the full URI, of the format scheme://user:pass@host/path?query#fragment';
	 *
	 * @return	string
	 */
	public function getURI()
	{
		$uri = '';
		$uri .= !empty($this->_scheme)  ? $this->_scheme.'://' : '';
		
		if(!empty($this->_user) AND !empty($this->_pass)) {
			$uri .= $this->_user . ':' . $this->_pass . '@';
		} elseif(!empty($this->_user) AND empty($this->_pass)) {
			$uri .= $this->_user . '@';
		}
		
		$uri .= $this->_host;
		$uri .= !empty($this->_port)  ? ':' . $this->_port : '';
		$uri .= $this->_path;
		$uri .= !empty($this->_query) 	 ? '?' . http_build_query($this->_query) : '';
		$uri .= !empty($this->_fragment) ? '#' . $this->_fragment : '';

		return $uri;
	}
	
	/**
	 * Set the URI
	 *
	 * @param	string	URI
	 * @return 	this
	 */
	public function setURI($uri) 
	{
		$segments = parse_url($uri);
		
		foreach ($segments as $key => $value) {
			$key = '_'.$key;
			$this->$key = $value;
		}
		
		parse_str($this->_query, $this->_query); 
		
		return $this;
	}
		
	/**
	 * Get scheme
	 *
	 * @return	string
	 */
	public function getScheme() 
	{
		return $this->_scheme;
	}
	
	/**
	 * Set Scheme
	 *
	 * @param	string	Scheme
	 * @return 	this
	 */
	public function setScheme($scheme)
	{
		$this->_scheme = $scheme;
		return $this;
	}
	
	/**
	 * Get host
	 *
	 * @return	string
	 */
	public function getHost() 
	{
		return $this->_host;
	}
	
	/**
	 * Set Host
	 *
	 * @param	string	Host
	 * @return 	this
	 */
	public function setHost($host)
	{
		$this->_host = $host;
		return $this;
	}
	
	/**
	 * Get port
	 *
	 * @return	string
	 */
	public function getPort() 
	{
		return $this->_port;
	}
	
	/**
	 * Set Port
	 *
	 * @param	string	Port
	 * @return 	this
	 */
	public function setPort($port)
	{
		$this->_port = $port;
		return $this;
	}

	/**
	 * Get user
	 *
	 * @return	string
	 */
	public function getUser() 
	{
		return $this->_user;
	}
	
	/**
	 * Set User
	 *
	 * @param	string	User
	 * @return 	this
	 */
	public function setUser($user)
	{
		$this->_user = $user;
		return $this;
	}
	
	/**
	 * Get password
	 *
	 * @return	string
	 */
	public function getPass() 
	{
		return $this->_pass;
	}
	
	/**
	 * Set Password
	 *
	 * @param	string	Pass
	 * @return 	this
	 */
	public function setPass($pass)
	{
		$this->_pass = $pass;
		return $this;
	}
	
	/**
	 * Get path
	 *
	 * @return	string
	 */
	public function getPath() 
	{
		return $this->_path;
	}
	
	/**
	 * Set Path
	 *
	 * @param	string	Path
	 * @return 	this
	 */
	public function setPath($path)
	{
		$this->_path = $path;
		return $this;
	}
	
	/**
	 * Get query array
	 *
	 * @return	array	Associated array
	 */
	public function getQuery() 
	{
		return $this->_query;
	}
	
	/**
	 * Set Query
	 * 
	 * Accepts a string (foo=bar&foo2=bar2) or an associated array
	 *
	 * @param	array|string	Query
	 * @return 	this
	 */
	public function setQuery($query)
	{
		if(!is_array($query)) 
		{
			parse_str($query, $query);
		}
		$this->_query = $query;
		return $this;
	}
	
	/**
	 * Get fragment
	 *
	 * @return	string
	 */
	public function getFragment() 
	{
		return $this->_fragment;
	}
	
	/**
	 * Set Fragment
	 *
	 * @param	string	Fragment
	 * @return 	this
	 */
	public function setFragment($fragment)
	{
		$this->_fragment = $fragment;
		return $this;
	}
}