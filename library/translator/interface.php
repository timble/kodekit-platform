<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Translator Interface
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Translator
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
     * Loads translations from a source.
     *
     * @param mixed $file     The file containing translations.
     * @param bool  $override Tells if previous loaded translations should be overridden
     * @return bool True if translations were loaded, false otherwise
     */
    public function load($file, $override = false);

    /**
     * Imports a source translations.
     *
     * @param mixed $source The source for which translations will be imported.
     *
     * @return TranslatorInterface
     */
    public function import($source);

    /**
     * Translations finder.
     *
     * Looks for translation files on the provided path.
     *
     * @param string $path The path to look for translations.
     * @return string|false The translation file, false in no translations file is found.
     */
    public function find($path);

    /**
     * Sets the locale
     *
     * @param string $locale
     * @return TranslatorInterface
     */
    public function setLocale($locale);

    /**
     * Gets the locale
     *
     * @return string|null
     */
    public function getLocale();

    /**
     * Set the fallback locale
     *
     * @param string $locale The fallback locale.
     * @return TranslatorInterface
     */
    public function setLocaleFallback($locale);

    /**
     * Get the fallback locale
     *
     * @return string The fallback locale.
     */
    public function getLocaleFallback();

    /**
     * Translator catalogue getter.
     *
     * @return TranslatorCatalogueInterface The translator catalogue.
     */
    public function getCatalogue();

    /**
     * Translator catalogue setter.
     *
     * @param TranslatorCatalogueInterface $catalogue
     * @return TranslatorInterface
     */
    public function setCatalogue(TranslatorCatalogueInterface $catalogue);

    /**
     * Checks if the translator can translate a string
     *
     * @param $string String to check
     * @return bool
     */
    public function isTranslatable($string);
}
