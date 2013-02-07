<?php
/**
 * @version		$Id: abstract.php 4948 2012-09-03 23:05:48Z johanjanssens $
 * @package		Koowa_Dispatcher
 * @subpackage  Request
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Dispatcher Request Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Request
 */
interface KDispatcherRequestInterface extends KControllerRequestInterface, KServiceInstantiatable
{
    /**
     * Set the request cookies
     *
     * @param  array $cookies
     * @return KDispatcherRequestInterface
     */
    public function setCookies($cookies);

    /**
     * Get the request cookies
     *
     * @return KHttpMessageParameters
     */
    public function getCookies();

    /**
     * Set the request files
     *
     * @param  array $files
     * @return KDispatcherRequestInterface
     */
    public function setFiles($files);

    /**
     * Get the request files
     *
     * @return KHttpMessageParameters
     */
    public function getFiles();

    /**
     * Returns the HTTP referrer.
     *
     * 'referer' a commonly used misspelling word for 'referrer'
     * @see     http://en.wikipedia.org/wiki/HTTP_referrer
     *
     * @param   boolean  $isInternal Only allow internal url's
     * @return  KHttpUrl A KHttpUrl object
     */
    public function getReferrer($isInternal = true);

    /**
     * Returns the client information doing the request
     *
     * @return string $_SERVER['HTTP_USER_AGENT'] or an empty string if it's not supplied in the request
     */
    public function getAgent();

    /**
     * Returns the client IP address.
     *
     * @return string $_SERVER['HTTP_REMOTE_ADDR'] or an empty string if it's not supplied in the request
     */
    public function getAddress();

    /**
     * Returns the base URL from which this request is executed.
     *
     * The base URL never ends with a / and t also includes the script filename (e.g. index.php) if one exists.
     *
     * Suppose this request is instantiated from /mysite on localhost:
     *
     *  * http://localhost/mysite              returns an empty string
     *  * http://localhost/mysite/about        returns '/about'
     *  * http://localhost/mysite/enco%20ded   returns '/enco%20ded'
     *  * http://localhost/mysite/about?var=1  returns '/about'
     *
     * @return  object  A KHttpUrl object
     */
    public function getBaseUrl();

    /**
     * Set the base URL for which the request is executed.
     *
     * @param string $url
     * @return KDispatcherRequest
     */
    public function setBaseUrl($url);

    /**
     * Returns the base url of the request.
     *
     * @return  object  A KHttpUrl object
     */
    public function getBasePath();

    /**
     * Set the base path for which the request is executed.
     *
     * @param string $path
     * @return KDispatcherRequest
     */
    public function setBasePath($path);

    /**
     * Return the request token
     *
     * @return  string  The request token or NULL if no token could be found
     */
    public function getToken();

    /**
     * Return the request format
     *
     * This function tries to find the format by inspecting the accept header and using the accept header with the
     * highest quality. The accept mime-type will be mapped to a format. If the request query contains a 'format'
     * parameter it will be used instead.
     *
     * @param string $default The default format
     * @return  string  The request format or NULL if no format could be found
     */
    public function getFormat($default = 'html');

    /**
     * Associates a format with mime types.
     *
     * @param string       $format    The format
     * @param string|array $mimeTypes The associated mime types (the preferred one must be the first as it will be used
     *                                as the content type)
     * @return KDispatcherRequestInterface
     */
    public function addFormat($format, $mime_types);

    /**
     * Gets a list of languages acceptable by the client browser.
     *
     * @return array Languages ordered in the user browser preferences
     */
    public function getLanguages();

    /**
     * Gets a list of charsets acceptable by the client browser.
     *
     * @return array List of charsets in preferable order
     */
    public function getCharsets();

    /**
     * Checks whether the request is secure or not.
     *
     * @return  string
     */
    public function isSecure();
}