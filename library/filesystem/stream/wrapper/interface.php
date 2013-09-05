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
 * FileSystem Stream Wrapper Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
interface FilesystemStreamWrapperInterface
{
    /**
     * Register the stream wrapper
     *
     * Function prevents from registering the wrapper twice
     * @return bool
     */
    public static function register();

    /**
     * Un Register the stream wrapper
     *
     *  Once the wrapper has been disabled you may override
     * @return bool
     */
    public static function unregister();

    /**
     * Check if the stream wrapper is registered
     *
     * @return bool TRUE if the path is a registered stream URL, FALSE otherwise.
     */
    public static function isRegistered();

    /**
     * Get the stream type
     *
     * @return string The stream type
     */
    public static function getType();

    /**
     * Get the stream protocol used to register the stream with
     *
     * @return string The stream protocol
     */
    public static function getProtocol();
}