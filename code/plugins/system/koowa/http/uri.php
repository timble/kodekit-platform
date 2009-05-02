<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Http
 * @copyright   Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * HTTP URI class
 * 
 * This class helps you to create and manipulate URIs, including query
 * strings and path elements. It does so by splitting up the pieces of the
 * URI and allowing you modify them individually; you can then then fetch
 * them as a single URI string. This helps when building complex links,
 * such as in a paged navigation system.
 * 
 * The following is a simple example. Say that the page address is currently
 * `http://anonymous::guest@example.com/path/to/index.php/foo/bar?baz=dib#anchor`.
 * 
 *  You can use KHttpUri to parse this complex string very easily:
 * 
 * {{code: php
 *     // Create a URI object;
 *    
 *     $url = http://anonymous:guest@example.com/path/to/index.php/foo/bar.xml?baz=dib#anchor
 *     $uri = KFactory::get('lib.koow.http.uri', $url);
 * 
 *     // the $uri properties are ...
 *     // 
 *     // $uri->scheme   => 'http'
 *     // $uri->host     => 'example.com'
 *     // $uri->user     => 'anonymous'
 *     // $uri->pass     => 'guest'
 *     // $uri->path     => array('path', 'to', 'index.php', 'foo', 'bar')
 *     // $uri->format   => 'xml'
 *     // $uri->query    => array('baz' => 'dib')
 *     // $uri->fragment => 'anchor'
 * }}
 * 
 * Now that we have imported the URI and had it parsed automatically, we
 * can modify the component parts, then fetch a new URI string.
 * 
 * {{code: php
 *     // change to 'https://'
 *     $uri->scheme = 'https';
 * 
 *     // remove the username and password
 *     $uri->user = '';
 *     $uri->pass = '';
 * 
 *     // change the value of 'baz' to 'zab'
 *     $uri->setQuery('baz', 'zab');
 * 
 *     // add a new query element called 'zim' with a value of 'gir'
 *     $uri->query['zim'] = 'gir';
 * 
 *     // reset the path to something else entirely.
 *     // this will additionally set the format to 'php'.
 *     $uri->setPath('/something/else/entirely.php');
 * 
 *     // add another path element
 *     $uri->path[] = 'another';
 *     
 *     // and fetch it to a string.
 *     $new_uri = $uri->get();
 * 
 *     // the $new_uri string is as follows; notice how the format
 *     // is always applied to the last path-element.
 *     // /something/else/entirely/another.php?baz=zab&zim=gir#anchor
 *
 *     // Get the full URL to get the shceme and host
 *     $full_uri = $uri->get(true);
 * 
 *     // the $full_uri string is:
 *     // https://example.com/something/else/entirely/another.php?baz=zab&zim=gir#anchor
 * }}
 * 
 * 
 * This class has a number of public properties, all related to
 * the parsed URI processed by [[KHttpUri::set()]]. They are ...
 * 
 * | Name       | Type    | Description
 * | ---------- | ------- | --------------------------------------------------------------
 * | `scheme`   | string  | The scheme protocol; e.g.: http, https, ftp, mailto
 * | `host`     | string  | The host name; e.g.: example.com
 * | `port`     | string  | The port number
 * | `user`     | string  | The username for the URI
 * | `pass`     | string  | The password for the URI
 * | `path`     | array   | A sequential array of the path elements
 * | `format`   | string  | The filename-extension indicating the file format
 * | `query`    | array   | An associative array of the query terms
 * | `fragment` | string  | The anchor or page fragment being addressed
 * 
 * As an example, the following URI would parse into these properties:
 * 
 *     http://anonymous:guest@example.com:8080/foo/bar.xml?baz=dib#anchor
 *     
 *     scheme   => 'http'
 *     host     => 'example.com'
 *     port     => '8080'
 *     user     => 'anonymous'
 *     pass     => 'guest'
 *     path     => array('foo', 'bar')
 *     format   => 'xml'
 *     query    => array('baz' => 'dib')
 *     fragment => 'anchor'
 * 
 * 
 * 
 * @author      Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Http
 */
class KHttpUri extends KObject 
{
	/**
	 * The scheme (for example 'http' or 'https').
	 *
	 * @var	string
	 */
	public $scheme = '';
	
	/**
	 * The host specification (for example, 'example.com').
	 *
	 * @var	string
	 */
	public $host = '';
	
	/**
	 * The port number (for example, '80').
	 *
	 * @var	string
	 */
	public $port = '';
	
	/**
	 * The username, if any.
	 *
	 * @var	string
	 */
	public $user = '';
	
	/**
	 * The password, if any.
	 *
	 * @var	string
	 */
	public $pass = '';
	
	/**
	 * The path portion (for example, 'path/to/index.php').
	 *
	 * @var	string
	 */
	public $path = '';
	
	/**
     * The dot-format extension of the last path element (for example, the "rss"
     * in "feed.rss").
     * 
     * @var string
     */
    public $format = '';
    
    /**
     * The query portion (for example baz=dib)
     * 
     * Public access is allowed via __get() with $query.
     * 
     * @var array
     * 
     * @see __set()
     * @see __get()
     * @see setQuery()
     * @see getQuery()
     */
    protected $_query = array();
	
    /**
     * The fragment portion (for example, the "foo" in "#foo").
     * 
     * @var string
     */
    public $fragment = '';
	  
