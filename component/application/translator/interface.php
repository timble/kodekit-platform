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
     *
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
     * Key getter.
     *
     * @param $string The translation string.
     *
     * @return string The string key.
     */
    public function getKey($string);

    /**
     * Fallback locale setter.
     *
     * @param string $locale The fallback locale.
     *
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
     * @param string $component    The component name.
     * @param mixed  $subcomponent The subcomponent name.
     * @param mixed  $base_path    The base path to load language files from.
     */
    public function load($component, $subcomponent = null, $base_path = null);
}