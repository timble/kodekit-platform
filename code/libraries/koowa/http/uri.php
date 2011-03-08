<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Http
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
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
 * You can use KHttpUri to parse this complex string very easily:
 * 
 * <code>
 * <?php
 *     // Create a URI object;
 *    
 *     $url = 'http://anonymous:guest@example.com/path/to/index.php/foo/bar.xml?baz=dib#anchor'
 *     $uri = KFactory::get('lib.koowa.http.uri', array('uri' => $url) );
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
 * ?>
 * </code>
 * 
 * Now that we have imported the URI and had it parsed automatically, we
 * can modify the component parts, then fetch a new URI string.
 * 
 * <code>
 * <?php
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
 * ?>
 * </code>
 *  
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Http
 */
class KHttpUri extends KObject 
{
    /**
     * The URI parts
     * 
     * @see get()
     */
    const PART_SCHEME   = 1;
    const PART_USER     = 2;
    const PART_PASS     = 4;
    const PART_HOST     = 8;
    const PART_PORT     = 16;
    const PART_PATH     = 32;
    const PART_FORMAT   = 64;
    const PART_QUERY    = 128;
    const PART_FRAGMENT = 256;
    
    const PART_AUTH     = 6;
    const PART_BASE     = 63;
    const PART_ALL      = 511;
    
    /**
     * The scheme [http|https|ftp|mailto|...]
     *
     * @var string
     */
    public $scheme = '';
    
    /**
     * The host specification (for example, 'example.com').
     *
     * @var string
     */
    public $host = '';
    
    /**
     * The port number (for example, '80').
     *
     * @var string
     */
    public $port = '';
    
    /**
     * The username, if any.
     *
     * @var string
     */
    public $user = '';
    
    /**
     * The password, if any.
     *
     * @var string
     */
    public $pass = '';
    
    /**
     * The path portion (for example, 'path/to/index.php').
     *
     * @var string
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
     * @see setQuery()
     * @see getQuery()
     */
    protected $_query = array();
    
    /**
     * The fragment aka anchor portion (for example, the "foo" in "#foo").
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
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config = null) 
    {
        //If no config is passed create it
        if(!isset($config)) $config = new KConfig();
        
        parent::__construct($config);
        
        $this->set($config->uri); 
    }
    
    /**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'uri'  => '',
        ));
        
        parent::_initialize($config);
    }
    
    /** 
     * Implements the virtual $query property.
     * 
     * @param   string  The virtual property to set.
     * @param   string  Set the virtual property to this value.
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
     * @param   string  The virtual property to return.
     * @return  array   The value of the virtual property.
     */
    public function &__get($key)
    {
        if ($key == 'query') {
           return $this->_query;
        }
    }
    
    /**
     * Get the full URI, of the format scheme://user:pass@host/path?query#fragment';
     *
     * @param integer A bitmask of binary or'ed HTTP_URL constants; PART_ALL is the default
     * @return  string
     */
    public function get($parts = self::PART_ALL)
    {
        $uri = '';
        
        //Add the scheme
        if(($parts & self::PART_SCHEME) && !empty($this->scheme)) {
            $uri .=  urlencode($this->scheme).'://';
        }  
        
        //Add the username and password
        if(($parts & self::PART_USER) && !empty($this->user)) 
        {
            $uri .= urlencode($this->user);
            if(($parts & self::PART_PASS) && !empty($this->pass)) {
                $uri .= ':' . urlencode($this->pass);
            }
            
            $uri .= '@';
        }
        
        // Add the host and port, if any.
        if(($parts & self::PART_HOST) && !empty($this->host)) 
        {
            $uri .=  urlencode($this->host);
            
            if(($parts & self::PART_PORT) && !empty($this->port)) {
                $uri .=  ':' . (int) $this->port;
            }
        }
        
        // Add the rest of the URI. we use trim() instead of empty() on string
        // elements to allow for string-zero values.
        if(($parts & self::PART_PATH) && !empty($this->path)) 
        {
            $uri .= $this->_pathEncode($this->path);
            if(($parts & self::PART_FORMAT) && trim($this->format) !== '') {
                $uri .= '.' . urlencode($this->format);
            }
        }
        
        $query = $this->getQuery();
        if(($parts & self::PART_QUERY) && !empty($query)) {
            $uri .= '?' . $this->getQuery();
        }
        
        if(($parts & self::PART_FRAGMENT) && trim($this->fragment) !== '') {
            $uri .=  '#' . urlencode($this->fragment);
        }
                     
        return $uri;
    }
    
    /**
     * Set the URI
     *
     * @param   string  URI
     * @return  KHttpUri
     */
    public function set($uri) 
    {
        if(!empty($uri)) 
        {
            $segments = parse_url(urldecode($uri));
            
            foreach ($segments as $key => $value) {
                $this->$key = $value;
            }
            
            if($this->format = pathinfo($this->path, PATHINFO_EXTENSION)) {
                $this->path = str_replace('.'.$this->format, '', $this->path);
            }
        }
        
        return $this;
    }
    
    /**
     * Sets the query string in the URI, for KHttpUri::getQuery() and KHttpUri::$query.
     * 
     * This will overwrite any previous values.
     * 
     * @param   string|array    The query string to use; for example `foo=bar&baz=dib`.
     * @return  KHttpUri
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
     * @return  string|array    The query string; e.g., `foo=bar&baz=dib`.
     */
    public function getQuery($toArray = false)
    {
		$result = $toArray ? $this->_query : http_build_query($this->_query, '', '&');
		return $result;
    }
    
    /**
     * Sets the KHttpUri::$path array and $format from a string.
     * 
     * This will overwrite any previous values. Also, resets the format based
     * on the final path value.
     * 
     * @param   string  The path string to use; for example,"/foo/bar/baz/dib".  
     * A leading slash will *not* create an empty first element; if the string 
     * has a leading slash, it is ignored.
     * @return  KHttpUri
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
        
        return $this;
    }
    
    
    /**
     * Return a string representation of this URI.
     *
     * @see    get()
     * @return string
     */
    public function __toString()
    {
        return $this->get(self::PART_ALL);
    }
    
    /**
     * Converts an array of path elements into a string.
     * 
     * Does not use urlencode(); instead, only converts characters found in 
     * KHttpUri::$_encode_path.
     * 
     * @param   array The path elements.
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