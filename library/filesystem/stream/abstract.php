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
 * Abstract FileSystem Stream
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\FileSystem\Stream\Abstract
 */
abstract class FilesystemStreamAbstract extends Object implements FilesystemStreamInterface
{
    /**
     * The stream name
     *
     * @var string
     */
    protected static $_name = '';

    /**
     * The stream type
     *
     * @var string
     */
    protected $_type;

    /**
     * The stream path
     *
     * @var string
     */
    protected $_path;

    /**
     * The stream mode
     *
     * @var boolean
     */
    protected $_mode;

    /**
     * The stream options
     *
     * @var array
     */
    protected $_options;

    /**
     * Stream filters
     *
     * @var array List of the attached filters
     */
    protected $_filters;

    /**
     * The stream content
     *
     * @var resource|string
     */
    protected $_resource;

    /**
     * Chunk size
     *
     * @var integer The chunk size
     * @see read()
     */
    protected $_chunk_size;

    /**
     * Lookup table of readable and writable stream types
     *
     * @var array
     */
    protected static $modes = array(

        'read' => array(
            'r'  => true,
            'r+' => true,
            'w'  => false,
            'w+' => true,
            'a'  => false,
            'a+' => true,
            'x'  => false,
            'x+' => true,
            'c'  => false,
            'c+' => true,
        ),

        'write' => array(
            'r'  => false,
            'r+' => true,
            'w'  => true,
            'w+' => true,
            'a'  => true,
            'a+' => true,
            'x'  => true,
            'x+' => true,
            'c'  => true,
            'c+' => true,
        )
    );

    /**
     * Object constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config = null)
    {
        parent::__construct($config);

        //Set the chunk size
        $this->setChunkSize($config->chunk_size);

        $this->_type    = $config->type;
        $this->_path    = $config->path;
        $this->_mode    = $config->mode;
        $this->_options = ObjectConfig::unbox($config->options);

        //Attach stream filters
        foreach($config->filters as $key => $filter)
        {
            if (is_numeric($key)) {
                $this->addFilter($filter);
            } else {
                $this->addFilter($key, $filter);
            }
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'path'       => null,
            'type'       => null,
            'mode'       => 'w+b',
            'options'    => array(),
            'filters'    => array(),
            'chunk_size' => '8192'
        ));
    }

    /**
     * Get the stream name used to register the stream with
     *
     * @return string The stream name
     */
    public static function getName()
    {
        return static::$_name;
    }

    /**
     * Opens the stream
     *
     * @param string $mode The mode used to open the file, as detailed for fopen().
     * @throws \BadMethodCallException If open is not supported on the stream
     * @throws \RuntimeException If the stream cannot be opened.
     * @return FilesystemStreamAbstract|false Return a stream object or FALSE on failure.
     */
    public function open()
    {
        if($path = $this->getPath())
        {
            try
            {
                $options = $this->getOptions();

                if(!empty($options))
                {
                    $scheme          = $this->getIdentifier()->getName();
                    $context         = stream_context_create(array($scheme => $options));
                    $this->_resource = @fopen($path, $this->getMode(), false, $context);
                }
                else $this->_resource = @fopen($path, $this->getMode(), false);

            }
            catch (Exception $e) {
                $this->_resource = false;
            }

            if ($this->_resource === false)
            {
                throw new \RuntimeException(sprintf(
                    'Failed to open stream "%s" with mode "%s"', $path, $this->getMode()
                ));
            }

            return true;
        }
        else throw new \BadMethodCallException('The stream "'.self::getName().'" does not support open.');

        return false;
    }

