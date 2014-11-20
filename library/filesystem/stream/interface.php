<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * FileSystem Stream Interface
 *
 * A stream interface. Modeled after the PHP streamWrapper class prototype. See http://php.net/streamwrapper
 * for details on that.
 *
 * We divert from the PHP prototype in the following:
 *
 *  * better method names
 *  * methods that should not be implemented in the PHP prototype when not being
 *    supported (like rename) must throw a \BadMethodCallException instead.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\FileSystem\Stream\Interface
 */
interface FilesystemStreamInterface extends ObjectInterface
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
    CONST TYPE_TEMP      = 'temp';
    CONST TYPE_LINK      = 'link';
    CONST TYPE_DIRECTORY = 'dir';
    CONST TYPE_FIFO      = 'fifo';
    CONST TYPE_MEMORY    = 'memory';
    CONST TYPE_BLOCK     = 'block';
    CONST TYPE_SOCKET    = 'socket';
    CONST TYPE_UNKNOWN   = false;

    /**
     * Get the stream name used to register the stream with
     *
     * @return string The stream name
     */
    public static function getName();

    /**
     * Opens the stream
     *
     * @throws \BadMethodCallException If open is not supported.
     * @throws \RuntimeException If the stream cannot be opened.
     * @return FilesystemStreamAbstract|false Return a stream object or FALSE on failure.
     */
    public function open();

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
    public function read($length = -1);

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
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Returns the current position of the stream read/write pointer
     *
     * @return int Should return the current position of the stream.
     */
    public function peek();

    /**
     * Lock the stream
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
    public function lock($operation);

    /**
     * Unlock the stream
     *
     * This method is called when closing the stream (LOCK_UN).
     *
     * @throws \BadMethodCallException if unlock is not supported.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function unlock();

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
    public function write($data);

    /**
     * Read data from the stream to another stream
     *
     * @param resource|FilesystemStreamInterface $stream The stream resource to copy the data too
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public function copy($stream);

    /**
     * Rename a stream
     *
     * @param string $path  The URL which the stream should be renamed to.
     * @throws \BadMethodCallException if rename is not supported.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function rename($path);

    /**
     * Delete a file
     *
     * @throws \BadMethodCallException if unlink is not supported.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function unlink();

    /**
     * Indicates whether the current position is the end-of-stream
     *
     * @return boolean Should return TRUE if the read/write position is at the end of the stream and if no more data is
     *                 available to be read, or FALSE otherwise.
     */
    public function eof();

    /**
     * Flush the data from the stream to another stream
     *
     * If no target stream is being passed and you have cached data that is not yet stored into the underlying storage,
     * you should do so now
     *
     * @param resource|FilesystemStreamInterface|null $stream The stream resource to flush the data too
     * @param int $length  The total bytes to flush, if -1 the stream will be flushed until eof. The limit should
     *                     lie within the total size of the stream.
     * @return boolean Should return TRUE if the cached data was successfully stored (or if there was no data to store),
     *                 or FALSE if the data could not be stored.
     */
    public function flush($stream = null, $length = -1);

    /**
     * Truncate to given size
     *
     * @param int $size The new size
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function truncate($size);

    /**
     * Closes the stream
     *
     * It must free all the resources. If there is any data to flush, you should do so
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function close();

    /**
     * Retrieve the underlying resource
     *
     * @param  integer $cast_ass Can be STREAM_CAST_FOR_SELECT when stream_select() is calling stream_cast()
     *                           or STREAM_CAST_AS_STREAM when stream_cast() is called for other uses.
     * @return mixed   using resource or false
     */
    public function cast($cast_as);

    /**
     * Get the stream type
     *
     * @return string The stream type
     */
    public function getType();

    /**
     * Get the stream path
     *
     * @return string The stream path
     */
    public function getPath();

    /**
     * Get the stream mode
     *
     * @return string
     */
    public function getMode();

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
    public function getInfo($link = false);

    /**
     * Get the size of the stream
     *
     * @return int|bool
     */
    public function getSize();

    /**
     * Get the stream options
     *
     * @return array
     */
    public function getOptions();

    /**
     * Calculate a hash of a Stream
     *
     * @param string  $algo Hash algorithm (e.g. md5, crc32, etc)
     * @param bool    $raw  Whether or not to use raw output
     * @return bool|string Returns false on failure or a hash string on success
     */
    public function getHash($algo = 'sha1', $raw = false);

    /**
     * Get the streams last modified, last accessed or created time.
     *
     * @param string $time One of the TIME_* constants
     * @return \DateTime|false A DateTime object or FALSE if the time could not be found
     */
    public function getTime($time = self::TIME_MODIFIED);

    /**
     * Get the stream resource
     *
     * @return resource
     */
    public function getResource();

    /**
     * Set the stream resource
     *
     * @param resource $resource  Stream resource
     * @throws \RuntimeException  If the resource is not a valid 'stream' resource.
     * @return FilesystemStreamInterface
     */
    public function setResource($resource);

    /**
     * Get the chunk size using during read operations
     *
     * @return integer The chunk size in bytes
     */
    public function getChunkSize();

    /**
     * Set the chunk size using during read operation
     *
     * @param integer $size The chunk size in bytes
     * @return FilesystemStreamInterface
     */
    public function setChunkSize($size);

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
    public function setBlocking($mode);

    /**
     * Set timeout period on a stream
     *
     * @param int $seconds       The seconds part of the timeout to be set.
     * @param int $microseconds  The microseconds part of the timeout to be set.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function setTimeout($seconds, $microseconds = 0);

    /**
     * Sets write file buffering on the given stream
     *
     * @param int $mode STREAM_BUFFER_NONE or STREAM_BUFFER_FULL
     * @param int $size The number of bytes to buffer. If buffer is 0 then write operations are unbuffered. This
     *                  ensures that all writes with fwrite() are completed before other processes are allowed to
     *                  write to the stream
     * @return int|false Returns 0 on success, or FALSE on failure
     */
    public function setBuffer($mode, $size);

    /**
     * Attach a filter in FIFO order
     *
     * @param mixed $filter An object that implements ObjectInterface, ObjectIdentifier object
     *                      or valid identifier string
     * @param array $config  An optional array of filter config options
     * @return  bool   Returns TRUE if the filter was attached, FALSE otherwise
     */
    public function addFilter($filter, $config = array());

    /**
     * Detach a filter
     *
     * @param string $filter   The name of the filter
     * @return bool
     */
    public function removeFilter($filter);

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
     * Check if the stream is seekable
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Indicates whether the stream is in binary mode
     *
     * @return Boolean
     */
    public function isBinary();

    /**
     * Indicates whether the stream is in text mode
     *
     * @return Boolean
     */
    public function isText();

    /**
     * Check if the stream is a local stream vs a remote stream
     *
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function isLocal();

    /**
     * Indicates whether the stream is blocked
     *
     * @return bool TRUE when the stream is in blocking IO mode
     */
    public function isBlocked();

    /**
     * Indicates whether the stream is blocked
     *
     * @return bool TRUE if the stream timed out while waiting for data on the last call to fread() or fgets().
     */
    public function isTimeout();

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
    public function toString();

}