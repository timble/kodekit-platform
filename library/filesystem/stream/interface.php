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
 * FileSystem Stream Interface
 *
 * The filesystem stream is an object oriented wrapper for the the PHP file system API. It wraps the file resource
 * returned by @see fopen().
 *
 * @link http://www.php.net/manual/en/ref.filesystem.php
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
interface FilesystemStreamInterface
{
    /**
     * Time Constants
     */
    CONST TIME_CREATED  = 'ctime';
    CONST TIME_ACCESSED = 'atime';
    CONST TIME_MODIFIED = 'mtime';

    /**
     * Type Constants
     */
    CONST TYPE_FILE      = 'file';
    CONST TYPE_LINK      = 'link';
    CONST TYPE_DIRECTORY = 'dir';
    CONST TYPE_FIFO      = 'fifo';
    CONST TYPE_MEMORY    = 'memory';
    CONST TYPE_BLOCK     = 'block';
    CONST TYPE_SOCKET    = 'socket';
    CONST TYPE_UNKNOWN   = false;

    /**
     * Set the stream that is wrapped by the object
     *
     * If the stream is not an object we will try to open it in read-only mode.
     *
     * @param resource|string $stream Stream path or resource
     * @param string          $mode   The mode to open the stream with
     * @return FilesystemStreamInterface
     */
    public function open($stream, $mode = 'rb');

    /**
     * Seek to a position in the stream
     *
     * @param int $offset Stream offset
     * @param int $whence Where the offset is applied
     * @return bool Returns TRUE on success or FALSE on failure
     * @link   http://www.php.net/manual/en/function.fseek.php
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int|bool Returns the position of the file pointer or false on error
     */
    public function peek();

    /**
     * Read data from the stream
     *
     * @param int $length Up to length number of bytes read.
     * @return string|bool Returns the data read from the stream or FALSE on failure or EOF
     */
    public function read($length);

    /**
     * Write data to the stream
     *
     * @param string $string The string that is to be written.
     * @return int|bool Returns the number of bytes written to the stream on success or FALSE on failure.
     */
    public function write($string);

    /**
     * Read data from the stream to another stream
     *
     * @param resource $stream The stream resource to copy the data too
     * @param int $length Up to length number of bytes read.
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public function copy($stream, $length);

    /**
     * Flush the data from the stream to the output buffer (php://output)
     *
     * @param int $size   The chunk size in bytes to use when flushing. Default is 8Kb
     * @param int $limit  The total length of the stream to flush, if -1 the stream will be flushed until eof. The limit
     *                    should lie within the total size of the stream.
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public function flush($chunk = '8192', $limit = -1);

    /**
     * Rewind to the beginning of the stream
     *
     * @return bool Returns true on success or false on failure
     */
    public function rewind();

    /**
     * Check if the internal stream pointer has reached the end of the stream
     *
     * @return bool
     */
    public function eof();

    /**
     * Close the underlying stream
     *
     * @return FilesystemStreamInterface
     */
    public function close();

    /**
     * Convert the stream to a string if the stream is readable and the stream is seekable.
     *
     * @return string
     */
    public function getContent();

    /**
     * Get the stream resource
     *
     * @return resource
     */
    public function getResource();

    /**
     * Calculate a hash of a Stream
     *
     * @param string          $algo Hash algorithm (e.g. md5, crc32, etc)
     * @param bool            $raw  Whether or not to use raw output
     * @return bool|string Returns false on failure or a hash string on success
     */
    public function getHash($algo = 'sha1', $raw = false);

    /**
     * Get the stream wrapper type
     *
     * @return string
     */
    public function getProtocol();

    /**
     * Get the path or uri associated with this stream
     *
     * @return string
     */
    public function getPath();

    /**
     * Get the size of the stream if able
     *
     * @return int|bool
     */
    public function getSize();

    /**
     * Get the streams last modified, last accessed or created time.
     *
     * @param string $time One of the TIME_* constants
     * @return \DateTime|false A DateTime object or FALSE if the time could not be found
     */
    public function getTime($time = self::TIME_MODIFIED);

    /**
     * Gives information about the stream
     *
     * @link http://be2.php.net/manual/en/function.fstat.php
     *
     * @return array
     */
    public function getInfo();

    /**
     * Get stream metadata
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     *
     * @param string $key Specific metadata to retrieve
     * @return array|mixed|null
     */
    public function getData($key = null);

    /**
     * Set custom options on the stream
     *
     * @param string $name   Name of the option to set
     * @param mixed  $value  Value to set
     * @return FilesystemStreamInterface
     */
    public function setData($name, $value);

    /**
     * Get the stream context
     *
     * @return resource
     */
    public function getContext();

    /**
     * Set the stream context params
     *
     * @param array|resource $context An stream, wrapper or context resource or  an array of context parameters
     * @return bool Returns TRUE if the context could be successfully set. FALSE otherwise.
     */
    public function setContext($context);

    /**
     * Attach a filter in FIFO order
     *
     * @param mixed $filter An object that implements ObjectInterface, ObjectIdentifier object
     *                      or valid identifier string
     * @param array $config  An optional array of filter config options
     * @return  bool   Returns TRUE if the filter was attached, FALSE otherwise
     */
    public function attachFilter($filter, $config = array());

    /**
     * Detach a filter
     *
     * @param string $name   The name of the filter
     * @return bool
     */
    public function detachFilter($filter);

    /**
     * Check if a filter is attached to the stream
     *
     * @param string $name  The name of the filter
     * @return bool Returns TRUE if the filter is attached, FALSE otherwise.
     */
    public function hasFilter($name);

    /**
     * Get a filter
     *
     * @param string $name  The name of the filter
     * @return resource The filter resource
     */
    public function getFilter($name);

    /**
     * Get the attached filters
     *
     * @return array The named list of attached filters
     */
    public function getFilters();

    /**
     * Register the stream wrapper
     *
     * Function prevents from registering the wrapper twice
     *
     * @param mixed $wrapper An ObjectIdentifier object or valid identifier string
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function registerWrapper($wrapper, $config = array());

    /**
     * Un Register a stream wrapper
     *
     * @param mixed $wrapper An ObjectIdentifier object or valid identifier string
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function unregisterWrapper($wrapper);

    /**
     * Get a list of all the registered stream wrappers
     *
     * @return array
     */
    public function getWrappers();

    /**
     * Check if the stream is a local stream vs a remote stream
     *
     * @return bool
     */
    public function isLocal();

    /**
     * Check if the stream is readable
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Check if the stream is writable
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Check if the stream is repeatable
     *
     * When TRUE the stream can be repeated an unlimited number of times, without any limitation on when a repeat can
     * occur.  A repeatable stream getContent() and copy or flush methods can be called more than once whereas a
     * non-repeatable entity's can not.
     *
     * @return bool
     */
    public function isRepeatable();

    /**
     * Check if the string is repeatable
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Check if the stream is consumed
     *
     * @return bool
     */
    public function isConsumed();

    /**
    /**
     * Check if the stream wrapper is registered for a specific protocol
     *
     * @param string $protocol
     * @return bool TRUE if the path is a registered stream URL, FALSE otherwise.
     */
    public function isRegistered($protocol);
}