    /**
     * Reads the specified number of bytes from the current position
     *
     * If the current position is the end-of-file, you must return an empty string.
     *
     * @param integer|null $length How many bytes of data from the current position should be returned. Defaults to -1
     *                            (use the chunk size, default 8192 bytes).
     * @throws \BadMethodCallException If read is not supported.
     * @throws \LogicException If read is not allowed.
     * @return string If there are less than count bytes available, return as many as are available. If no more data is
     *                available, return either FALSE or an empty string.
     */
    public function read($length = -1)
    {
        $result = false;

        if($resource = $this->getResource())
        {
            if (!$this->isReadable()) {
                throw new \LogicException('The stream does not allow read.');
            } else {
                $result = fread($resource, $length < 0 ? $this->getChunkSize() : $length);
            }
        }
        else throw new \BadMethodCallException('The stream "'.self::getName().'" does not support read.');

        return $result;
    }

    /**
     * Seeks to specific location in a stream.
     *
     * The read/write position of the stream should be updated according to the offset and whence.
     *
     * $whence can one of:
     *
     *  SEEK_SET - Set position equal to offset bytes.
     *  SEEK_CUR - Set position to current location plus offset.
     *  SEEK_END - Set position to end-of-file plus offset.
     *
     * @param integer $offset The stream offset to seek to.
     * @param integer $whence
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if(($resource = $this->getResource()) && $this->isSeekable()) {
            return fseek($resource, $offset, $whence) === 0;
        }

        return false;
    }

    /**
     * Returns the current position of the stream read/write pointer
     *
     * @return int Should return the current position of the stream.
     */
    public function peek()
    {
        if($resource = $this->getResource()) {
            return ftell($resource);
        }

        return false;
    }

    /**
     * Advisory file locking.
     *
     * This method is called in response to flock(), when file_put_contents() (when flags contains LOCK_EX),
     * stream_set_blocking().
     *
     * $operation is one of the following:
     *
     *  LOCK_SH to acquire a shared lock (reader).
     *  LOCK_EX to acquire an exclusive lock (writer).
     *  LOCK_NB if you don't want flock() to block while locking.
     *
     * @param integer $operation One of the LOCK_* constants
     * @throws \BadMethodCallException if lock is not supported.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function lock($operation)
    {
        throw new \BadMethodCallException('The stream "'.self::getName().'" does not support lock.');
    }

    /**
     * Advisory file locking.
     *
     * This method is called when closing the stream (LOCK_UN).
     *
     * @throws \BadMethodCallException if unlock is not supported.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function unlock()
    {
        throw new \BadMethodCallException('The stream "'.self::getName().'" does not support unlock.');
    }

    /**
     *  Write to stream.
     *
     * If there is not enough room in the underlying stream, store as much as possible.
     *
     * Note : Don't forget to update the current position of the stream by number of bytes that were successfully written.
     *
     * @param string $data Should be stored into the underlying stream.
     * @throws \BadMethodCallException if write is not supported.
     * @throws \LogicException If write is not allowed.
     * @return int Should return the number of bytes that were successfully stored, or 0 if none could be stored.
     */
    public function write($data)
    {
        $result = false;

        if($resource = $this->getResource())
        {
            if (!$this->isWritable()) {
                throw new \LogicException('The stream does not allow write.');
            } else {
                $result = fwrite($resource, $data);
            }
        }
        else throw new \BadMethodCallException('The stream "'.self::getName().'" does not support write.');

        return $result;
    }

    /**
     * Copy data from one stream to another stream
     *
     * @param resource|FilesystemStreamInterface $stream The stream resource to copy the data too
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public function copy($stream)
    {
        if($this->getResource())
        {
            if (!$stream instanceof FilesystemStreamInterface && !is_resource($stream) && !get_resource_type($stream) == 'stream')
            {
                throw new \InvalidArgumentException(sprintf(
                    'Stream must be on object implementing the FilesystemStreamInterface or a resource of type "stream".'
                ));
            }

            if($stream instanceof FilesystemStreamInterface) {
                $resource = $stream->getResource();
            } else {
                $resource = $stream;
            }

            return fwrite($resource, $this->read());
        }

        return false;
    }

    /**
     * Rename a stream
     *
     * @param string $path  The URL which the stream should be renamed to.
     * @throws \BadMethodCallException if rename is not supported.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function rename($path)
    {
        throw new \BadMethodCallException('The stream "'.self::getName().'" does not support rename.');
    }

    /**
     * Delete a file
     *
     * @throws \BadMethodCallException if unlink is not supported.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function unlink()
    {
        if($path = $this->getPath())
        {
            if(@unlink($path) === true)
            {
                if($this->getResource()) {
                    $this->close();
                }

                $this->_path = null;
                return true;
            }
        }
        else throw new \BadMethodCallException('The stream "'.self::getName().'" does not support unlink.');

        return false;
    }

    /**
     * Indicates whether the current position is the end-of-stream
     *
     * @return boolean Should return TRUE if the read/write position is at the end of the stream and if no more data is
     *                 available to be read, or FALSE otherwise.
     */
    public function eof()
    {
        if($resource = $this->getResource()) {
            return feof($resource);
        }

        return false;
    }

