<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Http Cookie
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Http
 */
class HttpCookie extends Object implements HttpCookieInterface
{
    /**
     * The name of the cookie
     *
     * @var string
     */
    protected $_name;

    /**
     * The value of the cookie.
     *
     * This value is stored on the clients computer; do not store sensitive information
     *
     * @var string
     */
    public $value;

    /**
     * The domain that the cookie is available to.
     *
     * Setting the domain to 'www.example.com' will make the cookie available in the www subdomain and higher subdomains.
     * Cookies available to a lower domain, such as 'example.com' will be available to higher subdomains, such as
     * 'www.example.com'
     *
     * @var string
     */
    public $domain;

    /**
     * The time the cookie expires.
     *
     * This is a Unix timestamp so is in number of seconds since the epoch.
     *
     * @var integer
     */
    protected $_expire;

    /**
     * The path on the server in which the cookie will be available on
     *
     * If set to '/', the cookie will be available within the entire domain. If set to '/foo/', the cookie will only be
     * available within the /foo/ directory and all sub-directories such as /foo/bar/ of domain. The default value is
     * the current directory that the cookie is being set in.
     *
     * @var string
     */
    public $path;

    /**
     * When TRUE indicates that the cookie should only be transmitted over a secure HTTPS connection from the client.
     *
     * @var string
     */
    public $secure;

    /**
     * When TRUE the cookie will be made accessible only through the HTTP protocol. This means that the cookie won't be
     * accessible by scripting languages, such as JavaScript.
     *
     * @var string
     */
    public $http_only;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return HttpCookie
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the config values
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
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
            'name'      => '',
            'value'     => null,
            'domain'    => null,
            'expire'    => 0,
            'path'      => '/',
            'secure'    => false,
            'http_only' => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Set the cookie name
     *
     * @param string $name The name of the cookie
     * @throws \InvalidArgumentException    If the cookie name is not valid or is empty
     * @return HttpCookie
     */
    public function setName($name)
    {
        //Check for invalid cookie name (from PHP source code)
        if (preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new \InvalidArgumentException(sprintf('The cookie name "%s" contains invalid characters.', $name));
        }

        //Check for empty cookie name
        if (empty($name)) {
            throw new \InvalidArgumentException('The cookie name cannot be empty.');
        }

        $this->_name = $name;
        return $this;
    }

    /**
     * Set the cookie expiration time
     *
     * @param integer|string|\DateTime $expire The expiration time of the cookie
     * @throws \InvalidArgumentException    If the cookie expiration time is not valid
     * @return HttpCookie
     */
    public function setExpire($expire)
    {
        // Convert expiration time to a Unix timestamp
        if ($expire instanceof \DateTime) {
            $expire = $expire->format('U');
        }

        if (!is_numeric($expire))
        {
            $expire = strtotime($expire);

            if ($expire === false || $expire === -1) {
                throw new \InvalidArgumentException('The cookie expiration time is not valid.');
            }
        }

        $this->_expire = $expire;
        return $this;
    }

    /**
     * Checks whether the cookie should only be transmitted over a secure HTTPS connection from the client.
     *
     * @return bool
     */
    public function isSecure()
    {
        return (bool)$this->_secure;
    }

    /**
     * Checks whether the cookie will be made accessible only through the HTTP protocol.
     *
     * @return bool
     */
    public function isHttpOnly()
    {
        return (bool)$this->_http_only;
    }

    /**
     * Whether this cookie is about to be cleared
     *
     * @return bool
     */
    public function isCleared()
    {
        return (bool)($this->_expire < time());
    }

    /**
     * Return a string representation of the cookie
     *
     * @return string
     */
    public function toString()
    {
        $str = urlencode($this->name) . '=';

        if ((string)$this->value !== '' )
        {
            $str .= urlencode($this->value);

            if ($this->expire !== 0) {
                $str .= '; expires=' . gmdate(\DateTime::COOKIE, $this->expire);
            }
        }
        else $str .= 'deleted; expires=' . gmdate(\DateTime::COOKIE, time() - 31536001);

        if ($this->path !== '/') {
            $str .= '; path=' . $this->path;
        }

        if ($this->domain !== null) {
            $str .= '; domain=' . $this->domain;
        }

        if ($this->isSecure() === true) {
            $str .= '; secure';
        }

        if ($this->isHttpOnly() === true) {
            $str .= '; httponly';
        }

        return $str;
    }

    /**
     * Set a cookie attribute by key
     *
     * @param   string $key   The key name.
     * @param   mixed  $value The value for the key
     * @return  void
     */
    public function __set($key, $value)
    {
        if ($key == 'name') {
            $this->setName($value);
        }

        if ($key == 'expire') {
            $this->setExpire($value);
        }
    }

    /**
     * Get a cookie attribute by key
     *
     * @param   string $key   The key name.
     * @return  string $value The corresponding value.
     */
    public function &__get($key)
    {
        $result = null;

        if ($key == 'name') {
            $result = $this->_name;
        }

        if ($key == 'expire') {
            $result = $this->_expire;
        }

        return $result;
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