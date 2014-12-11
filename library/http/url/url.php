<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Http Url
 *
 * This class helps you to create and manipulate urls, including query strings and path elements. It does so by splitting
 * up the pieces of the url and allowing you modify them individually; you can then then fetch them as a single url
 * string.
 *
 * The following is a simple example. Say that the page address is currently
 * `http://anonymous::guest@example.com/path/to/index.php/foo/bar?baz=dib#anchor`.
 *
 * You can use HttpUrl to parse this complex string very easily:
 *
 * <code>
 * <?php
 *     // Create a url object;
 *
 *     $url = 'http://anonymous:guest@example.com/path/to/index.php/foo/bar.xml?baz=dib#anchor'
 *     $url = ObjectManager::getInstance()->getObject('lib:http.url', array('url' => $url) );
 *
 *     // the $ur properties are ...
 *     //
 *     // $url->scheme   => 'http'
 *     // $url->host     => 'example.com'
 *     // $url->user     => 'anonymous'
 *     // $url->pass     => 'guest'
 *     // $url->path     => array('path', 'to', 'index.php', 'foo', 'bar.xml')
 *     // $url->query    => array('baz' => 'dib')
 *     // $url->fragment => 'anchor'
 * ?>
 * </code>
 *
 * Now that we have imported the url and had it parsed automatically, we can modify the component parts, then fetch a
 * new url string.
 *
 * <code>
 * <?php
 *     // change to 'https://'
 *     $url->scheme = 'https';
 *
 *     // remove the username and password
 *     $url->user = '';
 *     $url->pass = '';
 *
 *     // change the value of 'baz' to 'zab'
 *     $url->setQuery('baz', 'zab');
 *
 *     // add a new query element called 'zim' with a value of 'gir'
 *     $url->query['zim'] = 'gir';
 *
 *     // reset the path to something else entirely.
 *     $url->setPath('/something/else/entirely.php');
 *
 *     // Get the full URL to get the scheme and host
 *     $full_url = $url->toString(true);
 *
 *     // the $full_url string is:
 *     // https://example.com/something/else/entirely.php?baz=zab&zim=gir#anchor
 * ?>
 * </code>
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Http
 */
class HttpUrl extends Object implements HttpUrlInterface
{
    /**
     * The url parts
     *
     * @see get()
     */
    const SCHEME   = 1;
    const USER     = 2;
    const PASS     = 4;
    const HOST     = 8;
    const PORT     = 16;
    const PATH     = 32;
    const QUERY    = 64;
    const FRAGMENT = 128;

    const USERINFO  = 6;   //User info
    const AUTHORITY = 31;  //Authority
    const BASE      = 63;  //Hierarchical part
    const FULL      = 255; //Complete url

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
     * The fragment aka anchor portion (for example, the "foo" in "#foo").
     *
     * @var string
     */
    public $fragment = '';

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
     * The path portion (for example, 'path/to/index.php').
     *
     * @var array
     *
     * @see setPath()
     * @see getPath()
     */
    protected $_path = '';

    /**
     * Url-encode only these characters in path elements.
     *
     * Characters are ' ' (space), '/', '?', '&', and '#'.
     *
     * @var array
     */
    protected $_encode_path = array(
        ' ' => '+',
        '/' => '%2F',
        '?' => '%3F',
        '&' => '%26',
        '#' => '%23',
    );

    /**
     * Escape '&' to '&amp;'
     *
     * @var boolean
     *
     * @see getQuery()
     * @see getUrl()
     */
    protected $_escape;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the escaping behavior
        $this->setEscape($config->escape);

