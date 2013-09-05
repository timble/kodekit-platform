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
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
interface FilesystemStreamInterface
{
    /**
     * Time Constants
     */
    CONST TIME_CREATED  = 'created';
    CONST TIME_ACCESSED = 'accessed';
    CONST TIME_MODIFIED = 'modified';

    /**
     * Type Constants
     */
    CONST TYPE_FILE      = 'file';
    CONST TYPE_LINK      = 'link';
    CONST TYPE_DIRECTORY = 'dir';
    CONST TYPE_FIFO      = 'fifo';
    CONST TYPE_STRING    = 'string';
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
     * @return integer|false A Unix timestamp or FALSE if the time could not be found
     */
    public function getTime($time = self::TIME_MODIFIED);

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
     * Check if the stream wrapper is registered
     *
     * @return bool TRUE if the path is a registered stream URL, FALSE otherwise.
     */
    public function isRegistered();
}