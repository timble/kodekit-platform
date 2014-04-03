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
     * Catalogue setter.
     *
     * @param TranslatorCatalogueInterface $catalogue The catalogue.
     * @return TranslatorInterface this.
     */
    public function setCatalogue(TranslatorCatalogueInterface $catalogue);

    /**
     * Catalogue getter.
     *
     * @return TranslatorCatalogueInterface The catalogue.
     */
    public function getCatalogue();

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
     * Loads a component into the translator.
     *
     * @param string $component The component name.
     * @param mixed  $source    The source (location) to lookup for translation files.
     */
    public function load($component, $source = null);

    /**
     * Translations parser.
     *
     * @param string $file The translations file.
     * @return array|null Associative array containing the translations, null if not parsed.
     */
    public function parseTranslations($file);
}