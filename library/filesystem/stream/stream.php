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
 * FileSystem Stream
 *
 * The filesystem stream is an object oriented wrapper for the the PHP file system API. It wraps the file resource
 * returned by @see fopen().
 *
 * @link http://www.php.net/manual/en/ref.filesystem.php
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
class FilesystemStream extends Object implements FilesystemStreamInterface
{
    /**
     * Stream resource
     *
     * @var resource
     */
    protected $_resource;

    /**
     * Stream size
     *
     * @var int Size of the stream contents in bytes
     */
    protected $_size;

    /**
     * Stream data
     *
     * @var array The stream metadata
     */
    protected $_data;

    /**
     * Stream context params
     *
     * @var array
     */
    protected $_context;

    /**
     * Stream filters
     *
     * @var array List of the attached stream filters
     */
    protected $_filters;

    /**
     * Lookup table of readable and writeable stream types
     *
     * @var array
     */
    protected static $modes = array(
        'read' => array(
            'r' => true, 'w+' => true, 'r+' => true, 'x+' => true, 'c+' => true,
            'rb' => true, 'w+b' => true, 'r+b' => true, 'x+b' => true, 'c+b' => true,
            'rt' => true, 'w+t' => true, 'r+t' => true, 'x+t' => true, 'c+t' => true, 'a+' => true
        ),
        'write' => array(
            'w' => true, 'w+' => true, 'rw' => true, 'r+' => true, 'x+' => true, 'c+' => true,
            'wb' => true, 'w+b' => true, 'r+b' => true, 'x+b' => true, 'c+b' => true,
            'w+t' => true, 'r+t' => true, 'x+t' => true, 'c+t' => true, 'a' => true, 'a+' => true
        )
    );

