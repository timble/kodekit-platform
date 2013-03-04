<?php
/**
 * @package     Koowa_Http
 * @subpackage  Messsage
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Http Message Class
 *
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Messsage
 */
abstract class KHttpMessage extends KObject implements KHttpMessageInterface
{
    /**
     * The message headers
     *
     * @var KHttpMessageHeaders
     */
    protected $_headers;

    /**
     * The http version
     *
     * @var string
     */
    protected $_version;

    /**
     * The message content
     *
     * @var string
     */
    protected $_content;

    /**
     * The message content type
     *
     * @var string
     */
    protected $_content_type;

    /**
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KHttpMessage
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Set Headers
        $this->setHeaders($config->headers);

        $this->setVersion($config->version);
        $this->setContent($config->content);
        $this->setContentType($config->content_type);
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
            'version'      => '1.1',
            'content'      => '',
            'content_type' => '',
            'headers'      => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Set the header parameters
     *
     * @param  array $headers
     * @return KHttpMessageInterface
     */
    public function setHeaders($headers)
    {
        $this->_headers = $this->getService('lib://nooku/http.message.headers', array('headers' => $headers));
        return $this;
    }

    /**
     * Get the headers container
     *
     * @param  array $headers
     * @return KHttpMessageHeaders
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version The HTTP protocol version
     * @return KHttpResponse
     */
    public function setVersion($version)
    {
        if ($version != '1.1' && $version != '1.0') {
            throw new \InvalidArgumentException('Not valid or not supported HTTP version: ' . $version);
        }

        $this->_version = $version;
        return $this;
    }

    /**
     * Gets the HTTP protocol version.
     *
     * @return string The HTTP protocol version
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * Sets the response content.
     *
     * Valid types are strings, numbers, and objects that implement a __toString() method.
     *
     * @param mixed $content
     * @throws UnexpectedValueException
     * @return KHttpMessageAbstract
     */
    public function setContent($content)
    {
        if (!is_null($content) && !is_string($content) && !is_numeric($content) && !is_callable(array($content, '__toString')))
        {
            throw new \UnexpectedValueException(
                'The Response content must be a string or object implementing __toString(), "'.gettype($content).'" given.'
            );
        }

        //Cast to a string
        $this->_content = (string) $content;
        return $this;
    }

    /**
     * Get message content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Sets the message content type
     *
     * @param string $type Content type
     * @return KHttpMessageAbstract
     */
    public function setContentType($type)
    {
        $this->_content_type = $type;
        return $this;
    }

    /**
     * Retrieves the message content type
     *
     * @return string Character set
     */
    public function getContentType()
    {
        return $this->_content_type;
    }

    /**
     * Render the message as a string
     *
     * @return string
     */
    public function toString()
    {
        return $this->getContent();
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

    /**
     * Clones the object instance.
     */
    public function __clone()
    {
        $this->_headers = clone $this->_headers;
    }
}