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
 * Buffer FileSystem Stream
 *
 * The buffer:// stream  is a read-write stream that allow temporary data to be stored in a file-like.
 * Two types of buffers are supported :
 *
 * - buffer://temp
 *
 * A temporary file buffer that will opens a unique temp file. The location of this temporary file is determined in the
 * same way as the sys_get_temp_dir() function. If the stream is closed the file is removed.
 *
 * - buffer://memory[/path]
 *
 * A memory buffer that is capable of loading data from the path into the buffer, if a path is specified. If a path is
 * specified and the buffer is opened in writing mode, the data in memory will be flushed back into the file when the
 * stream is closed.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\FileSystem\Stream\Buffer
 */
class FilesystemStreamBuffer extends FilesystemStreamAbstract
{
    /**
     * The stream name
     *
     * @var string
     */
    protected static $_name = 'buffer';

    /**
     * The stream length
     *
     * @var int
     */
    protected $_length;

    /**
     * Current stream position
     *
     * @var int
     */
    protected $_position;

    /**
     * Use to check if the memory stream is synced with the physical stream
     *
     * @var bool
     */
    protected $_synchronised;

    /**
     * Opens the stream
     *
     * @throws \RuntimeException If the stream cannot be opened.
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function open()
    {
        //Create a memory buffer
        if($this->getType() == 'memory')
        {
            $this->_resource = '';
            if($path = $this->getPath())
            {
                try {
                    $this->_resource = @file_get_contents($path);
                } catch (Exception $e) {
                    $this->_resource = false;
                }

                if ($this->_resource === false) {
                    throw new \RuntimeException(sprintf('Cannot get content from file : "%s"', $path));
                }
            }

            $this->_position     = 0;
            $this->_synchronised = true;

            //Truncate the file to zero length
            if(in_array($this->getMode(), array('w', 'w+'))) {
                $this->truncate(0);
            }

            //Place the file pointer at the end of the file
            if(in_array($this->getMode(), array('a', 'a+'))) {
                $this->_position = mb_strlen($this->_resource, '8bit');
            }

            return true;
        }

        return parent::open();
    }

    /**
     * Reads the specified number of bytes from the current position
     *
     * If the current position is the end-of-file, you must return an empty string.
     *
     * @param integer|null $bytes How many bytes of data from the current position should be returned. If NULL use the
     *                            chunk size, default 8192
     * @throws \BadMethodCallException If read is not supported.
     * @throws \LogicException If read is not allowed.
     * @return string If there are less than count bytes available, return as many as are available. If no more data is
     *                available, return either FALSE or an empty string.
     */
    public function read($bytes = null)
    {
        $result = false;

        if ($this->isReadable())
        {
            if($this->getType() == 'memory')
            {
                $result = substr($this->_resource, $this->_position, is_int($bytes) ? $bytes : $this->getChunkSize());
                $this->_position += mb_strlen($result, '8bit');
            }
            else $result = parent::read($bytes);
        }
        else throw new \LogicException('The stream does not allow read.');

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
        if($this->getType() == 'memory')
        {
            switch ($whence)
            {
                case SEEK_SET:

                    $this->_position = $offset;
                    return true;
                    break;

                case SEEK_CUR:

                    $this->_position += $offset;
                    return true;
                    break;

                case SEEK_END:

                    $this->_position = $this->_length + $offset;
                    return true;
                    break;
            }
        }

        return parent::seek($offset, $whence);
    }

    /**
     * Returns the current position of the stream read/write pointer
     *
     * @return int Should return the current position of the stream.
     */
    public function peek()
    {
        if($this->getType() == 'memory') {
            $position =  $this->_position;
        } else {
            $position = parent::peek();
        }

        return $position;
    }

    /**
     *  Write to stream.
     *
     * If there is not enough room in the underlying stream, store as much as possible.
     *
     * Note : Don't forget to update the current position of the stream by number of bytes that were successfully written.
     *
     * @param string $data Should be stored into the underlying stream.
     * @throws \LogicException If write is not allowed.
     * @return int Should return the number of bytes that were successfully stored, or 0 if none could be stored.
     */
    public function write($data)
    {
        $result = 0;

        if($this->getType() == 'memory')
        {
            if ($this->isText() && defined('PHP_WINDOWS_VERSION_MAJOR')) {
                $data = preg_replace('/(?<!\r)\n/', "\r\n", $data);
            }

            $left  = substr($this->_resource, 0, $this->_position);
            $right = substr($this->_resource, $this->_position +  mb_strlen($data, '8bit'));

            $this->_resource     = $left . $data . $right;
            $this->_position    += mb_strlen($data, '8bit');
            $this->_length       = mb_strlen($this->_resource, '8bit');
            $this->_synchronised = false;

            $result = mb_strlen($data, '8bit');
        }
        else $result = parent::write($data);

        return $result;
    }