    /**
     * Flush the data from the stream to another stream
     *
     * If no target stream is being passed and you have cached data that is not yet stored into the underlying storage,
     * you should do so now
     *
     * @param resource|FilesystemStreamInterface|null $stream The stream resource to flush the data too
     * @param int  $length  The total bytes to flush, if -1 the stream will be flushed until eof. The limit should
     *                      lie within the total size of the stream.
     * @return boolean Should return TRUE if the cached data was successfully stored (or if there was no data to store),
     *                 or FALSE if the data could not be stored.
     */
    public function flush($stream = null, $length = -1)
    {
        if($this->getResource() && $stream !== NULL)
        {
            if (!$stream instanceof FilesystemStreamInterface && !is_resource($stream) && !get_resource_type($stream) == 'stream')
            {
                throw new \InvalidArgumentException(sprintf(
                    'Stream must be on object implementing the FilesystemStreamInterface or a resource of type "stream".'
                ));
            }

            if($stream instanceof FilesystemStreamInterface) {
                $resource = $stream->getResource();
            } else {
                $resource = $stream;
            }

            $range = $length < 0 ? $this->getSize() : $length;

            //Send data chunk
            while (!$this->eof() && $this->peek() <= $range) {
                $this->copy($resource);
            }

            return true;
        }

        return false;
    }

    /**
     * Truncate to given size
     *
     * @param int $size The new size
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function truncate($size)
    {
        if($resource = $this->getResource())
        {
            if($this->isWritable()) {
                return ftruncate($resource, $size);
            }
        }

        return false;
    }

    /**
     * Rewind to the beginning of the stream
     *
     * @return bool Returns true on success or false on failure
     */
    public function rewind()
    {
        if($resource = $this->getResource() && $this->isSeekable()) {
            return rewind($resource);
        }

        return false;
    }

    /**
     * Closes the stream
     *
     * It must free all the resources. If there is any data to flush, you should do so
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function close()
    {
        if($resource = $this->getResource())
        {
            if(fclose($resource) === true)
            {
                $this->_resource = null;
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieve the underlying resource
     *
     * @param  integer $cast_ass Can be STREAM_CAST_FOR_SELECT when stream_select() is calling stream_cast()
     *                           or STREAM_CAST_AS_STREAM when stream_cast() is called for other uses.
     * @return mixed   using resource or false
     */
    public function cast($cast_as)
    {
        return false;
    }

    /**
     * Get the stream type
     *
     * @return string The stream type
     */
    public function getType()
    {
        if($this->_type == null)
        {
            if($path = $this->getPath())
            {
                if(!$this->_type = @filetype($path)) {
                    $this->_type = self::TYPE_UNKNOWN;
                }
            }
        }

        return $this->_type;
    }

    /**
     * Get the stream path
     *
     * @return string he URI/filename associated with this stream
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Get the stream mode
     *
     * @param bool $include_flags If false strip binary/text flags from mode. Default TRUE.
     * @return string
     */
    public function getMode($include_flags = true)
    {
        $mode = $this->_mode;

        //Strip binary/text flags from mode
        if($include_flags === false) {
            $mode = strtr($mode, array('b' => '', 't' => ''));
        }

        return $mode;
    }