    /** 
     * Url-encode only these characters in path elements.
     * 
     * Characters are ' ' (space), '/', '?', '&', and '#'.
  	 *
     * @var array 
     */
    protected $_encode_path = array (
        ' ' => '+',
        '/' => '%2F',
        '?' => '%3F',
        '&' => '%26',
        '#' => '%23',
    );    
	
	/**
	 * Constructor
	 *
	 * @param	string	Uri
	 */
	public function __construct($uri) 
	{
		$this->set($uri);
	}
	
	/** 
     * Implements the virtual $query property.
     * 
     * @param string The virtual property to set.
     * @param string Set the virtual property to this value.
     * @return mixed The value of the virtual property.
     */
    public function __set($key, $val)
    {
        if ($key == 'query') {
        	$this->setQuery($val);
        }
        
        if($key == 'path') {
        	$this->setPath($val);
        }
    }
    
    /**
     * Implements access to $_query by reference so that it appears to be 
     * a public $query property.
     * 
     * @param string $key The virtual property to return.
     * @return array
     */
    public function &__get($key)
    {
        if ($key == 'query') {
           return $this->_vars;
        }
    }
    
	/**
	 * Get the full URI, of the format scheme://user:pass@host/path?query#fragment';
	 *
	 * @param bool If true, returns a full URI with scheme, user, pass, host, and port.  
	 * Otherwise, just returns the path, format, query, and fragment.  Default false.
	 * @return	string
	 */
	public function get($full = false)
	{
		$uri = '';
		
		 // are we doing a full URI?
        if ($full) 
        {
			//Add the scheme
			$uri .= !empty($this->scheme)  ? urlencode($this->scheme).'://' : '';
		
			//Add the username and password
       		if (! empty($this->user)) 
       		{
        		$uri .= urlencode($this->user);
          		if (! empty($this->pass)) {
            		$uri .= ':' . urlencode($this->pass);
          		}
          	
          		$uri .= '@';
       		}
       	
       		// Add the host and port, if any.
      		$uri .= (empty($this->host) ? '' : urlencode($this->host))
            	 . (empty($this->port) ? '' : ':' . (int) $this->port);
        }
             
		// Get the query as a string
        $query = $this->getQuery();
        
        // Add the rest of the URI. we use trim() instead of empty() on string
        // elements to allow for string-zero values.
        $uri .= (empty($this->path)           ? '' : $this->_pathEncode($this->path));
        $uri .= (trim($this->format) === ''   ? '' : '.' . urlencode($this->format));
        $uri .= (empty($query)                ? '' : '?' . $query);
        $uri .= (trim($this->fragment) === '' ? '' : '#' . urlencode($this->fragment));
             
		return $uri;
	}
	
	/**
	 * Set the URI
	 *
	 * @param	string	URI
	 * @return 	this
	 */
	public function set($uri) 
	{
		$segments = parse_url(urldecode($uri));
		
		foreach ($segments as $key => $value) {
			$this->$key = $value;
		}
		
		return $this;
	}
	
	/**
     * Sets the query string in the URI, for KhttpUri::getQuery() and KhttpUri::$query.
     * 
     * This will overwrite any previous values.
     * 
     * @param 	string|array 	The query string to use; for example `foo=bar&baz=dib`.
     * @return this
     */
    public function setQuery($query)
    {
        if(!is_array($query)) 
        {
			if(strpos($query, '&amp;') !== false) {
			   $query = str_replace('&amp;','&',$query);
			}
			
			//Set the query vars
			parse_str($query, $this->_query);
		}

		if(is_array($query)) {
			$this->_query = $query;
		}
		    
        return $this;
    }
    
    /**
     * Returns the query portion as a string or array
     * 
     * @return 	string|array 	The query string; e.g., `foo=bar&baz=dib`.
     */
    public function getQuery($toArray = false)
    {
		$result = $toArray ? $this->_query : http_build_query($this->_query);
		return $result;
    }
    
	/**
     * Sets the KHttpUri::$path array and $format from a string.
     * 
     * This will overwrite any previous values. Also, resets the format based
     * on the final path value.
     * 
     * @param 	string 	The path string to use; for example,"/foo/bar/baz/dib".  
     * A leading slash will *not* create an empty first element; if the string 
     * has a leading slash, it is ignored.
     * @return this
     */
    public function setPath($path)
    {
        $spec = trim($path, '/');
        
        $this->path = array();
        if (! empty($path)) {
            $this->path = explode('/', $path);
        }
        
        foreach ($this->path as $key => $val) {
            $this->path[$key] = urldecode($val);
        }
        
        if ($val = end($this->path)) 
        {
            // find the last dot in the value
            $pos = strrpos($val, '.');
            
            if ($pos !== false) 
            {
                $key = key($this->path);
                $this->format = substr($val, $pos + 1);
                $this->path[$key] = substr($val, 0, $pos);
            }
        }
    }
	
	
	/**
     * Return a string representation of this URI.
     *
     * @see    getUri()
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }
    
    /**
     * Converts an array of path elements into a string.
     * 
     * Does not use [[php::urlencode() | ]]; instead, only converts
     * characters found in KHttpUri::$_encode_path.
     * 
     * @param 	array The path elements.
     * @return string A URI path string.
     */
    protected function _pathEncode($spec)
    {
        if (is_string($spec)) {
            $spec = explode('/', $spec);
        }
        
        $keys = array_keys($this->_encode_path);
        $vals = array_values($this->_encode_path);
        
        $out = array();
        foreach ((array) $spec as $elem) {
            $out[] = str_replace($keys, $vals, $elem);
        }
        
        return implode('/', $out);
    }
    
}