        //Set the url
        $this->setUrl(ObjectConfig::unbox($config->url));
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation
     *
     * @param   ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'url'    => '',
            'escape' => false
        ));

        parent::_initialize($config);
    }

    /**
     * Parse the url from a string
     *
     * Partial URLs are also accepted. setUrl() tries its best to parse them correctly. Function also accepts an
     * associative array like parse_url returns.
     *
     * @param   string|array  $url Part(s) of an URL in form of a string or associative array like parse_url() returns
     * @throws  \UnexpectedValueException If the url is not an array a string or cannot be casted to one.
     * @return  HttpUrl
     * @see     parse_url()
     */
    public function setUrl($url)
    {
        if (!is_string($url) && !is_numeric($url) && !is_callable(array($url, '__toString')) && !is_array($url))
        {
            throw new \UnexpectedValueException(
                'The url must be a array as returned by parse_url() a string or object implementing __toString(), "'.gettype($url).'" given.'
            );
        }

        if(!is_array($url)) {
            $parts = parse_url((string) $url);
        } else {
            $parts = $url;
        }

        foreach ($parts as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Get the scheme part of the URL
     *
     * @return string|null
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Set the URL scheme
     *
     * @param  string $scheme
     * @return  HttpUrl
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * Get the URL user
     *
     * @return string|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the URL user
     *
     * @param  string $user
     * @return HttpUrl
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the URL password
     *
     * @return string|null
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set the URL password
     *
     * @param  string $user
     * @return HttpUrl
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * Get the URL host
     *
     * @return string|null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the URL Host
     *
     * @param  string $host
     * @return HttpUrl
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get the URL port
     *
     * @return integer|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the port part of the URL
     *
     * @param  integer $port
     * @return HttpUrl
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Returns the path portion as a string or array
     *
     * @param     boolean $toArray If TRUE return an array. Default FALSE
     * @return  string|array The path string; e.g., `path/to/site`.
     */
    public function getPath($toArray = false)
    {
        $result = $toArray ? $this->_path : $this->_pathEncode($this->_path);
        return $result;
    }

    /**
     * Sets the HttpUrl::$path array
     *
     * This will overwrite any previous values.
     *
     * @param   string|array  $path The path string or array of elements to use; for example,"/foo/bar/baz/dib".
     *                              A leading slash will *not* create an empty first element; if the string has a
     *                              leading slash, it is ignored.
     * @return  HttpUrl
     */
    public function setPath($path)
    {
        if (is_string($path))
        {
            if (!empty($path)) {
                $path = explode('/', $path);
            } else {
                $path = array();
            }
        }

        foreach ($path as $key => $val) {
            $path[$key] = urldecode($val);
        }

        $this->_path = $path;
        return $this;
    }

    /**
     * Returns the query portion as a string or array
     *
     * @param   boolean      $toArray If TRUE return an array. Default FALSE
     * @param   boolean|null $escape  If TRUE escapes '&' to '&amp;' for xml compliance. If NULL use the default.
     * @return  string|array The query string; e.g., `foo=bar&baz=dib`.
     */
    public function getQuery($toArray = false, $escape = null)
    {
        $result = $this->_query;
        $escape = isset($escape) ? (bool) $escape : $this->getEscape();

        if(!$toArray)
        {
            $result =  http_build_query($this->_query, '', $escape ? '&amp;' : '&');

            // We replace the + used for spaces by http_build_query with the more standard %20.
            $result = str_replace('+', '%20', $result);
        }

        return $result;
    }

    /**
     * Sets the query string
     *
     * If an string is provided, will decode the string to an array of parameters. Array values will be represented in
     * the query string using PHP's common square bracket notation.
     *
     * @param   string|array  $query  The query string to use; for example `foo=bar&baz=dib`.
     * @param   boolean       $merge  If TRUE the data in $query will be merged instead of replaced. Default FALSE.
     * @return  HttpUrl
     */
    public function setQuery($query, $merge = false)
    {
        $result = $query;
        if (!is_array($query))
        {
            if (strpos($query, '&amp;') !== false) {
                $query = str_replace('&amp;', '&', $query);
            }

            //Set the query vars
            parse_str($query, $result);
        }

        if ($merge) {
            $this->_query = array_merge($this->_query, $result);
        } else {
            $this->_query = $result;
        }

        return $this;
    }

    /**
     * Get the URL fragment
     *
     * @return string|null
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Set the URL fragment part
     *
     * @param  string $fragment
     * @return HttpUrl
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;
        return $this;
    }

    /**
     * Enable/disable URL escaping
     *
     * @param bool $escape
     * @return HttpUrl
     */
    public function setEscape($escape)
    {
        $this->_escape = (bool) $escape;
        return $this;
    }

    /**
     * Get the escape setting
     *
     * @return bool
     */
    public function getEscape()
    {
        return $this->_escape;
    }

    /**
     * Build the url from an array
     *
     * @param   string  $array Associative array like parse_url() returns.
     * @return  HttpUrl
     * @see     parse_url()
     */
    public static function fromArray(array $parts)
    {
        $url = new static(new ObjectConfig(array('url' => $parts)));
        return $url;
    }

    /**
     * Build the url from a string
     *
     * Partial URLs are also accepted. fromString tries its best to parse them correctly.
     *
     * @param   string  $url
     * @throws  \UnexpectedValueException If the url is not a string or cannot be casted to one.
     * @return  HttpUrl
     * @see     parse_url()
     */
    public static function fromString($url)
    {
        if (!is_string($url) && !is_numeric($url) && !is_callable(array($url, '__toString')))
        {
            throw new \UnexpectedValueException(
                'The url must be a string or object implementing __toString(), "'.gettype($url).'" given.'
            );
        }

        $url = self::fromArray(parse_url((string) $url));
        return $url;
    }

    /**
     * Get the full url, of the format scheme://user:pass@host/path?query#fragment';
     *
     * @param integer      $parts   A bitmask of binary or'ed HTTP_URL constants; FULL is the default
     * @param boolean|null $escape  If TRUE escapes '&' to '&amp;' for xml compliance. If NULL use the default.
     * @return  string
     */
    public function toString($parts = self::FULL, $escape = null)
    {
        $url = '';
        $escape = isset($escape) ? (bool) $escape : $this->getEscape();

        //Add the scheme
        if (($parts & self::SCHEME) && !empty($this->scheme)) {
            $url .= urlencode($this->scheme) . '://';
        }

        //Add the username and password
        if (($parts & self::USER) && !empty($this->user))
        {
            $url .= urlencode($this->user);
            if (($parts & self::PASS) && !empty($this->pass)) {
                $url .= ':' . urlencode($this->pass);
            }

            $url .= '@';
        }

        // Add the host and port, if any.
        if (($parts & self::HOST) && !empty($this->host))
        {
            $url .= urlencode($this->host);

            if (($parts & self::PORT) && !empty($this->port)) {
                $url .= ':' . (int)$this->port;
            }
        }

        // Add the rest of the url. we use trim() instead of empty() on string
        // elements to allow for string-zero values.
        if (($parts & self::PATH) && !empty($this->_path))
        {
            $url .= $this->getPath();
        }

        if (($parts & self::QUERY) && !empty($this->_query))
        {
            if($query = $this->getQuery(false, $escape)) {
                $url .= '?' . $query;
            }
        }

        if (($parts & self::FRAGMENT) && trim($this->fragment) !== '') {
            $url .= '#' . urlencode($this->fragment);
        }

        return $url;
    }

    /**
     * Check if two url's are equal
     *
     * @param HttpUrlInterface $url
     * @return Boolean
     */
    public function equals(HttpUrlInterface $url)
    {
        $parts = array('scheme', 'host', 'port', 'path', 'query', 'fragment');

        foreach($parts as $part)
        {
            if($this->{$part} != $url->{$part}) {
                return false;
            }
        }

        return true;
    }

    /**
     * Converts an array of path elements into a string.
     *
     * Does not use urlencode(); instead, only converts characters found in HttpUrl::$_encode_path.
     *
     * @param  array $spec The path elements.
     * @return string A url path string.
     */
    protected function _pathEncode($spec)
    {
        if (is_string($spec)) {
            $spec = explode('/', $spec);
        }

        $keys = array_keys($this->_encode_path);
        $vals = array_values($this->_encode_path);

        $out = array();
        foreach ((array)$spec as $elem) {
            $out[] = str_replace($keys, $vals, $elem);
        }

        return implode('/', $out);
    }

    /**
     * Set the virtual properties.
     *
     * @param   string $key   The virtual property to set.
     * @param   string $value Set the virtual property to this value.
     */
    public function __set($key, $value)
    {
        if ($key == 'query') {
            $this->setQuery($value);
        }

        if ($key == 'path') {
            $this->setPath($value);
        }
    }

    /**
     * Get the virtual properties by reference so that they appears to be public
     *
     * @param   string  $key The virtual property to return.
     * @return  mixed   The value of the virtual property.
     */
    public function &__get($key)
    {
        if ($key == 'query') {
            return $this->_query;
        }

        if ($key == 'path') {
            return $this->_path;
        }

        return null;
    }

    /**
     * Allow PHP casting of this object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}