<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Http Response Headers
 *
 * Container class that handles the aggregations of HTTP headers as a collection
 *
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.2
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Http
 */
class HttpResponseHeaders extends HttpMessageHeaders
{
    /**
     * A list of response cookies
     *
     * @var array
     */
    protected $_cookies = array();

    /**
     * Add a cookie.
     *
     * @param HttpCookie $cookie
     */
    public function addCookie(HttpCookie $cookie)
    {
        $this->_cookies[$cookie->domain][(string) $cookie->path][$cookie->name] = $cookie;
    }

    /**
     * Removes a cookie from the array, but does not unset it in the browser
     *
     * @param string $name
     * @param mixed  $path
     * @param string $domain
     */
    public function removeCookie($name, $path = '/', $domain = null)
    {
        //Ensure we have a valid path
        if (empty($path)) {
            $path = '/';
        }

        //Force to string to allow passing objects
        $path = (string) $path;

        unset($this->_cookies[$domain][$path][$name]);

        if (empty($this->_cookies[$domain][$path]))
        {
            unset($this->_cookies[$domain][$path]);

            if (empty($this->_cookies[$domain])) {
                unset($this->_cookies[$domain]);
            }
        }
    }

    /**
     * Clears a cookie in the browser
     *
     * @param string $name
     * @param string $path
     * @param string $domain
     */
    public function clearCookie($name, $path = '/', $domain = null)
    {
        $cookie = $this->getObject('lib:http.cookie', array(
            'name'   => $name,
            'path'   => $path,
            'domain' => $domain,
        ));

        $this->addCookie($cookie);
    }

    /**
     * Returns an array with all cookies
     *
     * @return array
     */
    public function getCookies()
    {
        $result = array();
        foreach ($this->_cookies as $path)
        {
            foreach ($path as $cookies)
            {
                foreach ($cookies as $cookie) {
                    $result[] = $cookie;
                }
            }
        }

        return $result;
    }

    /**
     * Returns the headers as a string.
     *
     * @return string The headers
     */
    public function toString()
    {
        $cookies = '';
        foreach ($this->getCookies() as $cookie) {
            $cookies .= 'Set-Cookie: '.$cookie."\r\n";
        }

        return parent::toString().$cookies;
    }
}