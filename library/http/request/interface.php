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
 * Http Request Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Http
 */
interface HttpRequestInterface extends HttpMessageInterface
{
    /**
     * Set the method for this request
     *
     * @param  string $method
     * @throws \InvalidArgumentException
     * @return HttpRequest
     */
    public function setMethod($method);

    /**
     * Return the method for this request
     *
     * @return string
     */
    public function getMethod();

    /**
     * Set the url for this request
     *
     * @param string|HttpUrl   $uri
     * @throws \InvalidArgumentException If the url is not an instance of HttpUrl or a string
     * @return HttpRequest
     */
    public function setUrl($url);

    /**
     * Return the url for this request
     *
     * @return HttpUrl
     */
    public function getUrl();

    /**
     * Is this an OPTIONS method request?
     *
     * @return bool
     */
    public function isOptions();

    /**
     * Is this a GET method request?
     *
     * @return bool
     */
    public function isGet();

    /**
     * Is this a HEAD method request?
     *
     * @return bool
     */
    public function isHead();

    /**
     * Is this a POST method request?
     *
     * @return bool
     */
    public function isPost();

    /**
     * Is this a PUT method request?
     *
     * @return bool
     */
    public function isPut();

    /**
     * Is this a DELETE method request?
     *
     * @return bool
     */
    public function isDelete();

    /**
     * Is this a TRACE method request?
     *
     * @return bool
     */
    public function isTrace();

    /**
     * Is this a CONNECT method request?
     *
     * @return bool
     */
    public function isConnect();

    /*
     * Is this a PATCH method request?
     *
     * @return bool
     */
    public function isPatch();

    /**
     * Is the request an ajax request
     *
     * @return boolean
     */
    public function isAjax();

    /**
     * Is the request a flash request
     *
     * @return boolean
     */
    public function isFlash();
}