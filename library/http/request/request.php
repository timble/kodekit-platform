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
 * Http Request
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Http
 * @link    http://www.w3.org/Protocols/rfc2616/rfc2616-sec5.html#sec5
 */
class HttpRequest extends HttpMessage implements HttpRequestInterface
{
    // Methods
    const GET     = 'GET';
    const POST    = 'POST';
    const PUT     = 'PUT';
    const DELETE  = 'DELETE';
    const PATCH   = 'PATCH';
    const HEAD    = 'HEAD';
    const OPTIONS = 'OPTIONS';
    const TRACE   = 'TRACE';
    const CONNECT = 'CONNECT';

    /**
     * The request method
     *
     * @var string
     */
    protected $_method;

    /**
     * URL of the request regardless of the server
     *
     * @var HttpUrl
     */
    protected $_url;

    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return HttpResponse
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if(!empty($config->url)) {
            $this->setUrl($config->url);
        }

        if(!empty($config->method)) {
            $this->setMethod($config->method);
        }
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'method'  => self::GET,
            'url'     => '',
            'headers' => array()
        ));

        parent::_initialize($config);
    }

    /**
     * Set the header parameters
     *
     * @param  array $headers
     * @return HttpRequest
     */
    public function setHeaders($headers)
    {
        $this->_headers = $this->getObject('lib:http.request.headers', array('headers' => $headers));
        return $this;
    }

    /**
     * Set the method for this request
     *
     * @param  string $method
     * @throws \InvalidArgumentException
     * @return HttpRequest
     */
    public function setMethod($method)
    {
        $method = strtoupper($method);
        if (!defined('static::'.$method)) {
            throw new \InvalidArgumentException('Invalid HTTP method passed');
        }

        $this->_method = $method;
        return $this;
    }

    /**
     * Return the method for this request
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Set the url for this request
     *
     * @param string|array  $url Part(s) of an URL in form of a string or associative array like parse_url() returns
     * @return HttpRequest
     */
    public function setUrl($url)
    {
        $this->_url = $this->getObject('lib:http.url', array('url' => $url));
        return $this;
    }

    /**
     * Return the url for this request
     *
     * @return HttpUrl
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * Is this an OPTIONS method request?
     *
     * @return bool
     */
    public function isOptions()
    {
        return ($this->_method === self::OPTIONS);
    }

    /**
     * Is this a GET method request?
     *
     * @return bool
     */
    public function isGet()
    {
        return ($this->getMethod() === self::GET);
    }

    /**
     * Is this a HEAD method request?
     *
     * @return bool
     */
    public function isHead()
    {
        return ($this->getMethod() === self::HEAD);
    }

    /**
     * Is this a POST method request?
     *
     * @return bool
     */
    public function isPost()
    {
        return ($this->getMethod() === self::POST);
    }

    /**
     * Is this a PUT method request?
     *
     * @return bool
     */
    public function isPut()
    {
        return ($this->getMethod() === self::PUT);
    }

    /**
     * Is this a DELETE method request?
     *
     * @return bool
     */
    public function isDelete()
    {
        return ($this->getMethod() === self::DELETE);
    }

    /**
     * Is this a TRACE method request?
     *
     * @return bool
     */
    public function isTrace()
    {
        return ($this->getMethod() === self::TRACE);
    }

    /**
     * Is this a CONNECT method request?
     *
     * @return bool
     */
    public function isConnect()
    {
        return ($this->getMethod() === self::CONNECT);
    }

    /*
     * Is this a PATCH method request?
     *
     * @return bool
     */
    public function isPatch()
    {
        return ($this->getMethod() === self::PATCH);
    }

    /**
     * Is the request a Javascript XMLHttpRequest?
     *
     * @return boolean
     */
    public function isAjax()
    {
        $header = $this->_headers->get('X-Requested-With');
        return false !== $header && $header == 'XMLHttpRequest';
    }

    /**
     * Is this a Flash request?
     *
     * @return boolean
     */
    public function isFlash()
    {
        $header = $this->_headers->get('User-Agent');
        return $header !== false && stristr($header, ' flash') || $this->_headers->has('X-Flash-Version');
    }

    /**
     * Is this a safe request?
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.1.1
     * @return boolean
     */
    public function isSafe()
    {
        return $this->isGet() || $this->isHead() || $this->isOptions();
    }

    /**
     * Render entire request as HTTP request string
     *
     * @return string
     */
    public function toString()
    {
        $request = sprintf('%s %s HTTP/%s', $this->getMethod(), (string) $this->getUrl(), $this->getVersion());

        $str = trim($request) . "\r\n";
        $str .= $this->getHeaders();
        $str .= "\r\n";
        $str .= $this->getContent();
        return $str;
    }

    /**
     * Deep clone of this instance
     *
     * @return void
     */
    public function __clone()
    {
        parent::__clone();

        if($this->_url instanceof HttpUrl) {
            $this->_url = clone $this->_url;
        }
    }
}