    /**
     * Indicates whether the current position is the end-of-stream
     *
     * @return boolean Should return TRUE if the read/write position is at the end of the stream and if no more data is
     *                 available to be read, or FALSE otherwise.
     */
    public function eof()
    {
        if($this->getType() == 'memory') {
            $result = $this->_position >= $this->_length;
        } else {
            $result = parent::eof();
        }

        return $result;
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
        if($this->getType() == 'temp')
        {
            if(parent::close() == true)
            {
                //Only unlink temporary files
                if($this->getType() == 'temp') {
                    $this->unlink();
                }

                return true;
            }
        }

        if($this->getType() == 'memory') {
            return $this->flush();
        }

        return false;
    }

    /**
     * Flush the data from the stream to another stream
     *
     * If no target stream is being passed and you have cached data that is not yet stored into the underlying storage,
     * you should do so now
     *
     * @param resource|null $stream The stream resource to flush the data too
     * @param int           $length  The total bytes to flush, if -1 the stream will be flushed until eof. The limit should
     *                         lie within the total size of the stream.
     * @return boolean Should return TRUE if the cached data was successfully stored (or if there was no data to store),
     *                 or FALSE if the data could not be stored.
     */
    public function flush($stream = null, $length = -1)
    {
        $result = false;

        if($this->getType() == 'memory')
        {
            if(!is_resource($stream))
            {
                if($path = $this->getPath() && $this->isWritable() && !$this->_synchronised)
                {
                    try {
                        $handle = @fopen($path, 'w');
                    } catch (Exception $e) {
                        $handle = false;
                    }

                    if ($handle === false)
                    {
                        throw new \RuntimeException(sprintf(
                            'File "%s" cannot be opened with mode "%s"', $path, $this->getMode()
                        ));
                    }

                    //Flush the content to the underlying storage
                    $result = fwrite($handle, $this->_resource);
                }
            }
            //Flush the content to another stream
            else $result = fwrite($stream, $this->_resource);
        }

        return $result;
    }

    /**
     * Truncate to given size
     *
     * @param int $size The new size
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function truncate($size)
    {
        if($this->isWritable())
        {
            if($this->getType() == 'memory')
            {
                if ($this->_length > $size) {
                    $this->_resource = substr($this->_resource, 0, $size);
                } else {
                    $this->_resource = str_pad($this->_resource, $size, "\0", STR_PAD_RIGHT);
                }
            }
            else return parent::truncate($size);

            return true;
        }

        return false;
    }

    /**
     * Get the stream type
     *
     * @return string The stream type
     */
    public function getType()
    {
        if($this->_type == null) {
            $this->_type = parse_url($this->_path, PHP_URL_HOST);
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
        if($this->getType() == 'memory') {
            $this->_path = parse_url($this->_path, PHP_URL_PATH);
        }

        if($this->getType() == 'temp')
        {
            if(!$this->getResource()) {
                $this->_path = $this->getTemporaryFile();
            }
        }

        return parent::getPath();
    }

    /**
     * Get the stream mode
     *
     * @param bool $include_flags If false strip binary/text flags from mode. Default TRUE.
     * @return string
     */
    public function getMode($include_flags = true)
    {
        //Temp streams are always writable
        if($this->getType() == 'temp') {
            $this->_mode = 'w+b';
        }

        return parent::getMode($include_flags);
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
        if($this->getType() == 'memory')
        {
            $info = array('dev'     => 0,
                          'ino'     => 0,
                          'mode'    => 0,
                          'nlink'   => 0,
                          'uid'     => 0,
                          'gid'     => 0,
                          'rdev'    => 0,
                          'size'    => $this->_length,
                          'atime'   => time(),
                          'mtime'   => time(),
                          'ctime'   => time(),
                          'blksize' => -1,
                          'blocks'  => -1);
        }
        else $info = parent::getInfo($link);

        return $info;
    }

    /**
     * Creates a file with a unique file name
     *
     * @param string|null $directory Uses the result of getTemporaryDirectory() by default
     * @return string File path
     */
    public function getTemporaryFile($directory = null)
    {
        if ($directory === null) {
            $directory = $this->getTemporaryDirectory();
        }

        $name = str_replace('.', '', uniqid('buffer', true));
        $path = $directory.'/'.$name;

        touch($path);

        return $path;
    }

    /**
     * Returns a directory path for temporary files
     *
     * @return string Folder path
     */
    public function getTemporaryDirectory()
    {
        return sys_get_temp_dir();
    }

    /**
     * Check if the stream is seekable
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function isSeekable()
    {
        //Memory streams are always seekable
        if($this->getType() == 'memory') {
           return true;
        }

        return parent::isSeekable();
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
        if($this->getType() == 'memory') {
            return $this->_resource;
        }

        return parent::toString();
    }
}