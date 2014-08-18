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
 * FileSystem Stream Adapter
 *
 * A generic stream sitting between PHP and streams implementing KFilesystemStreamInterface.
 *
 * Instances of this class are created by PHP itself and therefore are unknown to Koowa object manager.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\FileSystem\Stream\Adapter
 */
final class FilesystemStreamAdapter
{
    /**
     * The stream object
     *
     * @var FilesystemStreamInterface
     */
    private $__stream;

    /**
     * The stream context object
     *
     * @var resource
     */
    public $context;

    /**
     * Create a stream
     *
     * If trigger_error flag is set exceptions will be catched and E_USER_WARNING raised using trigger_error(). If the
     * flag is not set and the stream cannot be created FALSE will be returned instead.
     *
     * @param string $url    The stream url
     * @param string $mode   The type of access required for this stream. (see Table 1 of the fopen() reference);
     * @param bool   $trigger_error Raise errors using trigger_error.
     * @return FilesystemStreamInterface|false
     */
    public function createStream($url, $mode, $trigger_error = false)
    {
        try
        {
            $this->__stream = ObjectManager::getInstance()
                ->getObject('filesystem.stream.factory')
                ->createStream($url, $mode, $this->context);
        }
        catch(Exception $e)
        {
            //If this flag is set, you are responsible for raising errors using trigger_error() during
            //opening of the stream. If this flag is not set, you should not raise any errors.
            if ($trigger_error) {
                trigger_error($e->getMessage(), E_USER_WARNING);
            }

            return false;
        }

        return $this->__stream;
    }

    /**
     * Get the stream
     *
     * @return FilesystemStreamInterface|null
     */
    public function getStream()
    {
        return $this->__stream;
    }

    /**
     * Opens file or URL.
     *
     * This method is called immediately after the stream is initialized (f.e. by fopen() and file_get_contents()).
     *
     * @param string    $path     Specifies the URL that was passed to the original function.
     * @param string    $mode     The mode used to open the file, as detailed for fopen().
     * @param int       $options  Stream options (STREAM_USE_PATH | STREAM_REPORT_ERRORS)
     * @param string    $opened_path If the path is opened successfully, and STREAM_USE_PATH is set in options,
     *                             opened_path should be set to the full path of the file/resource that was actually
     *                             opened.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        if($stream = $this->createStream($path, $mode, $options & STREAM_REPORT_ERRORS))
        {
            //If the path is opened successfully, and STREAM_USE_PATH is set in options, opened_path
            //should be set to the full path of the file/resource that was actually opened.
            if ($options & STREAM_USE_PATH) {
                $opened_path = $stream->getPath();
            }

            return true;
        }

        return false;
    }

    /**
     *  Read from stream.
     *
     * This method is called in response to fread() and fgets().
     *
     * @param integer $count How many bytes of data from the current position should be returned.
     * @return string If there are less than count bytes available, return as many as are available. If no more data is
     *                available, return either FALSE or an empty string.s
     */
    public function stream_read($bytes)
    {
        if ($stream = $this->getStream()) {
            return $stream->read($bytes);
        }

        return false;
    }

    /**
     * Seeks to specific location in a stream.
     *
     * This method is called in response to fseek().
     *
     * @param int $offset   The stream offset to seek to.
     * @param int $whence   Can be SEEK_SET, SEEK_CUR or SEEK_END. Default SEEK_SET
     * @return boolean
     */
    public function stream_seek($offset, $whence = SEEK_SET)
    {
        if ($stream = $this->getStream()) {
            return $stream->seek($offset, $whence);
        }

        return false;
    }

    /**
     * Retrieve the current position of a stream.
     *
     * This method is called in response to ftell().
     *
     * @return int Should return the current position of the stream.
     */
    public function stream_tell()
    {
        if ($stream = $this->getStream()) {
            return $stream->peek();
        }

        return false;
    }

    /**
     * Advisory file locking.
     *
     * This method is called in response to flock(), when file_put_contents() (when flags contains LOCK_EX),
     * stream_set_blocking() and when closing the stream (LOCK_UN).
     *
     * @param integer $operation One of the LOCK_* constants
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function stream_lock($operation)
    {
        if ($stream = $this->getStream())
        {
            if ($operation === LOCK_UN) {
                return $stream->unlock();
            } else {
                return $stream->lock($operation);
            }
        }

        return false;
    }

    /**
     *  Write to stream.
     *
     * This method is called in response to fwrite().
     *
     * @param string $data Should be stored into the underlying stream.
     * @return int Should return the number of bytes that were successfully stored, or 0 if none could be stored.
     */
    public function stream_write($data)
    {
        if ($stream = $this->getStream()) {
            return $stream->write($data);
        }

        return 0;
    }