    /**
     * Retrieve information about the resource pointed to by the stream
     *
     * @param boolean $link For resources with the ability to link to other resource (such as an HTTP Location: forward,
     *                      or a filesystem symlink). This flag specified that only information about the link itself
     *                      should be returned, not the resource pointed to by the link. This flag is set in response
     *                      to calls to lstat(), is_link(), or filetype().
     * @throws \BadMethodCallException if info is not supported.
     * @return array See http://php.net/stat
     */
    public function getInfo($link = false)
    {
        if(!$this->getResource()) {
            throw new \BadMethodCallException('The stream "'.self::getName().'" does not support info.');
        }

        if($this->isLocal()) {
            $info = fstat($this->_resource);
        }
        else {
            $info = @stat($this->getPath());
        }

        return $info;
    }

    /**
     * Get the size of the stream
     *
     * @return int|bool
     */
    public function getSize()
    {
        // If the stream is a file based stream and local, then use fstat
        clearstatcache(true, $this->getPath());

        $info = $this->getInfo();

        if (isset($info['size'])) {
            $size = $info['size'];
        } else {
            $size = strlen((string) $this->toString());
        }

        return $size;
    }

    /**
     * Get the stream options
     *
     * @return array
     */
    public function getOptions()
    {
        if($resource = $this->getResource())
        {
            $options = stream_context_get_options($resource);

            if(!empty($options))
            {
                $name = key($options);
                $result = $options[$name];
            }
            else $result = array();

            return $result;
        }

        return $this->_options;
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
            $result = new \DateTime('@'.$info[$time]);
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
        return $this->_resource;
    }

    /**
     * Set the stream resource
     *
     * @param resource $resource  Stream resource
     * @throws \RuntimeException  If the resource is not a valid 'stream' resource.
     * @return FilesystemStreamAbstract
     */
    public function setResource($resource)
    {
        if(get_resource_type($resource) !== 'stream')
        {
            throw new \RuntimeException(sprintf(
                "Not a valid 'stream' resource; received a '%s' resource", get_resource_type($resource)
            ));
        }

        $this->_resource = $resource;

        return $this;
    }

    /**
     * Get the chunk size using during read operations
     *
     * @return integer The chunk size in bytes
     */
    public function getChunkSize()
    {
        return $this->_chunk_size;
    }

    /**
     * Set the chunk size using during read operation
     *
     * @param integer $size The chunk size in bytes
     * @return FilesystemStreamAbstract
     */
    public function setChunkSize($size)
    {
        $this->_chunk_size = $size;
        return $this;
    }

    /**
     * Set blocking/non-blocking mode on a stream
     *
     * This function works for any stream that supports non-blocking mode (currently, regular files and socket streams)
     *
     * @param int $mode If mode is 0, the given stream will be switched to non-blocking mode, and if 1, it will be
     *                  switched to blocking mode. This affects calls like fgets() and fread() that read from the
     *                  stream. In non-blocking mode an fgets() call will always return right away while in blocking
     *                  mode it will wait for data to become available on the stream.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function setBlocking($mode)
    {
        if($resource = $this->getResource()) {
            return stream_set_blocking($resource, $mode);
        }

        return false;
    }

    /**
     * Set timeout period on a stream
     *
     * @param int $seconds       The seconds part of the timeout to be set.
     * @param int $microseconds  The microseconds part of the timeout to be set.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function setTimeout($seconds, $microseconds = 0)
    {
        if($resource = $this->getResource()) {
            return stream_set_timeout($resource, $seconds, $microseconds);
        }

        return false;
    }

    /**
     * Sets write file buffering on the given stream
     *
     * @param int $mode STREAM_BUFFER_NONE or STREAM_BUFFER_FULL
     * @param int $size The number of bytes to buffer. If buffer is 0 then write operations are unbuffered. This
     *                  ensures that all writes with fwrite() are completed before other processes are allowed to
     *                  write to the stream
     * @return int|false Returns 0 on success, or FALSE on failure
     */
    public function setBuffer($mode, $size)
    {
        if($resource = $this->getResource()) {
            return stream_set_write_buffer($resource, $size);
        }

        return false;
    }

