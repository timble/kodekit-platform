<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Translator Interface
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Koowa
 */
interface TranslatorInterface extends Library\TranslatorInterface
{
    /**
     * Fallback locale setter.
     *
     * @param string $locale The fallback locale.
     * @return TranslatorInterface this.
     */
    public function setFallbackLocale($locale);

    /**
     * Fallback locale getter.
     *
     * @return string The fallback locale.
     */
    public function getFallbackLocale();

    /**
     * Translations finder.
     *
     * Looks for translation files on the provided path.
     *
     * @param string $path The path to look for translations.
     *
     * @return string|false The translation file, false in no translations file is found.
     */
    public function find($path);
}