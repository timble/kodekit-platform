<?php
/**
 * @version     $Id:  $
 * @package     Koowa
 * @subpackage  Client
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * HTTP Client Adapter interface
 * 
 * @author      Laurens Vandeput <laurens@joomlatools.org>
 * @package     Koowa
 * @subpackage  Client
 * @version     1.0
 */

class KHttpClientAdapterSocket extends KObject implements KHttpClientAdapter 
{

	/**
	 * HTTP Socket
	 *
	 * @var resource
	 */
	protected $_socket;
	
	/**
	 * Configuration
	 *
	 * @var array
	 */
	protected $_configuration;
	
	/**
	 * Current connection
	 *
	 * @var string
	 * @var int
	 */
	protected $_current_connection = array(null, null);
	
	/**
	 * Method
	 *
	 * @var string
	 */
	protected $_method;
	
	public function __construct($options = null) 
	{
		$this->_configuration = $options;
	}

	/**
	 * Connect to the given URI.
	 *
	 * @param KHttpUri
	 */
	public function connect(KHttpUri $uri) 
	{
		if ($this->_current_connection[0] == $uri->getScheme() . '://' .$uri->getHost() && $this->_current_connection[1] == $uri->getPort()) 
		{
			if (is_resource($this->_socket))
			{
				$this->disconnect();
			}
		}
		
		if (!is_resource($this->_socket) || $this->_configuration['keep_alive'])
		{
			$this->_socket = stream_socket_client($uri->getHost() . ':' . $uri->getPort(),
													$errno,
													$errstr,
													(int) $this->_configuration['timeout']);
			if (!$this->_socket) 
			{
				$this->disconnect();
				throw new KException('Unable not connect to ' . $uri->getHost() . ':' . $uri->getPort() . '/' . $uri->getPath());
			}
			
			if (!stream_set_timeout($this->_socket, $this->_configuration['timeout']))
			{
				throw new KException("Unable to set connection timeout");
			}
			
			$this->_connected_to = array($uri->getScheme() . '://' .$uri->getHost(), $uri->getPort());
		}
	}
	
	/**
	 * Close the connection to the server
	 *
	 */
	public function disconnect() 
	{
		if (is_resource($this->_socket))
		{
			@fclose($this->_socket);
			$this->_socket = null;
			$this->_connected_to = array(null, null);
		}
	}
	
	public function read() 
	{
		// First, read headers only
        $response = '';
        $gotStatus = false;
        
        while ($line = @fgets($this->socket)) 
        {
            $gotStatus = $gotStatus || (strpos($line, 'HTTP') !== false);
            
            if ($gotStatus) 
            {
                $response .= $line;
                if (!chop($line)) break;
            }
        }

        if (KHttpClientResponse::extractCode($response) == 100 || KHttpClientResponse::extractCode($response) == 101)
        {
        	return $this->read();
        }

        // If this was a HEAD request, return after reading the header (no need to read body)
        if ($this->_method == KHttpClient::HEAD) 
        {
        	return $response;
        }

        // Check headers to see what kind of connection / transfer encoding we have
        $headers = KHttpClientResponse::extractHeaders($response);

        // if the connection is set to close, just read until socket closes
        if (isset($headers['connection']) && $headers['connection'] == 'close') 
        {
            while ($buff = @fread($this->socket, 8192)) 
            {
                $response .= $buff;
            }

            $this->close();

        // Else, if we got a transfer-encoding header (chunked body)
        } 
        elseif (isset($headers['transfer-encoding'])) 
        {
            if ($headers['transfer-encoding'] == 'chunked') 
            {
                do {
                    $chunk = '';
                    $line = @fgets($this->socket);
                    $chunk .= $line;

                    $hexchunksize = ltrim(chop($line), '0');
                    $hexchunksize = strlen($hexchunksize) ? strtolower($hexchunksize) : 0;

                    $chunksize = hexdec(chop($line));
                    
                    if (dechex($chunksize) != $hexchunksize) 
                    {
                        @fclose($this->socket);
                        throw new KException('Invalid chunk size "' .  $hexchunksize . '" unable to read chunked body');
                    }

                    $left_to_read = $chunksize;
                    
                    while ($left_to_read > 0) 
                    {
                        $line = @fread($this->socket, $left_to_read);
                        $chunk .= $line;
                        $left_to_read -= strlen($line);
                    }

                    $chunk .= @fgets($this->socket);
                    $response .= $chunk;
                } while ($chunksize > 0);
            } 
            else 
            {
                throw new KException('Cannot handle "' . $headers['transfer-encoding'] . '" transfer encoding');
            }

        // Else, if we got the content-length header, read this number of bytes
        } 
        elseif (isset($headers['content-length'])) 
        {
            $left_to_read = $headers['content-length'];
            $chunk = '';
            
            while ($left_to_read > 0) 
            {
                $chunk = @fread($this->socket, $left_to_read);
                $left_to_read -= strlen($chunk);
                $response .= $chunk;
            }
        } 
        else 
        {
            while ($buff = @fread($this->socket, 8192)) 
            {
                $response .= $buff;
            }

            $this->disconnect();
        }

        return $response;
	}
	
	public function write(KHttpUri $uri, $options) {	
        if (!$this->_socket) 
        {
            throw new KException('Trying to write but we are not connected');
        }

        $host = $uri->getScheme() . '://' .$uri->getHost();
        
        if ($this->_connected_to[0] != $host || $this->_connected_to[1] != $uri->getPort()) 
        {
            throw new KException('Trying to write but we are connected to the wrong host');
        }

        // Save request method for later
        $this->_method = $options['method'];

        // Build request headers
        $path = $uri->getPath();
        if ($uri->getQuery()) $path .= '?' . $uri->getQuery();
        $request = "{$options['method']} {$path} HTTP/{$this->_configuration['http_version']}\r\n";
        
        if (is_array($headers = $options['headers']))
        {
       		foreach ($headers as $k => $v) 
        	{
            	if (is_string($k)) $v = ucfirst($k) . ": $v";
            	$request .= "$v\r\n";
        	}
        }

        // Add the request body
        $request .= "\r\n" . $options['body'];

        // Send the request
        if (!@fwrite($this->_socket, $request)) {
            throw new KException('Error writing request to server');
        }

        return $request;
	}
}