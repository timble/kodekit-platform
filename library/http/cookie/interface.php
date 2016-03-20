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
 * Http Cookie Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Http
 */
interface HttpCookieInterface
{
    /**
     * Set the cookie name
     *
     * @param string $name The name of the cookie
     * @throws \InvalidArgumentException    If the cookie name is not valid or is empty
     * @return HttpCookie
     */
    public function setName($name);

    /**
     * Set the cookie expiration time
     *
     * @param integer|string|\DateTime $expire The expiration time of the cookie
     * @throws \InvalidArgumentException    If the cookie expiration time is not valid
     * @return HttpCookie
     */
    public function setExpire($expire);

    /**
     * Set the cookie path
     *
     * @param string $path The cookie path
     * @return HttpCookie
     */
    public function setPath($path);

    /**
     * Checks whether the cookie should only be transmitted over a secure HTTPS connection from the client.
     *
     * @return bool
     */
    public function isSecure();

    /**
     * Checks whether the cookie will be made accessible only through the HTTP protocol.
     *
     * @return bool
     */
    public function isHttpOnly();

    /**
     * Whether this cookie is about to be cleared
     *
     * @return bool
     */
    public function isCleared();

    /**
     * Return a string representation of the cookie
     *
     * @return string
     */
    public function toString();
}