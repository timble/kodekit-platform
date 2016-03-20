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
 * Translator Interface
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Kodekit\Library\Translator\Interface
 */
interface TranslatorInterface
{
    /**
     * Translates a string and handles parameter replacements
     *
     * Parameters are wrapped in curly braces. So {foo} would be replaced with bar given that $parameters['foo'] = 'bar'
     *
     * @param string $string String to translate
     * @param array  $parameters An array of parameters
     * @return string Translated string
     */
    public function translate($string, array $parameters = array());

    /**
     * Translates a string based on the number parameter passed
     *
     * @param array   $strings Strings to choose from
     * @param integer $number The umber of items
     * @param array   $parameters An array of parameters
     * @throws \InvalidArgumentException
     * @return string Translated string
     */
    public function choose(array $strings, $number, array $parameters = array());

    /**
     * Loads translations from a url
     *
     * @param string $url      The translation url
     * @param bool   $override If TRUE override previously loaded translations. Default FALSE.
     * @return bool True if translations were loaded, false otherwise
     */
    public function load($file, $override = false);

    /**
     * Find translations from a url
     *
     * @param string $url      The translation url
     * @return array An array with physical file paths
     */
    public function find($url);

    /**
     * Sets the language
     *
     * The language should be a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     * @see $language
     *
     * @param string $language The language tag
     * @return TranslatorInterface
     */
    public function setLanguage($language);

    /**
     * Gets the language
     *
     * Should return a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string|null The language tag
     */
    public function getLanguage();

    /**
     * Set the fallback language
     *
     * The language should be a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     * @see $language
     *
     * @param string $language The fallback language tag
     * @return TranslatorInterface
     */
    public function setLanguageFallback($language);

    /**
     * Get the fallback language
     *
     * Should return a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string The language tag
     */
    public function getLanguageFallback();

    /**
     * Get the catalogue
     *
     * @throws	\UnexpectedValueException   If the catalogue doesn't implement the TranslatorCatalogueInterface
     * @return TranslatorCatalogueInterface The translator catalogue.
     */
    public function getCatalogue();

    /**
     * Set a catalogue
     *
     * @param   mixed   $catalogue An object that implements ObjectInterface, ObjectIdentifier object
     *                             or valid identifier string
     * @return TranslatorInterface
     */
    public function setCatalogue($catalogue);

    /**
     * Checks if the translator can translate a string
     *
     * @param $string String to check
     * @return bool
     */
    public function isTranslatable($string);

    /**
     * Checks if translations from a given url are already loaded.
     *
     * @param mixed $url The url to check
     * @return bool TRUE if loaded, FALSE otherwise.
     */
    public function isLoaded($url);
}
