<?php
/**
 * @version		$Id$
 * @package		Koowa_Template
 * @subpackage  Stream
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

 /**
  * Stream wrapper to convert markup of mostly-PHP templates into PHP prior to include().
  *
  * Based in large part on the example at
  * http://www.php.net/manual/en/function.stream-wrapper-register.php
  *
  * @author     Johan Janssens <johan@nooku.org>
  * @package    Koowa_Template
  * @subpackage Stream
  */
class KTemplateStream implements KTemplateStreamInterface
{
    /**
     * Current stream position.
     *
     * @var int
     */
    protected $_pos = 0;

    /**
     * Template data
     *
     * @var string
     */
    protected $_data;

    /**
     * Stream stats.
     *
     * @var array
     */
    protected $_stat;

    /**
     * Template path
     *
     * @var string
     */
    protected $_path;

    /**
     * The template stack
     *
     * @var KTemplateStack
     */
    protected static $_stack;

    /**
     * Register the stream wrapper
     *
     * @param KTemplateStack $stack     The template stack object
     */
    public static function register(KTemplateStack $stack)
    {
        if (!in_array('tmpl', stream_get_wrappers())) {
            stream_wrapper_register('tmpl', __CLASS__);
        }

        //Store the template stack object
        self::$_stack = $stack;
    }

    /**
     * Opens the template file and converts markup.
     *
     * This function just gets the top object from the template stack.
     *
     * @param string  $path The stream path
     * @throws	\UnexpectedValueException	If the model doesn't implement the KModelInterface
     * @return boolean
     */
    public function stream_open($path)
    {
        //Get the template object from the template stack and parse it
        $template = self::$_stack->top();

        if(!$template instanceof KTemplateInterface)
        {
            throw new \UnexpectedValueException(
                'Template: '.get_class($template).' does not implement KTemplateInterface'
            );
        }

        //Get the template path
        $this->_path = $template->getPath();

        //Get the template data
        $this->_data = $template->getContents();

       // file_get_contents() won't update PHP's stat cache, so performing
       // another stat() on it will hit the filesystem again. Since the file
       // has been successfully read, avoid this and just fake the stat
       // so include() is happy.
        $this->_stat = array('mode' => 0100777, 'size' => strlen($this->_data));

        return true;
    }

    /**
     * Reads from the stream.
     *
     * @return string
     */
    public function stream_read($count)
    {
        $ret = substr($this->_data, $this->_pos, $count);
        $this->_pos += strlen($ret);
        return $ret;
    }

    /**
     * Tells the current position in the stream.
     *
     * @return int
     */
    public function stream_tell()
    {
        return $this->_pos;
    }

    /**
     * Tells if we are at the end of the stream.
     *
     * @return bool
     */
    public function stream_eof()
    {
        return $this->_pos >= strlen($this->_data);
    }

    /**
     * Stream statistics.
     *
     * @return array
     */
    public function stream_stat()
    {
        return $this->_stat;
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
     * @return bool  Always returns false as there is nounderlaying resource to return.
     */
    public function stream_cast($cast_as)
    {
        return false;
    }

    /**
     * Seek to a specific point in the stream.
     *
     * @param
     * @param
     * @return bool
     */
    public function stream_seek($offset, $whence)
    {
        switch ($whence)
        {
            case SEEK_SET:

                if ($offset < strlen($this->_data) && $offset >= 0) {
                $this->_pos = $offset;
                    return true;
                }
                else return false;
                break;

            case SEEK_CUR:

                if ($offset >= 0)
                {
                    $this->_pos += $offset;
                    return true;
                }
                else return false;
                break;

            case SEEK_END:

                if (strlen($this->_data) + $offset >= 0)
                {
                    $this->_pos = strlen($this->_data) + $offset;
                    return true;
                }
                else return false;
                break;

            default:
                return false;
        }
    }

    /**
     * Url statistics.
     *
     * This method is called in response to all stat() related functions on the stream
     *
     * @param   string  $path  The file path or URL to stat
     * @param   int     $flags Holds additional flags set by the streams API
     *
     * @return array
     */
    public function url_stat($path, $flags)
    {
        return $this->_stat;
    }
}