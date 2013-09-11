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
 * Abstract Dispatcher Response
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherResponseAbstract extends ControllerResponse implements DispatcherResponseInterface
{
    /**
     * The transport queue
     *
     * @var	ObjectQueue
     */
    protected $_queue;

    /**
     * Stream resource
     *
     * @var FilesystemStreamInterface
     */
    protected $_stream;

    /**
     * List of transport handlers
     *
     * @var array
     */
    protected $_transports;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Create the transport queue
        $this->_queue = $this->getObject('lib:object.queue');

        //Set the response messages
        $this->_messages = $this->getUser()->getSession()->getContainer('message')->all();

        //Attach the response transport handlers
        $transports = (array) ObjectConfig::unbox($config->transports);

        foreach ($transports as $key => $value)
        {
            if (is_numeric($key)) {
                $this->attachTransport($value);
            } else {
                $this->attachTransport($key, $value);
            }
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config    An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'content'     => 'string://', //Empty content using default string stream protocol
            'transports'  => array('redirect', 'json', 'http'),
        ));

        parent::_initialize($config);
    }

    /**
     * Send the response
     *
     * Iterate through the response transport handlers. If a handler returns TRUE the chain will be stopped.
     *
     * @return boolean  Returns true if the response has been send, otherwise FALSE
     */
    public function send()
    {
        foreach($this->_queue as $transport)
        {
            if($transport instanceof DispatcherResponseTransportInterface)
            {
                if($transport->send($this) === true)
                {
                    //Cleanup and flush output to client
                    if (!function_exists('fastcgi_finish_request'))
                    {
                        if (PHP_SAPI !== 'cli')
                        {
                            for ($i = 0; $i < ob_get_level(); $i++) {
                                ob_end_flush();
                            }

                            flush();
                        }
                    }
                    else fastcgi_finish_request();

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Sets the response content using a stream path
     *
     * Content needs to be of the form "scheme://..." and a wrapper for that scheme need to be registered. If no stream
     * wrappers for that protocol are registered, an exception will be throw.
     *
     * The default wrapper is the string://[content]. This wrapper allows you to pass a string directly to the response
     * transport to send it to the client. See @link http://www.php.net/manual/en/wrappers.php for a list of default
     * PHP stream protocols and wrappers.
     *
     * @param mixed  $content   The content
     * @param string $type      The content type
     * @throws \InvalidArgumentException If the content is not a valid stream or no stream wrapper is registered for the
     *                                   stream protocol
     * @return HttpMessage
     */
    public function setContent($content, $type = null)
    {
        parent::setContent($content);

        $stream = empty($content) ?  'string://' : $content;
        $stream = $this->getObject('lib:filesystem.stream', array('stream' => $stream));

        if(!$stream->isRegistered())
        {
            throw new \InvalidArgumentException(
                'Content is not a valid stream or no stream wrapper is registered for the stream protocol.'
            );

        }

        $this->_stream = $stream;

        return $this;
    }

    /**
     * Get the response content from the stream
     *
     * @return string
     */
    public function getContent()
    {
        $stream = $this->getStream();
        if($stream instanceof FilesystemStreamInterface) {
            $content = $stream->getContent();
        } else {
            $content = parent::getContent();
        }

        return $content;
    }

    /**
     * Sets the response content using a stream
     *
     * @param FilesystemStreamInterface $stream  The stream object
     * @return HttpMessage
     */
    public function setStream(FilesystemStreamInterface $stream)
    {
        $this->_stream = $stream;
        return $this;
    }

    /**
     * Get the stream resource
     *
     * @return FilesystemStreamInterface
     */
    public function getStream()
    {
        return $this->_stream;
    }

    /**
     * Get a transport handler by identifier
     *
     * @param   mixed    $transport    An object that implements ObjectInterface, ObjectIdentifier object
     *                                 or valid identifier string
     * @param   array    $config    An optional associative array of configuration settings
     * @return DispatcherResponseAbstract
     */
    public function getTransport($transport, $config = array())
    {
        //Create the complete identifier if a partial identifier was passed
        if (is_string($transport) && strpos($transport, '.') === false)
        {
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('response', 'transport');
            $identifier->name = $transport;
        }
        else $identifier = $this->getIdentifier($transport);

        if (!isset($this->_transports[$identifier->name]))
        {
            $transport = $this->getObject($identifier, array_merge($config, array('response' => $this)));

            if (!($transport instanceof DispatcherResponseTransportInterface))
            {
                throw new \UnexpectedValueException(
                    "Transport handler $identifier does not implement DispatcherResponseTransportInterface"
                );
            }

            $this->_transports[$transport->getIdentifier()->name] = $transport;
        }
        else $transport = $this->_transports[$identifier->name];

        return $transport;
    }

    /**
     * Attach a transport handler
     *
     * @param   mixed  $transport An object that implements ObjectInterface, ObjectIdentifier object
     *                            or valid identifier string
     * @param   array $config  An optional associative array of configuration settings
     * @return DispatcherResponseAbstract
     */
    public function attachTransport($transport, $config = array())
    {
        if (!($transport instanceof DispatcherResponseTransportInterface)) {
            $transport = $this->getTransport($transport, $config);
        }

        //Enqueue the transport handler in the command chain
        $this->_queue->enqueue($transport, $transport->getPriority());

        return $this;
    }

    /**
     * Returns true if the response is worth caching under any circumstance.
     *
     * Responses that are streamable are considered un cacheable.
     *
     * @link http://tools.ietf.org/html/rfc2616#section-14.9.1
     * @return Boolean true if the response is worth caching, false otherwise
     */
    public function isCacheable()
    {
        if($this->isStreamable()) {
            return false;
        }

        return parent::isCacheable();
    }

    /**
     * Check if the response is streamable
     *
     * All response are considered streamable, only if the Accept-Ranges has a value 'none' the response should not
     * be streamed.
     *
     * @link http://tools.ietf.org/html/rfc2616#section-14.5
     * @return bool
     */
    public function isStreamable()
    {
        if($this->_headers->get('Accept-Ranges', null) !== 'none' && $this->getStream()->getType() == 'file') {
            return true;
        };

        return false;
    }

    /**
     * Check if the response is attachable
     *
     * @return bool
     */
    public function isAttachable()
    {
        if($this->getRequest()->isDownload() || $this->getContentType() == 'application/force-download') {
            return true;
        }

        return false;
    }

    /**
     * Check if the response is downloadable
     *
     * @return bool
     */
    public function isDownloadable()
    {
        if($this->getStream()->getType() == 'file') {
            return true;
        }

        return false;
    }

    /**
     * Deep clone of this instance
     *
     * @return void
     */
    public function __clone()
    {
        parent::__clone();

        $this->_queue  = clone $this->_queue;
    }
}