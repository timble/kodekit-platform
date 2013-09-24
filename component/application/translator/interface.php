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
     * Components loader.
     *
     * Load components into the translator.
     *
     * @param array $components The list of components to load. The array may be indexed, associative or a combination
     *                          of both. If a non-numeric key is found, the key represents a subcomponent e.g. a module, while the value points
     *                          to its component.
     */
    public function load($components);
}