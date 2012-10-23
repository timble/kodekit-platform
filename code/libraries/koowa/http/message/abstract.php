<?php
/**
 * @version     $Id$
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
class KHttpMessageAbstract extends KObject implements KHttpMessageInterface
{
    /**
     * The message headers
     *
     * @var KHttpMessageHeaders
     */
    public $headers;

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
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KHttpMessage
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->setVersion($config->version);
        $this->setContent($config->content);
        $this->setHeaders($config->headers);
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
            'version' => '1.1',
            'content' => '',
            'headers' => $this->getService('koowa:http.message.headers')
        ));

        parent::_initialize($config);
    }

    /**
     * Set the headers container
     *
     * @param  KHttpHeaders $headers
     * @return KHttpMessage
     */
    public function setHeaders(KHttpMessageHeaders $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version The HTTP protocol version
     * @return KHttpResponse
     */
    public function setVersion($version)
    {
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
        if (!is_string($content) && !is_numeric($content) && !is_callable(array($content, '__toString'))) {
            throw new \UnexpectedValueException(
                'The Response content must be a string or object implementing __toString(), "'.gettype($content).'" given.'
            );
        }

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
     * Render the message as a string
     *
     * @return string
     */
    public function __toString()
    {
        $request = $this->getContent();
        return $request;
    }

    /**
     * Clones the object instance.
     */
    public function __clone()
    {
        $this->headers = clone $this->headers;
    }
}