    /**
     * Attach a filter in FIFO order
     *
     * @param mixed $filter An object that implements ObjectInterface, ObjectIdentifier object
     *                      or valid identifier string
     * @param array $config  An optional array of filter config options
     * @return  bool   Returns TRUE if the filter was attached, FALSE otherwise
     */
    public function addFilter($filter, $config = array())
    {
        $result = false;

        if(is_resource($this->_resource))
        {
            //Handle custom filters
            if(!in_array($filter, stream_get_filters()))
            {
                //Create the complete identifier if a partial identifier was passed
                if (is_string($filter) && strpos($filter, '.') === false)
                {
                    $identifier = $this->getIdentifier()->toArray();
                    $identifier['path'] = array('stream', 'filter');
                    $identifier['name'] = $filter;

                    $identifier = $this->getIdentifier($identifier);
                }
                else $identifier = $this->getIdentifier($filter);

                //Make sure the class
                $class = $this->getObject('manager')->getClass($identifier);

                if(array_key_exists(__NAMESPACE__.'\FilesystemStreamFilterInterface', class_implements($class)))
                {
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
        }

        return $result;
    }

    /**
     * Detach a filter
     *
     * @param string $filter   The name of the filter
     * @return  bool   Returns TRUE if the filter was detached, FALSE otherwise
     */
    public function removeFilter($filter)
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
     * Check if the stream is readable
     *
     * @return bool
     */
    public function isReadable()
    {
        return isset(self::$modes['read'][$this->getMode(false)]);
    }

    /**
     * Check if the stream is writable
     *
     * @return bool
     */
    public function isWritable()
    {
        return isset(self::$modes['write'][$this->getMode(false)]);
    }

    /**
     * Check if the stream is seekable
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function isSeekable()
    {
        if($resource = $this->getResource())
        {
            $data = stream_get_meta_data($resource);
            return (bool) $data['seekable'];

        }

        return false;
    }

    /**
     * Indicates whether the stream is in binary mode
     *
     * @return bool
     */
    public function isBinary()
    {
        return (bool) strpos($this->getMode(), 'b');
    }

    /**
     * Indicates whether the stream is in text mode
     *
     * @return bool
     */
    public function isText()
    {
        return (bool) strpos($this->getMode(), 't');
    }

    /**
     * Check if the stream is a local stream vs a remote stream
     *
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function isLocal()
    {
        if($resource = $this->getResource()) {
            return stream_is_local($resource);
        }

        return false;
    }

    /**
     * Indicates whether the stream is blocked
     *
     * @return bool TRUE when the stream is in blocking IO mode
     */
    public function isBlocked()
    {
        if($resource = $this->getResource())
        {
            $data = stream_get_meta_data($resource);
            return (bool) $data['blocked'];

        }

        return false;
    }

    /**
     * Indicates whether the stream is blocked
     *
     * @return bool TRUE if the stream timed out while waiting for data on the last call to fread() or fgets().
     */
    public function isTimeout()
    {
        if($resource = $this->getResource())
        {
            $data = stream_get_meta_data($resource);
            return (bool) $data['timed_out'];

        }

        return false;
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before reading data and read the stream until
     * the end is reached. The file pointer should stay at it's original position.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * @return string
     */
    public function toString()
    {
        $result = '';

        if ($this->isReadable() && $this->isSeekable())
        {
            $position = $this->peek();

            $this->seek(0);
            $result = stream_get_contents($this->_resource);
            $this->seek($position);
        }

        return $result;
    }

    /**
     * Cast the object to a string
     *
     * @return string
     */
    final public function __toString()
    {
        $result = '';

        //Not allowed to throw exceptions in __toString() See : https://bugs.php.net/bug.php?id=53648
        try {
            $result = $this->toString();
        } catch (Exception $e) {
            trigger_error(__NAMESPACE__.'\FilesystemStreamAbstract::__toString exception: '. (string) $e, E_USER_ERROR);
        }

        return $result;
    }
}