    /**
     * Retrieve information about a file resource.
     *
     * This method is called in response to fstat().
     *
     * @return array See http://php.net/stat
     */
    public function stream_stat()
    {
        if ($stream = $this->getStream()) {
            return $stream->getInfo();
        }

        return false;
    }

    /**
     * Tests for end-of-file on a file pointer.
     *
     * This method is called in response to feof().
     *
     * @return @return boolean Should return TRUE if the read/write position is at the end of the stream and if no
     *                         more data is available to be read, or FALSE otherwise.
     */
    public function stream_eof()
    {
        if ($stream = $this->getStream()) {
            return $stream->eof();
        }

        return false;
    }

    /**
     * Flushes the output.
     *
     * This method is called in response to fflush().
     *
     * @return boolean Should return TRUE if the cached data was successfully stored (or if there was no data to store),
     *                 or FALSE if the data could not be stored.
     */
    public function stream_flush()
    {
        if ($stream = $this->getStream()) {
            return $stream->flush();
        }

        return false;
    }

    /**
     * Truncate to given size
     *
     * @param int $size
     * @return bool
     */
    public function stream_truncate($size)
    {
        if ($stream = $this->getStream()) {
            return $stream->truncate($size);
        }

        return false;
    }

    /**
     * Close an resource
     *
     * This method is called in response to fclose().
     *
     * All resources that were locked, or allocated, by the stream should be released.
     *
     * @return void
     */
    public function stream_close()
    {
        if ($stream = $this->getStream()) {
            $stream->close();
        }
    }

    /**
     * Signal that stream_select is not supported by returning false
     *
     *  This method is called in response to stream_select().
     *
     * @param  integer $cast_as
     * @return resource|false Should return the underlying stream resource used by the stream, or FALSE.
     */
    public function stream_cast($cast_as)
    {
        if ($stream = $this->getStream()) {
            return $stream->cast($cast_as);
        }

        return false;
    }

    /**
     * Renames a file or directory.
     *
     * This method is called in response to rename().
     *
     * Should attempt to rename source to target.
     *
     * @param string $path_from The URL to the current file.
     * @param string $path_to   The URL which the path_from should be renamed to.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function rename($path_from, $path_to)
    {
        if($stream = $this->createStream($path_from, 'r+')) {
            return $stream->rename($path_to);
        }

        return false;
    }

    /**
     * Change stream options.
     *
     * This method is called to set options on the stream.
     *
     * @param integer $option
     * @param integer $arg1
     * @param integer $arg2
     * @return boolean TRUE on success or FALSE on failure. If option is not implemented, FALSE should be returned.
     */
    public function stream_set_option($option, $arg1, $arg2)
    {
        if ($stream = $this->getStream())
        {
            switch($option)
            {
                case STREAM_OPTION_BLOCKING      : $result = $stream->setBlocking($arg1); break;
                case STREAM_OPTION_READ_TIMEOUT  : $result = $stream->setTimeout($arg1, $arg2); break;
                case STREAM_OPTION_WRITE_BUFFER  : $result = $stream->setBuffer($arg1, $arg2); break;
                default : $result = false;
            }

            return $result;
        }

        return false;
    }

    /**
     * Retrieve information about a file.
     *
     * This method is called in response to all stat() related functions.
     *
     * @param string  $path The file path or URL to stat. Note that in the case of a URL, it must be a :// delimited
     *                      URL. Other URL forms are not supported.
     * @param integer $flags Holds additional flags set by the streams API.
     * @return array Should return as many elements as stat() does. Unknown or unavailable values should be set to a
     *               rational value (usually 0).
     */
    public function url_stat($path, $options)
    {
        if($stream = $this->createStream($path, 'r', !($options & STREAM_URL_STAT_QUIET))) {
            $stream->getInfo($options & STREAM_URL_STAT_LINK);
        }

        return false;
    }

    /**
     * Delete a file.
     *
     * This method is called in response to unlink().
     *
     * @param string $path The file URL which should be deleted.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function unlink($path)
    {
        if($stream = $this->createStream($path, 'w+')) {
            $stream->unlink();
        }

        return false;
    }
}