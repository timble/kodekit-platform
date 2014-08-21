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
 * Translator Locator Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Translator\Locator\Interface
 */
interface TranslatorLocatorInterface
{
    /**
     * Get the locator name
     *
     * @return string The stream name
     */
    public static function getName();

    /**
     * Locate the translation based on a physical path
     *
     * @param  string $url       The translation url
     * @param  string $locale    The locale to search for
     * @return string  The real file path for the translation
     */
    public function locate($url, $locale);

    /**
     * Find a translation path
     *
     * @param array  $info  The path information
     * @return array
     */
    public function find(array $info);

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    public function realPath($file);
}
