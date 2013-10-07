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
 * Buffer FileSystem Stream Wrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
class FilesystemStreamWrapperBuffer extends FilesystemStreamWrapperAbstract
{
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
            'protocol' => 'buffer',
        ));
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

        $this->_path     = $path;
        $this->_protocol = $parts['scheme'];
        $this->_type     = $parts['host'];
        $this->_mode     = 'w+b'; //force to writeable
        $this->_data     = '';

        //Set the options
        $this->setOptions($options);

        //Set the mode
        $this->setMode($this->_mode);

        //Open the file or create a temp file
        if($this->_type == 'temp')
        {
            $this->_path = tempnam(sys_get_temp_dir(), 'temp');
            $this->_data = fopen($this->_path, $this->getMode());

            if ($options & STREAM_USE_PATH) {
                $opened_path = $this->_path;
            }
        }

        //Copy the file content into the stream
        if(isset($parts['path']))
        {
            $content = file_get_contents($parts['path']);

            if(is_resource($this->_data))
            {
                fwrite($this->_data, $content);
                fseek($this->_data, 0);

            }
            else $this->_data = $content;
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
            if(!is_resource($this->_data))
            {
                $data = substr($this->_data, $this->_position, $bytes);
                $this->_position += strlen($data);
            }
            else $data = fread($this->_data, $bytes);

            return $data;
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
            if(!is_resource($this->_data))
            {
                $left  = substr($this->_data, 0, $this->_position);
                $right = substr($this->_data, $this->_position + strlen($data));

                $this->_data = $left . $data . $right;
                $this->_position += strlen($data);
                $this->_length    = strlen($this->_data);
            }
            else fwrite($this->_data, $data);

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
        if(!is_resource($this->_data)) {
            $position =  $this->_position;
        } else {
            $position = ftell($this->_data);
        }

        return $position;
    }

    /**
     * Tells if we are at the end of the stream.
     *
     * @return boolean
     */
    public function stream_eof()
    {
        if(!is_resource($this->_data)) {
            $result = $this->_position >= $this->_length;
        } else {
            $result = feof($this->_data);
        }

        return $result;
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
        if(is_resource($this->_data))
        {
            fclose($this->_data);

            //Only unlink temporary files
            if($this->_type == 'temp') {
                unlink($this->getPath());
            }

            $this->_data = '';
            $this->_path = '';
        }
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
        if(!is_resource($this->_data))
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

        } else fseek($this->_data, $offset, $whence);

        return false;
    }

    /**
     * Truncate to given size
     *
     * @param int $size
     */
    public function stream_truncate($size)
    {
        if($this->_write)
        {
            if(!is_resource($this->_data))
            {
                if ($this->_length > $size) {
                    $this->_data = substr($this->_data, 0, $size);
                } else {
                    $this->_data = str_pad($this->_data, $size, "\0", STR_PAD_RIGHT);
                }

            } else ftruncate($this->_data, $size);

            return true;
        }

        return false;
    }

    /**
     * Return info about stream
     *
     * @return array
     */
    public function stream_stat()
    {
        if(!is_resource($this->_data))
        {
            $stat = array('dev'     => 0,
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
        else $stat = fstat($this->_data);

        return $stat;
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