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
     * Get the stream path
     *
     * @return string The stream protocol
     */
    public function getPath()
    {
        return $this->_path;
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
}