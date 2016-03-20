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
 * Translator Catalogue
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Kodekit\Library\Translator\Catalogue\Abstract
 */
abstract class TranslatorCatalogueAbstract extends ObjectArray implements TranslatorCatalogueInterface
{
    /**
     * Get a string from the registry
     *
     * @param  string $string
     * @return  string  The translation of the string
     */
    public function get($string)
    {
        return $this->offsetGet($string);
    }

    /**
     * Set a string in the registry
     *
     * @param  string $string
     * @param  string $translation
     * @return TranslatorCatalogueAbstract
     */
    public function set($string, $translation)
    {
        $this->offsetSet($string, $translation);
        return $this;
    }

    /**
     * Check if a string exists in the registry
     *
     * @param  string $string
     * @return boolean
     */
    public function has($string)
    {
        return $this->offsetExists((string) $string);
    }

    /**
     * Remove a string from the registry
     *
     * @param  string $string
     * @return TranslatorCatalogueAbstract
     */
    public function remove($string)
    {
        $this->offsetUnset($string);
        return $this;
    }

    /**
     * Add translations to the catalogue.
     *
     * @param array  $translations Associative array containing translations.
     * @param bool   $override     If TRUE override existing translations. Default is FALSE.
     * @return bool True on success, false otherwise.
     */
    public function add(array $translations, $override = false)
    {
        if ($override) {
            $this->_data = array_merge($this->_data, $translations);
        } else {
            $this->_data = array_merge($translations, $this->_data);
        }

        return true;
    }

    /**
     * Clears out all strings from the registry
     *
     * @return  TranslatorCatalogueAbstract
     */
    public function clear()
    {
        $this->_data = array();
        return $this;
    }
}
