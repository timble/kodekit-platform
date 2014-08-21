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
 * Abstract Translator Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Translator\Locator\Abstract
 */
abstract class TranslatorLocatorAbstract extends Object implements TranslatorLocatorInterface
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
     * Locate the translation based on a physical path
     *
     * @param  string $url       The translation url
     * @param  string $locale    The locale to search for
     * @return string  The real file path for the translation
     */
    public function locate($url, $locale)
    {
        $info   = array(
            'url'     => $url,
            'locale'  => $locale,
            'path'    => '',
        );

        return $this->find($info);
    }

    /**
     * Find a translation path
     *
     * @param array  $info  The path information
     * @return array
     */
    public function find(array $info)
    {
        $result = array();

        if($info['path'] && $info['locale'])
        {
            $pattern = $info['path'].'/'.$info['locale'].'.*';

            foreach(glob($pattern) as $file)
            {
                if($path = $this->realPath($file))
                {
                    $result[] = $path;
                    break;
                }
            }
        }

        return $result;
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
