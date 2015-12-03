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
     * Sets the language
     *
     * The language should be a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @param string $language
     * @return TranslatorLocatorInterface
     */
    public function setLanguage($language);

    /**
     * Gets the language
     *
     * Should return a properly formatted IETF language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string|null
     */
    public function getLanguage();

    /**
     * Locate the translation based on a physical path
     *
     * @param  string $url       The translation url
     * @return string  The real file path for the translation
     */
    public function locate($url);

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

    /**
     * Returns true if the translation is still fresh.
     *
     * @param  string $url    The translation url
     * @param int     $time   The last modification time of the cached translation (timestamp)
     * @return bool TRUE if the template is still fresh, FALSE otherwise
     */
    public function isFresh($url, $time);
}
