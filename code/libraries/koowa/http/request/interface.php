<?php
/**
 * @version     $Id: response.php 4675 2012-06-03 01:05:49Z johanjanssens $
 * @package     Koowa_Http
 * @subpackage  Request
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Http Request Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Request
 */
interface KHttpRequestInterface extends KHttpMessageInterface
{
    /**
     * Set the method for this request
     *
     * @param  string $method
     * @throws InvalidArgumentException
     * @return KHttpRequest
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
     * @param string|KHttpUrl   $uri
     * @throws InvalidArgumentException If the url is not an instance of KHttpUrl or a string
     * @return KHttpRequest
     */
    public function setUrl($url);

    /**
     * Return the url for this request
     *
     * @return KHttpUrl
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