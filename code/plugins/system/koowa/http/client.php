<?php
/**
 * @version     $Id:  $
 * @package     Koowa_Http
 * @subpackage  Client
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * HTTP Client class
 * 
 * @author      Laurens Vandeput <laurens@joomlatools.org>
 * @package     Koowa_Http
 * @subpackage  Client
 * @version     1.0
 */
class KHttpClient extends KObject 
{
	const GET 				= 'GET';	
	const POST 				= 'POST';	
	const PUT 				= 'PUT';
	const HEAD    			= 'HEAD';
	
	const ENC_URLENCODED 	= 'application/x-www-form-urlencoded';
    const ENC_FORMDATA   	= 'multipart/form-data';
	
	protected $_method 		= self::GET;
	
	protected $_last_request;
	protected $_last_response;
	protected $_no_redirects;
	
	protected $_authentication;
	
	protected $_config;
	protected $_uri;
	protected $_enctype;
	
	protected $_headers;

	protected $_adapter = null;
	
	public function __construct($options = null) 
	{
		$this->setConfig($options);
	}
	
	public function setConfig($config) 
	{
		$this->_config = array(
	        'maxredirects'	=> 5,
			'timeout'		=> 10
    	);
    	
    	if (!is_null($config))
    	{
    		array_merge($this->_config, $config);
    	}
	}
	
	public function setURI($uri) 
	{
		if (is_string($uri)) {
			$this->_uri = new KHttpUri($uri);
		} else if ($uri instanceof KHttpUri) {
			$this->_uri = $uri;
		} else {
			throw new KHttpException("URI ({$uri->__toString()}) is not a valid type.");
		}
	}
	
	public function setAuthentication($username, $password) 
	{
		if (!is_null($username) || $username === false) 
		{
			$_authentication = array(
				'username'	=> $username,
				'password'	=> $password
			);
		}
	}
	
	public function getURI() 
	{
		return $this->_uri;
	}
	
	public function setMethod($method) 
	{
		if (!preg_match('/^[A-Za-z_]+$/', $method)) {
			throw new KHttpException("$method is not a valid HTTP operation!");
		}
		
		if ($method == self::POST && $this->_enctype === null) {
			$this->_enctype = $this->ENC_URLENCODED;
		}
		
		$this->_method = $method;
	}
	
	public function setAdapter(KHttpClientAdapter $adapter) {
		$this->_adapter = $adapter;
	}
	
	public function setHeaders($name, $value = null) 
	{
		if (is_array($name)) 
		{
			foreach ($name as $key => $value) {
				$this->setHeaders($key, $value);
			}
		} 
		else 
		{
			if (is_string($value)) {
				$value = trim($value);
			}
			if ($value == null) {
				unset($this->_headers[strtolower($name)]);
			}
			else {
				$this->_headers[strtolower($name)] = $value;
			}
		}
	}
	
	public function getHeader($key) 
	{
		$key = strtolower($key);
		
		if (isset($this->_headers[$key])) {
			return $this->_headers[$key];
		} else {
			return null;
		}
	}
	
	public function send($uri, $data) 
	{
		$prerequisites = array(
			array('object' => $this->_uri, 		'message' => "A URI must be set!"),
			array('object' => $this->_method, 	'message' => "A HTTP method must set!"),
			array('object' => $data,		 	'message' => "Data cannot be null!"),
			array('object' => $this->_adapter, 	'message' => "A connection adapter must be set!")
		);
	
		foreach ($prerequisites as $requirement) {
			if (is_null($requirement['object'])) {
				throw new KHttpException($requirement['message']);
			}
		}
		
		if (is_string($uri)) {
			$uri = new KHttpUri($uri);
		}

		$response = null;

		$query = http_build_query($data);
		$uri->setQuery($query);
		
		$this->setHeaders('Content-type', self::ENC_URLENCODED);
        $body = http_build_query($data, '', '&');

        if ($body || $this->method == self::POST || $this->method == self::PUT) {
            $this->setHeaders('Content-length', strlen($body));
        }
		
		$options = array(
			'method' 	=> $this->_method,
			'headers' 	=> $this->_headers,
			'body' 		=> $body
		);
				
		$this->_adapter->connect($this->_uri);
		$this->_last_request = $this->_adapter->write($uri, $options);
		$response = $this->_adapter->read();
		$this->_adapter->disconnect();
				
		if (!$response) {
			throw new KHttpException("Unable to read response, or response is empty.");
		} 
				
		$this->_last_response = $response;
		
		return $this->_last_response;
	}
	
	public function getLastRequest() 
	{
		return $this->_last_request;
	}
	
	public function getLastResponse() 
	{
		return $this->_last_response;
	}
	
}
