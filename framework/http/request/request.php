<?php
/**
 * @package     Koowa_Http
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * HTTP Request Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @link        http://www.w3.org/Protocols/rfc2616/rfc2616-sec5.html#sec5
 */
class KHttpRequest extends KHttpMessage implements KHttpRequestInterface
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
     * @var KHttpUrl
     */
    protected $_url;

    /**
     * Constructor
     *
     * @param KConfig $config  An optional KConfig object with configuration options
     * @return KHttpResponse
     */
    public function __construct(KConfig $config)
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
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
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
     * @return KHttpRequest
     */
    public function setHeaders($headers)
    {
        $this->_headers = $this->getService('lib://nooku/http.request.headers', array('headers' => $headers));
        return $this;
    }

    /**
     * Set the method for this request
     *
     * @param  string $method
     * @throws \InvalidArgumentException
     * @return KHttpRequest
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
     * @param string|KHttpUrl   $uri
     * @throws \InvalidArgumentException If the url is not an instance of KHttpUrl or a string
     * @return KHttpRequest
     */
    public function setUrl($url)
    {
        if (!$url instanceof KHttpUrlInterface || !is_string($url)) {
            throw new \InvalidArgumentException('Url must be an instance of KHttpUrl or a string');
        }

        if (is_string($url)) {
            $url = $this->getService('lib://nooku/http.url', array('url' => $url));
        }

        $this->_url = $url;
        return $this;
    }

    /**
     * Return the url for this request
     *
     * @return KHttpUrl
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
}