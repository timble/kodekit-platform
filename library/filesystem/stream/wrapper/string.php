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
 * FileSystem String Stream Wrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
class FilesystemStreamWrapperString extends FilesystemStreamWrapperAbstract
{
    /**
     * The wrapper protocol
     */
    public static $protocol = 'string';

    /**
     * The wrapper type
     */
    public static $type = FileSystemStream::TYPE_STRING;

    /**
     * Current stream position
     *
     * @var int
     */
    protected $_position;

    /**
     * The stream length
     *
     * @var int
     */
    protected $_length;

    /**
     * Content of stream
     *
     * @var string
     */
    protected $_data;

    /**
     * Whether this stream can be read
     *
     * @var boolean
     */
    protected $_read;

    /**
     * Whether this stream can be written
     *
     * @var boolean
     */
    protected $_write;

    /**
     * Whether this stream should have UNIX-style newlines converted to Windows-style
     *
     * @var boolean
     */
    protected $_normaliseForWin = false;

    /**
     * Options
     *
     * @var int
     */
    protected $_options;

    /**
     * Register the stream wrapper
     *
     * Function prevents from registering the wrapper twice
     * @return bool
     */
    public static function register()
    {
        $result = false;
        if (!in_array(self::getProtocol(), stream_get_wrappers())) {
            $result = stream_wrapper_register(self::getProtocol(), __CLASS__);
        }

        return $result;
    }

    /**
     * Un Register the stream wrapper
     *
     *  Once the wrapper has been disabled you may override
     * @return bool
     */
    public static function unregister()
    {
        $result = false;
        if (self::isRegistered()) {
            $result = stream_wrapper_unregister(self::getProtocol());
        }

        return $result;
    }

    /**
     * Check if the stream wrapper is registered
     *
     * @return bool TRUE if the path is a registered stream URL, FALSE otherwise.
     */
    public static function isRegistered()
    {
        $result   = false;
        if($protocol = self::getProtocol()) {
            $result = in_array($protocol, stream_get_wrappers());
        }

        return $result;
    }

    /**
     * Get the stream type
     *
     * @return string The stream type
     */
    public static function getType()
    {
        return self::$type;
    }

    /**
     * Get the stream protocol used to register the stream with
     *
     * @return string The stream protocol
     */
    public static function getProtocol()
    {
        return self::$protocol;
    }

    /**
     * Open stream
     *
     * @param string    $path
     * @param string    $mode
     * @param int       $options
     * @param string    $opended_path
     *
     * @return boolean
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->_data = substr($path, strpos($path, '://') + 3);
        $this->_options = $options;

        if (strpos($mode, 't') && defined('PHP_WINDOWS_VERSION_MAJOR'))
        {
            $this->_normaliseForWin = true;
            $this->_data = preg_replace('/(?<!\r)\n/', "\r\n", $this->_data);
        }

        //Calculate the stream length
        $this->_length = strlen($this->_data);

        //Strip binary/text flags from mode for comparison
        $mode = strtr($mode, array('b' => '', 't' => ''));

        switch ($mode) {

            case 'r':
                $this->_read     = true;
                $this->_write    = false;
                $this->_position = 0;
                break;

            case 'r+':
            case 'c+':
                $this->_read     = true;
                $this->_write    = true;
                $this->_position = 0;
                break;

            case 'w':
                $this->_read     = false;
                $this->_write    = true;
                $this->_position = 0;
                $this->stream_truncate(0);
                break;

            case 'w+':
                $this->_read      = true;
                $this->_write     = true;
                $this->_position  = 0;
                $this->stream_truncate(0);
                break;

            case 'a':
                $this->_read     = false;
                $this->_write    = true;
                $this->_position = strlen($this->_data);
                break;

            case 'a+':
                $this->_read     = true;
                $this->_write    = true;
                $this->position  = strlen($this->_data);
                break;

            case 'c':
                $this->_read     = false;
                $this->_write    = true;
                $this->_position = 0;
                break;

            default:
                if ($this->_options & STREAM_REPORT_ERRORS) {
                    trigger_error('Invalid mode specified (mode specified makes no sense for this stream implementation)', E_ERROR);
                } else {
                    return false;
                }
        }

        return true;
    }

    /**
     * Read from the stream
     *
     * @param int $bytes Number of bytes to return
     * @return string
     */
    public function stream_read($bytes)
    {
        if ($this->_read)
        {
            $read = substr($this->_data, $this->_position, $bytes);
            $this->_position += strlen($read);
            return $read;
        }

        return false;
    }

    /**
     * Write to the stream
     *
     * @param string $data Data to write
     * @return int
     */
    public function stream_write($data)
    {
        if ($this->_normaliseForWin) {
            $data = preg_replace('/(?<!\r)\n/', "\r\n", $data);
        }

        if ($this->_write)
        {
            $left  = substr($this->_data, 0, $this->_position);
            $right = substr($this->_data, $this->_position + strlen($data));

            $this->_data = $left . $data . $right;
            $this->_position += strlen($data);
            $this->_length    = strlen($this->_data);

            return strlen($data);
        }

        return 0;
    }

    /**
     * Tells the current position in the stream.
     *
     * @return int
     */
    public function stream_tell()
    {
        return $this->_position;
    }

    /**
     * Tells if we are at the end of the stream.
     *
     * @return boolean
     */
    public function stream_eof()
    {
        return $this->_position >= $this->_length;
    }

    /**
     * Flushes the output
     *
     * @return boolean
     */
    public function stream_flush()
    {
        return false;
    }

    /**
     * Close the stream
     *
     * @return void
     */
    public function stream_close()
    {

    }

    /**
     * Signal that stream_select is not supported by returning false
     *
     * @param  int   $cast_as Can be STREAM_CAST_FOR_SELECT or STREAM_CAST_AS_STREAM
     * @return bool  Always returns false as there is no underlying resource to return.
     */
    public function stream_cast($cast_as)
    {
        return false;
    }

    /**
     *  Seek to a specific position in the stream.
     *
     * @param int $offset
     * @param int $whence Can be SEEK_SET, SEEK_CUR or SEEK_END
     * @return boolean
     */
    public function stream_seek($offset, $whence)
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

        return false;
    }

    /**
     * Truncate to given size
     *
     * @param int $size
     */
    public function stream_truncate($size)
    {
        if ($this->_length > $size) {
            $this->string = substr($this->_data, 0, $size);
        } else {
            $this->_data = str_pad($this->_data, $size, "\0", STR_PAD_RIGHT);
        }

        return true;
    }

    /**
     * Return info about stream
     *
     * @return array
     */
    public function stream_stat()
    {
        return array('dev'     => 0,
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

    /**
     * Return info about stream
     *
     * @param string    $path
     * @param array     $options
     * @return array
     */
    public function url_stat($path, $options)
    {
        return $this->stream_stat();
    }
}