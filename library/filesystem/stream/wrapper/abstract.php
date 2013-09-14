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
abstract class FilesystemStreamWrapperAbstract extends Object implements FilesystemStreamWrapperInterface
{
    /**
     * The wrapper protocol
     *
     * @var string
     */
    protected $_protocol;

    /**
     * The wrapper type
     *
     * @var string
     */
    protected $_type;

    /**
     * The wrapper path
     *
     * @var string
     */
    protected $_path;

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
     * The stream mode
     *
     * @var boolean
     */
    protected $_mode;

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
     * Object constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config = null)
    {
        //If stream is being constructed through object manager call parent.
        if($config instanceof ObjectConfig)
        {
            parent::__construct($config);

            $this->_protocol = $config->protocol;
            $this->_type     = $config->type;

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
            'protocol' => '',
            'type'     => FilesystemStreamInterface::TYPE_UNKNOWN
        ));
    }

    /**
     * Register the stream wrapper
     *
     * @return bool
     */
    public function register()
    {
        $result   = false;
        $protocol = $this->getProtocol();

        if (!empty($protocol) && !in_array($protocol, stream_get_wrappers())) {
            $result = stream_wrapper_register($protocol,  get_called_class());
        }

        return $result;
    }

    /**
     * Un Register the stream wrapper
     *
     * @return bool
     */
    public function unregister()
    {
        $result   = false;
        $protocol = $this->getProtocol();

        if ($this->isRegistered()){
            $result = stream_wrapper_unregister($protocol);
        }

        return $result;
    }

    /**
     * Check if the stream wrapper is registered
     *
     * @return bool TRUE if the path is a registered stream URL, FALSE otherwise.
     */
    public function isRegistered()
    {
        $result = false;
        if($protocol = $this->getProtocol()) {
            $result = in_array($protocol, stream_get_wrappers());
        }

        return $result;
    }

    /**
     * Get the stream type
     *
     * @return string The stream type
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get the stream protocol used to register the stream with
     *
     * @return string The stream protocol
     */
    public function getProtocol()
    {
        return $this->_protocol;
    }

    /**
     * Set the stream options
     *
     * @return string The stream options
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Set the stream options
     *
     * @param string $options Set the stream options
     */
    public function setOptions($options)
    {
        $this->_options = $options;
    }

    /**
     * Set the stream mode
     *
     * @return string The stream mode
     */
    public function getMode()
    {
        return $this->_mode;
    }

    /**
     * Set the stream mode
     *
     * @param $mode
     */
    public function setMode($mode)
    {
        $this->_mode = $mode; //store the raw mode

        //Strip binary/text flags from mode for comparison
        $mode = strtr($mode, array('b' => '', 't' => ''));

        switch ($mode)
        {
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
        $parts = parse_url($path); //parse the path

        $this->_mode     = $mode;
        $this->_path     = $path;
        $this->_protocol = $parts['scheme'];
        $this->_type     = $parts['host'];
        $this->_data     = '';

        //Set the options
        $this->setOptions($options);

        //Set the mode
        $this->setMode($mode);

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
        if (strpos($this->_mode, 't') && defined('PHP_WINDOWS_VERSION_MAJOR')) {
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