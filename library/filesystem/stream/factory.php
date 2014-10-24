<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Filesystem Stream Factory
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\FileSystem\Stream\Factory
 */
class FilesystemStreamFactory extends Object implements ObjectSingleton
{
    /**
     * Registered stream
     *
     * @var array
     */
    private $__streams;

    /**
     * A prefix for registered streams
     *
     * @var string
     */
    protected $_stream_prefix = '';

    /**
     * Constructor.
     *
     * @param ObjectConfig $config Configuration options
     */
    public function __construct( ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_stream_prefix = $config->stream_prefix;

        //Auto register streams
        foreach($config->streams as $stream) {
            $this->registerStream($stream);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config Configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'stream_prefix'  => 'nooku-',
            'streams' => array('lib:filesystem.stream.buffer'),
        ));
    }

    /**
     * Create a stream
     *
     * Note that only URLs delimited by "://"" are supported. ":" and ":/" while technically valid URLs, are not. If no
     * stream is registered for the specific scheme a exception will be thrown.
     *
     * @param string         $path       The stream path
     * @param string         $mode      The type of access required for this stream. (see Table 1 of the fopen() reference);
     * @param array|resource $context   Either an array of a resource of type 'stream-context' created with stream_create_context()
     * @param bool           $auto_open IF TRUE automatically open the stream. Default TRUE.
     * @throws \InvalidArgumentException If the url is not valid
     * @throws \RuntimeException         If the stream isn't registered
     * @throws \UnexpectedValueException If the stream object doesn't implement the KFilesystemStreamInterface
     * @throws \RuntimeException         If the stream cannot be opened.
     * @return FilesystemStreamInterface
     */
    public function createStream($path, $mode = 'rb', $context = array())
    {
        $scheme = parse_url($path, PHP_URL_SCHEME);

        //If no scheme is specified fall back to file:// stream
        $name = !empty($scheme) ? $scheme : 'file';

        //If a windows drive letter is passed use file:// stream
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        {
            if(preg_match('#^[a-z]{1}$#i', $name)) {
                $name = 'file';
            }
        }

        //Invalid context
        if (!is_null($context) && !is_array($context) && !is_resource($context) && !get_resource_type($context) == 'stream-context')
        {
            throw new \InvalidArgumentException(sprintf(
                'Context must be an array or a resource of type stream-context; received "%s"', gettype($context)
            ));
        }

        //Stream not supported
        if(!$this->isRegistered($name))
        {
            throw new \RuntimeException(sprintf(
                'Unable to find the filesystem stream "%s" - did you forget to register it ?', $name
            ));
        }

        //Create the options
        if (is_resource($context)) {
            $options = stream_context_get_options($context);
        } else {
            $options = (array) $context;
        }

        //Create the stream
        $identifier = $this->getStream($name);
        $stream     = $this->getObject($identifier, array(
            'path'    => $path,
            'options' => $options,
            'mode'    => $mode
        ));

        if(!$stream instanceof FilesystemStreamInterface)
        {
            throw new \UnexpectedValueException(
                'Stream: '.get_class($stream).' does not implement FilesystemStreamInterface'
            );
        }

        //Automatically open the stream
        try {
            $stream->open();
        } catch (\BadMethodCallException $e) {
            //Do nothing if the stream doesn't support open
        }

        return $stream;
    }

    /**
     * Register a stream
     *
     * Function prevents from registering the stream twice
     * If stream_prefix config option is set, the registered stream will be prefixed and createStream should be called
     * with the prefix.
     *
     * @param string $identifier A stream identifier string
     * @throws \UnexpectedValueException
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function registerStream($identifier)
    {
        $result = false;

        $identifier = $this->getIdentifier($identifier);
        $class      = $this->getObject('manager')->getClass($identifier);

        if(!$class || !array_key_exists(__NAMESPACE__.'\FilesystemStreamInterface', class_implements($class)))
        {
            throw new \UnexpectedValueException(
                'Stream: '.$identifier.' does not implement FilesystemStreamInterface'
            );
        }

        $name = $class::getName();

        if (!empty($name) && !$this->isRegistered($this->_stream_prefix.$name))
        {
            if($result = stream_wrapper_register($this->_stream_prefix.$name, __NAMESPACE__.'\FilesystemStreamAdapter')) {
                $this->__streams[$this->_stream_prefix.$name] = $identifier;
            }
        }

        return $result;
    }

    /**
     * Unregister a stream
     *
     * @param string $identifier A stream object identifier string or stream name
     * @throws \UnexpectedValueException
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function unregisterStream($identifier)
    {
        $result = false;

        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);

            if(!$class || !array_key_exists(__NAMESPACE__.'\FilesystemStreamInterface', class_implements($class)))
            {
                throw new \UnexpectedValueException(
                    'Stream: '.$identifier.' does not implement FilesystemStreamInterface'
                );
            }

            $name = $class::getName();

        }
        else $name = $identifier;

        if (!empty($name) && $this->isRegistered($this->_stream_prefix.$name))
        {
            if($result = stream_wrapper_unregister($this->_stream_prefix.$name)) {
                unset($this->__streams[$this->_stream_prefix.$name]);
            }
        }

        return $result;
    }

    /**
     * Get a registered stream identifier
     *
     * @param string $name The stream name
     * @return string|false The stream identifier
     */
    public function getStream($name)
    {
        $stream = false;

        if($this->isRegistered($name))
        {
            if(isset($this->__streams[$name])) {
                $stream = $this->__streams[$name];
            } else {
                $stream = 'lib:filesystem.stream.'.$name;
            }
        }

        return $stream;
    }

    /**
     * Get a list of all the registered streams
     *
     * @return array
     */
    public function getStreams()
    {
        return stream_get_wrappers();
    }

    /**
     * Check if the stream is registered
     *
     * @param string $identifier A stream object identifier string or stream name
     * @return bool TRUE if the stream is a registered, FALSE otherwise.
     */
    public function isRegistered($identifier)
    {
        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);

            if(!$class || !array_key_exists(__NAMESPACE__.'\FilesystemStreamInterface', class_implements($class)))
            {
                throw new \UnexpectedValueException(
                    'Stream: '.$identifier.' does not implement FilesystemStreamInterface'
                );
            }

            $name  = $class::getName();
        }
        else $name = $identifier;

        $result = in_array($name, $this->getStreams());
        return $result;
    }

    /**
     * Check if the stream for a registered protocol is supported
     *
     * @param string $identifier A stream object identifier string or stream name
     * @return bool TRUE if the stream is a registered and is supported, FALSE otherwise.
     */
    public function isSupported($identifier)
    {
        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);
            $name       = $class::getName();
        }
        else $name = $identifier;

        //Check if the stream is registered
        $result = $this->isRegistered($name);

        //Check if the stream is supported
        if(!ini_get('allow_url_fopen'))
        {
            if(in_array(array('ftp', 'sftp', 'http', 'https'), $name)) {
                $result = false;
            }
        }

        return $result;
    }
}