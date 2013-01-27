<?php
/**
 * @version		$Id$
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

 /**
  * Stream template wrapper interface
  *
  * The stream template wrapper will retrieve the template from the KTemplateStack using the stream path.
  *
  * @author     Johan Janssens <johan@nooku.org>
  * @category   Koowa
  * @package    Koowa_Template
  */
interface KTemplateStreamInterface
{
    /**
     * Register the stream wrapper
     *
     * Function prevents from registering the wrapper twice
     *
     * @param KTemplateStack $stack     The template stack object
     */
    public static function register(KTemplateStack $stack);

    /**
     * Opens the template file and converts markup.
     *
     * @param string    The stream path
     * @return boolean
     */
    public function stream_open($path);

    /**
     * Reads from the stream.
     *
     * @return string
     */
    public function stream_read($count);

    /**
     * Tells the current position in the stream.
     *
     * @return int
     */
    public function stream_tell();

    /**
     * Tells if we are at the end of the stream.
     *
     * @return bool
     */
    public function stream_eof();

    /**
     * Stream statistics.
     *
     * @return array
     */
    public function stream_stat();

    /**
     * Flushes the output
     *
     * @return boolean
     */
    public function stream_flush();

    /**
     * Close the stream
     *
     * @return void
     */
    public function stream_close();

	/**
     * Signal that stream_select is not supported by returning false
     *
     * @param  int   Can be STREAM_CAST_FOR_SELECT or STREAM_CAST_AS_STREAM
     * @return bool  Always returns false as there is nounderlaying resource to return.
     */
    public function stream_cast($cast_as);

    /**
     * Seek to a specific point in the stream.
     *
     * @return bool
     */
    public function stream_seek($offset, $whence);

    /**
     * Url statistics.
     *
     * This method is called in response to all stat() related functions on the stream
     *
     * @param   string  The file path or URL to stat
     * @param   int     Holds additional flags set by the streams API
     *
     * @return array
     */
    public function url_stat($path, $flags);
}