    /**
     * Constructor.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Register stream wrappers
        foreach($config->wrappers as $key => $wrapper)
        {
            if (is_numeric($key)) {
                $this->registerWrapper($wrapper);
            } else {
                $this->registerWrapper($key, $wrapper);
            }
        }

        //Create or set the context
        $this->setContext($config->params);

        //Attach stream filters
        foreach($config->filters as $key => $filter)
        {
            if (is_numeric($key)) {
                $this->attachFilter($filter);
            } else {
                $this->attachFilter($key, $filter);
            }
        }
    }

    /**
     * Closes the stream when object is destructed
     *
     * @return void
     */
    public function __destruct()
    {
        $this->close();
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
            'mode'    => 'rb',
            'params'  => array(
                'options' => array()
            ),
            'filters'  => array(),
            'wrappers' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Set the stream that is wrapped by the object
     *
     * If the stream is not an object we will try to open it in read-only mode.
     *
     * @param resource|string $stream Stream path or resource
     * @param string          $mode   The mode to open the stream with
     * @return Returns a file pointer resource on success, or FALSE on error.
     */
    public function open($stream, $mode = 'rb')
    {
        //make sure the existing stream is closed before opening new one.
        $this->close();

        if(!is_resource($stream)) {
            $this->_resource = fopen($stream, $mode, false, $this->getContext());
        } else {
            $this->_resource = $stream;
        }

        return $this;
    }

    /**
     * Seek to a position in the stream
     *
     * @param int $offset Stream offset
     * @param int $whence Where the offset is applied
     * @return bool Returns TRUE on success or FALSE on failure
     * @link   http://www.php.net/manual/en/function.fseek.php
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->isSeekable() ? fseek($this->_resource, $offset, $whence) === 0 : false;
    }

    /**
     * Returns the current position of the stream read/write pointer
     *
     * @return int|bool Returns the position of the file pointer or false on error
     */
    public function peek()
    {
        return ftell($this->_resource);
    }

    /**
     * Read data from the stream advance the pointer
     *
     * @param int $length Up to length number of bytes read.
     * @return string|bool Returns the data read from the stream or FALSE on failure or EOF
     */
    public function read($length = '8192')
    {
        return fread($this->_resource, $length);
    }

    /**
     * Write data to the stream
     *
     * @param string $string The string that is to be written.
     * @return int|bool Returns the number of bytes written to the stream on success or FALSE on failure.
     */
    public function write($string)
    {
        // Reset the size
        $this->_size = null;
        return fwrite($this->_resource, $string);
    }

    /**
     * Read data from the stream to another stream
     *
     * @param resource $stream The stream resource to copy the data too
     * @param int $length Up to length number of bytes read.
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public function copy($stream, $length)
    {
        return fwrite($stream, $this->read($length));
    }

    /**
     * Flush the data from the stream to the output buffer
     *
     * @param int $size   The chunk size in bytes to use when flushing. Default is 8Kb
     * @param int $range  The total length of the stream to flush, if -1 the stream will be flushed until eof. The limit
     *                    should lie within the total size of the stream.
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public function flush($length = '8192', $range = -1)
    {
        $output = fopen('php://output', 'wb');
        $range  = $range < 0 ? $this->getSize() : $range;

        //Send data chunk
        while (!$this->eof() && $this->peek() <= $range) {
            $this->copy($output, $length);
        }

        fclose($output);
        return true;
    }

    /**
     * Rewind to the beginning of the stream
     *
     * @return bool Returns true on success or false on failure
     */
    public function rewind()
    {
        if($this->isSeekable()) {
           return $this->seek(0);
        }

        return false;
    }

    /**
     * Check if the internal pointer has reached the end of the stream.
     *
     * @return bool Returns TRUE if the internal pointer is valid
     */
    public function eof()
    {
        return feof($this->_resource);
    }

    /**
     * Close the underlying stream
     *
     * @return FilesystemStreamInterface
     */
    public function close()
    {
        if (is_resource($this->_resource))
        {
            fclose($this->_resource);

            $this->_size = null;
            $this->_data = null;
        }

        return $this;
    }

    /**
     * Convert the stream to a string if the stream is readable and the stream is seekable.
     *
     * @return string
     */
    public function getContent()
    {
        $result = '';

        if ($this->isReadable() && $this->isSeekable())
        {
            $position = $this->peek();
            $result   = stream_get_contents($this->_resource, -1, 0);
            $this->seek($position);
        }

        return $result;
    }

    /**
     * Get the stream resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $$this->_resource;
    }

    /**
     * Calculate a hash of a Stream
     *
     * @param string  $algo Hash algorithm (e.g. md5, crc32, etc)
     * @param bool    $raw  Whether or not to use raw output
     * @return bool|string Returns false on failure or a hash string on success
     */
    public function getHash($algo = 'sha1', $raw = false)
    {
        $result = false;

        if ($this->isReadable() && $this->isSeekable())
        {
            $current = $this->peek();
            if ($this->seek(0) && in_array($algo, hash_algos()))
            {
                $hash = hash_init($algo);
                hash_update_stream($hash, $this->_resource);
                $result = hash_final($hash, (bool) $raw);
                $this->seek($current);
            }
        }

        return $result;
    }

    /**
     * Get the stream wrapper type
     *
     * @return string
     */
    public function getProtocol()
    {
        if($this->getData('wrapper_data') instanceof FilesystemStreamWrapperInterface)
        {
            $wrapper  = $this->getData('wrapper_data');
            $protocol = $wrapper->getProtocol();
        }
        else
        {
            $protocol = $this->getData('wrapper_type');

            //PHP reports a wrapper_type of 'plainfile' for the 'file' protocol.
            if($protocol == 'plainfile') {
                $protocol = 'file';
            }
        }

        return $protocol;
    }

    /**
     * Get the path or uri associated with this stream
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getData('uri');
    }

    /**
     * Get the size of the stream
     *
     * @return int|bool
     */
    public function getSize()
    {
        if ($this->_size == null)
        {
            // If the stream is a file based stream and local, then use fstat
            clearstatcache(true, $this->getPath());
            $info = $this->getInfo();

            if (isset($info['size'])) {
                $this->_size = $info['size'];
            } else {
                $this->_size = strlen((string) $this);
            }
        }

        return $this->_size;
    }


    /**
     * Set the size of the stream
     *
     * @param $size
     * @return FilesystemStream
     */
    public function setSize($size)
    {
        $this->_size = $size;
        return $this;
    }

    /**
     * Get the streams last modified, last accessed or created time.
     *
     * @param string $time One of the TIME_* constants
     * @return \DateTime|false A DateTime object or FALSE if the time could not be found
     */
    public function getTime($time = self::TIME_MODIFIED)
    {
        $result = false;
        $info = $this->getInfo();

        if(isset($info[$time])) {
            $result = \DateTime::createFromFormat('U', $info[$time]);
        }

        return $result;
    }

    /**
     * Get the stream type
     *
     * @return string|false The stream type, see also the TYPE_* constants or FALSE if the type could not be found.
     */
    public function getType()
    {
        $type = self::TYPE_UNKNOWN;
        if($this->getData('wrapper_data') instanceof FilesystemStreamWrapperInterface)
        {
            $wrapper = $this->getData('wrapper_data');
            $type = $wrapper->getType();
        }
        else
        {
            if($path = $this->getPath()) {
                $type = filetype($path);
            }
        }

        return $type;
    }

    /**
     * Gives information about the stream
     *
     * @link http://be2.php.net/manual/en/function.fstat.php
     *
     * @return array
     */
    public function getInfo()
    {
        return fstat($this->_resource);
    }

    /**
     * Get stream metadata
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     *
     * @param string $key Specific metadata to retrieve
     * @return array|mixed|null
     */
    public function getData($key = null)
    {
        if(!$this->_data) {
            $this->_data = stream_get_meta_data($this->_resource);
        }

        return !$key ? $this->_data : (array_key_exists($key, $this->_data) ? $this->_data[$key] : null);
    }

    /**
     * Set custom options on the stream
     *
     * @param string $name   Name of the option to set
     * @param mixed  $value  Value to set
     * @return FilesystemStreamInterface
     */
    public function setData($name, $value)
    {
        if(!$this->_data) {
            $this->_data = stream_get_meta_data($this->_resource);
        }

        $this->_data[$name] = $value;
        return $this;
    }

    /**
     * Get the stream context
     *
     * @return resource
     */
    public function getContext()
    {
        return stream_context_create($this->_context);
    }

    /**
     * Set the stream context params
     *
     * @param array|resource $context An stream, wrapper or context resource or  an array of context parameters
     * @return bool
     */
    public function setContext($context)
    {
        $result = false;

        //Get the context params from the resource
        if(is_resource($context)) {
            $context = (array) stream_context_get_params($context);
        }

        if(is_array($context))
        {
            if(!isset($this->_context)) {
                $this->_context = $context;
            } else {
                $this->_context = array_merge($this->_context, $context);
            }

            $result = stream_context_set_params($this->_resource, $this->_context);
        }

        return $result;
    }

    /**
     * Attach a filter in FIFO order
     *
     * @param mixed $filter An object that implements ObjectInterface, ObjectIdentifier object
     *                      or valid identifier string
     * @param array $config  An optional array of filter config options
     * @return  bool   Returns TRUE if the filter was attached, FALSE otherwise
     */
    public function attachFilter($filter, $config = array())
    {
        $result = false;

        //Handle custom filters
        if(!in_array($filter, stream_get_filters()))
        {
            //Create the complete identifier if a partial identifier was passed
            if (is_string($filter) && strpos($filter, '.') === false)
            {
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('stream', 'filter');
                $identifier->name = $filter;
            }
            else $identifier = $this->getIdentifier($filter);

            if($identifier->inherits('Nooku\Library\FilesystemStreamFilterInterface'))
            {
                $filter = $identifier->classname;
                $filter::register();

                $filter = $filter::getName();
            }
        }

        //If we have a valid filter name create the filter and append it
        if(is_string($filter) && !empty($filter))
        {
            $mode = 0;
            if($this->isReadable()) {
                $mode = $mode & STREAM_FILTER_READ;
            }

            if($this->isWritable()) {
                $mode = $mode & STREAM_FILTER_WRITE;
            }

            if($resource = stream_filter_append($this->_resource, $filter, $mode, $config))
            {
                $this->_filters[$filter] = $filter;
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Detach a filter
     *
     * @param string $name   The name of the filter
     * @return bool
     */
    public function detachFilter($filter)
    {
        $result = false;
        if(!is_resource($filter) && isset($this->_filters[$filter])){
            $filter = $this->_filters[$filter];
        }

        if(is_resource($filter)) {
            $result = stream_filter_remove($filter);
        }

        return $result;
    }

    /**
     * Register the stream wrapper
     *
     * Function prevents from registering the wrapper twice
     *
     * @param mixed $wrapper An ObjectIdentifier object or valid identifier string
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function registerWrapper($wrapper, $config = array() )
    {
        $result  = false;
        $wrapper = $this->getObject($wrapper, $config);

        if(!$wrapper instanceof FilesystemStreamWrapperInterface)
        {
            throw new \UnexpectedValueException(
                'Wrapper: '.get_class($wrapper).' does not implement FilesystemStreamWrapperInterface'
            );
        }

        $result = $wrapper->register();
        return $result;
    }

    /**
     * Un Register a stream wrapper
     *
     * @param mixed $wrapper An ObjectIdentifier object or valid identifier string
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function unregisterWrapper($wrapper)
    {
        $result  = false;
        $wrapper = $this->getObject($wrapper);

        if(!$wrapper instanceof FilesystemStreamWrapperInterface)
        {
            throw new \UnexpectedValueException(
                'Wrapper: '.get_class($wrapper).' does not implement FilesystemStreamWrapperInterface'
            );
        }

        $result = $wrapper->unregister();
        return $result;
    }

    /**
     * Get a list of all the registered stream wrappers
     *
     * @return array
     */
    public function getWrappers()
    {
        return stream_get_wrappers();
    }

    /**
     * Check if a filter is attached to the stream
     *
     * @param string $name  The name of the filter
     * @return bool Returns TRUE if the filter is attached, FALSE otherwise.
     */
    public function hasFilter($name)
    {
        return isset($this->_filters[$name]);
    }

    /**
     * Get a filter
     *
     * @param string $name  The name of the filter
     * @return resource The filter resource
     */
    public function getFilter($name)
    {
        $filter = null;
        if(isset($this->_filters[$name])) {
            $filter = $this->_filters[$name];
        }

        return $filter;
    }

    /**
     * Get the attached filters
     *
     * @return array The named list of attached filters
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Check if the stream is a local stream vs a remote stream
     *
     * @return bool
     */
    public function isLocal()
    {
        return stream_is_local($this->_resource);
    }

    /**
     * Check if the stream is readable
     *
     * @return bool
     */
    public function isReadable()
    {
        $mode = $this->getData('mode');
        return isset(self::$modes['read'][$mode]);
    }

    /**
     * Check if the stream is writable
     *
     * @return bool
     */
    public function isWritable()
    {
        $mode = $this->getData('mode');
        return isset(self::$modes['write'][$mode]);
    }

    /**
     * Check if the stream is repeatable
     *
     * When TRUE the stream can be repeated an unlimited number of times, without any limitation on when a repeat can
     * occur. A repeatable streams getContent() and copy() or flush() methods can be called more than once whereas a
     * non-repeatable entity's can not.
     *
     * @return bool
     */
    public function isRepeatable()
    {
        return $this->isReadable() && $this->isSeekable();
    }

    /**
     * Check if the stream is seekable
     *
     * @return bool
     */
    public function isSeekable()
    {
        return (boolean) $this->getData('seekable');
    }

    /**
     * Check if the stream is consumed
     *
     * @return bool
     */
    public function isConsumed()
    {
        return $this->eof();
    }

    /**
     * Check if the stream wrapper is registered for a specific protocol
     *
     * @param string $protocol
     * @return bool TRUE if the path is a registered stream URL, FALSE otherwise.
     */
    public function isRegistered($protocol)
    {
        $result = in_array($protocol, stream_get_wrappers());
        return $result;
    }

    /**
     * Convert the stream to a string if the stream is readable and the stream is seekable.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }
}