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
 * FileSystem Stream Filter Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\FileSystem\Stream\Filter\Abstract
 */
abstract class FilesystemStreamFilterAbstract extends \php_user_filter implements FilesystemStreamFilterInterface
{
    /**
     * The filter name
     *
     * @var string
     */
    protected static $_name = '';

    /**
     * The filter name
     *
     * String containing the name the filter was instantiated with. Filters may be registered under multiple names or
     * under wildcards. Use this property to determine which name was used.
     *
     * @var string
     * @see \php_user_filter
     */
    public $filtername;

    /**
     * The stream being filtered
     *
     * The stream resource being filtered. Maybe available only during filter() calls when the closing parameter is
     * set to FALSE.
     *
     * @var resource
     * @see \php_user_filter
     */
    public $stream;

    /**
     * The filter params
     *
     * The contents of the params parameter passed to stream_filter_append() or stream_filter_prepend().
     *
     * @var array
     * @see \php_user_filter
     */
    public $params;

    /**
     * Register the stream filter
     *
     * @return bool
     */
    public static function register()
    {
        $result = false;
        $name   = self::getName();

        if (!empty($name) && !in_array($name, stream_get_filters())) {
            $result = stream_filter_register(self::getName(), get_called_class());
        }

        return $result;
    }

    /**
     * Get the filter name
     *
     * @return string The filter name
     */
    public static function getName()
    {
        return static::$_name;
    }

    /**
     * Called the filter is created
     *
     * @return bool Return FALSE on failure, or TRUE on success.
     */
    public function onCreate()
    {
        //do nothing
        return true;
    }

    /**
     * Called when closing the filter
     *
     * This method is called upon filter shutdown (typically, this is also during stream shutdown), and is executed after
     * the flush method is called. If any resources were allocated or initialized during onCreate() this would be the time
     * to destroy or dispose of them.
     *
     * @return void
     */
    public function onClose()
    {
        //do nothing
    }

    /**
     * Check if the stream filter is registered
     *
     * @return bool TRUE if the filter is registeredL, FALSE otherwise.
     */
    public static function isRegistered()
    {
        $result = false;
        if($name = self::getName()) {
            $result = in_array($name, stream_get_wrappers());
        }

        return $result;
    }
}