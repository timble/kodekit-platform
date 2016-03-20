<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Translation Catalogue Interface
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Kodekit\Library\Translator\Catalogue\Interface
 */
interface TranslatorCatalogueInterface extends \IteratorAggregate, \ArrayAccess, \Serializable
{
    /**
     * Get a string from the registry
     *
     * @param  string $string
     * @return  string  The translation of the string
     */
    public function get($string);

    /**
     * Set a string in the registry
     *
     * @param  string $string
     * @param  string $translation
     * @return TranslatorCatalogueInterface
     */
    public function set($string, $translation);

    /**
     * Check if a string exists in the registry
     *
     * @param  string $string
     * @return boolean
     */
    public function has($string);

    /**
     * Remove a string from the registry
     *
     * @param  string $string
     * @return TranslatorCatalogueInterface
     */
    public function remove($string);

    /**
     * Add translations to the catalogue.
     *
     * @param array  $translations Associative array containing translations.
     * @param bool   $override     If TRUE override existing translations. Default is FALSE.
     * @return bool True on success, false otherwise.
     */
    public function add(array $translations, $override = false);

    /**
     * Clears out all strings from the registry
     *
     * @return  TranslatorCatalogueInterface
     */
    public function clear();

    /**
     * Get a list of all strings in the catalogue
     *
     * @return  array
     */
    public function toArray();
}
