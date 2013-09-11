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
class FilesystemStreamWrapperAbstract implements FilesystemStreamWrapperInterface
{
    /**
     * The wrapper protocol
     */
    public static $protocol = '';

    /**
     * The wrapper type
     */
    public static $type = FileSystemStream::TYPE_UNKNOWN;

    /**
     * Register the stream wrapper
     *
     * @return bool
     */
    public static function register()
    {
        $result   = false;
        $protocol = self::getProtocol();

        if (!empty($protocol) && !in_array($protocol, stream_get_wrappers())) {
            $result = stream_wrapper_register(self::getProtocol(),  get_called_class());
        }

        return $result;
    }

    /**
     * Un Register the stream wrapper
     *
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
        $result = false;
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
        return static::$type;
    }

    /**
     * Get the stream protocol used to register the stream with
     *
     * @return string The stream protocol
     */
    public static function getProtocol()
    {
        return static::$protocol;
    }
}