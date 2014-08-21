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
 * Component Template Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Locator\Abstract
 */
abstract class TemplateLocatorAbstract extends Object implements TemplateLocatorInterface, ObjectMultiton
{
    /**
     * The stream name
     *
     * @var string
     */
    protected static $_name = '';

    /**
     * Get the locator name
     *
     * @return string The stream name
     */
    public static function getName()
    {
        return static::$_name;
    }

    /**
     * Locate the template based on a virtual path
     *
     * @param  string $url   The Template url
     * @param  string $base  The base url or resource (used to resolved partials).
     * @throws \RuntimeException If the no base path was passed while trying to locate a partial.
     * @return string   The physical path of the template
     */
    public function locate($url, $base = null)
    {
        $info = array(
            'url'   => $url,
            'base'  => $base,
            'path'  => '',
        );

        return $this->find($info);
    }

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    final public function realPath($file)
    {
        $result = false;
        $path   = dirname($file);

        // Is the path based on a stream?
        if (strpos($path, '://') === false)
        {
            // Not a stream, so do a realpath() to avoid directory traversal attempts on the local file system.
            $path = realpath($path); // needed for substr() later
            $file = realpath($file);
        }

        // The substr() check added to make sure that the realpath() results in a directory registered so that
        // non-registered directories are not accessible via directory traversal attempts.
        if (file_exists($file) && substr($file, 0, strlen($path)) == $path) {
            $result = $file;
        }

        return $result;
    }
}