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
 * Translator Catalogue
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */
class TranslatorCatalogue extends ObjectArray implements TranslatorCatalogueInterface
{
    /**
     * List containing sources of loaded translations.
     *
     * @var array
     */
    protected $_sources;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_sources = array();
    }

    /**
     * Load translations into the catalogue.
     *
     * @param array  $translations Associative array containing translations.
     * @param bool   $override     Whether or not existing translations can be overridden during import.
     * @return bool True on success, false otherwise.
     */
    public function load($translations, $override = false)
    {
        if ($override) {
            $this->_data = array_merge($this->_data, $translations);
        } else {
            $this->_data = array_merge($translations, $this->_data);
        }

        return true;
    }

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
     * @return TranslatorCatalogue
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
     * @return TranslatorCatalogue
     */
    public function remove($string)
    {
        $this->offsetUnset($string);
        return $this;
    }

    /**
     * Clears out all strings from the registry
     *
     * @return  TranslatorCatalogue
     */
    public function clear()
    {
        $this->_data = array();
        return $this;
    }

    /**
     * Get a list of all strings in the catalogue
     *
     * @return  array
     */
    public function getStrings()
    {
        return array_keys($this->_data);
    }

    /**
     * Get a list of all sources that are loaded
     *
     * @return  array
     */
    public function getSources()
    {
        return $this->_sources;
    }

    /**
     * Sets a source as loaded in the catalogue.
     *
     * A source can be anything that contains translations, e.g. a component, an object, a file, an URI, etc. They
     * are referenced on catalogues for determining if their translations were already loaded.
     *
     * @param mixed $source The source.
     * @return TranslatorCatalogue
     */
    public function setLoaded($source)
    {
        $this->_sources[] = $source;
        $this->_sources   = array_unique($this->_sources);

        return $this;
    }

    /**
     * Tells if translations from a given source are already loaded.
     *
     * For more information about what a source is @see TranslatorCatalogueInterface::setLoaded
     *
     * @param mixed $source The source to check against.
     * @return bool True if loaded, false otherwise.
     */
    public function isLoaded($source)
    {
        return in_array($source, $this->_sources);
    }
}
