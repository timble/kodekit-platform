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
 * Translator
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Koowa
 */
class Translator extends Library\Translator implements Library\ObjectMultiton, TranslatorInterface
{
    /**
     * Translator catalogue.
     *
     * A catalogue containing translations.
     *
     * @var TranslatorCatalogue
     */
    protected $_catalogue;

    /**
     * Fallback locale.
     *
     * @var string
     */
    protected $_fallback_locale;

    /**
     * Loaded components.
     *
     * @var array
     */
    protected $_loaded = array();

    /**
     * Tells if translations should be cached.
     *
     * @var bool
     */
    protected $_caching;

    /**
     * @param Library\ObjectConfig $config
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_fallback_locale = $config->fallback_locale;
        $this->_catalogue       = $config->catalogue;
        $this->_caching = $config->caching;

        if ($this->_caching && !extension_loaded('apc')) {
            throw new \RuntimeException('Translations cannot be cached since APC is not loaded.');
        }

        if ($this->_caching && ($data = $this->_getCacheData()))
        {
            $this->_loaded = $data['loaded'];
            $config->object_manager->setConfig($this->_catalogue, array('data' => $data['catalogue']));
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config Configuration options.
     *
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'caching'         => $this->getObject('application')->getCfg('caching'),
            'catalogue'       => 'com:application.translator.catalogue',
            'fallback_locale' => 'en-GB',
            'locale'          => 'en-GB',
            'options'         => array(
                'sources'           => array(),
                'caching_container' => 'application-translator-' . $config->object_identifier)
        ));

        parent::_initialize($config);
    }

    /**
     * Translates a string and handles parameter replacements
     *
     * @param string $string     String to translate
     * @param array  $parameters An array of parameters
     *
     * @return string Translated string
     */
    public function translate($string, array $parameters = array())
    {
        $catalogue = $this->getCatalogue();
        $result    = parent::translate($catalogue->hasString($string) ? $catalogue->{$string} : $string, $parameters);

        return $result;
    }

    /**
     * Translates a string based on the number parameter passed
     *
     * @param array   $strings    Strings to choose from
     * @param integer $number     The number of items
     * @param array   $parameters An array of parameters
     *
     * @throws \InvalidArgumentException
     * @return string Translated string
     */
    public function choose(array $strings, $number, array $parameters = array())
    {
        if (count($strings) < 2) {
            throw new \InvalidArgumentException('Choose method requires at least 2 strings to choose from');
        }

        $choice = Library\TranslatorInflector::getPluralPosition($number, $this->getLocale());

        if ($choice === 0) {
            return $this->translate($strings[0], $parameters);
        }

        $string = null;

        while ($choice > 0)
        {
            $candidate = $strings[1] . ($choice === 1 ? '' : '_' . $choice);

            if ($this->getCatalogue()->hasString($candidate))
            {
                $string = $candidate;
                break;
            }

            $choice--;
        }

        return $this->translate($string ? $string : $strings[1], $parameters);
    }

    /**
     * @see TranslatorInterface::load()
     */
    public function load($component, $source = null)
    {
        if (!isset($this->_loaded[$component]))
        {
            if ($source)
            {
                // Use provided base path.
                $sources = (array) $source;
            }
            elseif (!$sources = Library\ObjectConfig::unbox($this->getConfig()->options->sources))
            {
                throw new \RuntimeException('No sources for looking for translation files');
            }

            foreach ($sources as $source)
            {
                // Always override while importing through translator.
                if (($file = $this->_findTranslations($component, $source)) && ($translations = yaml_parse_file($file))) {
                    $this->getCatalogue()->import($translations, true);
                }
            }

            $this->_loaded[$component] = true;

            if ($this->_caching) {
                $this->_setCacheData(array('catalogue' => $this->getCatalogue()->toArray(), 'loaded' => $this->_loaded));
            }
        }
    }

    /**
     * Cache data getter.
     *
     * @return array Associative array containing translator data.
     */
    protected function _getCacheData()
    {
        $data = array();

        $container = $this->getConfig()->options->caching_container;

        if (apc_exists($container)) {
            $data = unserialize(apc_fetch($container));
        }

        return $data;
    }

    /**
     * Cache data setter
     *
     * @param $data array The data to be cached.
     *
     * @return $this TranslatorInterface
     */
    protected function _setCacheData($data)
    {
        apc_store($this->getConfig()->options->caching_container, serialize($data));
        return $this;
    }

    /**
     * Translations finder.
     *
     * @param string $component The component to look translations for.
     * @param string $path      The path to look for translation files.
     *
     * @return null|string The file path or null if a translation file wasn't found.
     */
    protected function _findTranslations($component, $path)
    {
        $file = null;

        $locale          = $this->getLocale();
        $fallback_locale = $this->getFallbackLocale();

        $locales = array($locale);

        if ($fallback_locale && ($locale !== $fallback_locale)) $locales[] = $fallback_locale;

        $file = null;

        foreach ($locales as $locale)
        {
            $candidate = $this->_getTranslationsFile($component, $path, $locale);

            if (file_exists($candidate))
            {
                $file = $candidate;
                break;
            }
        }

        return $file;
    }

    /**
     * Translations file getter.
     *
     * @param string $component The component name.
     * @param string $path      The base path.
     * @param string $locale    The translations locale.
     *
     * @return string The translations file.
     */
    protected function _getTranslationsFile($component, $path, $locale)
    {
        $folder = $this->_getTranslationsFolder($component, $path, $locale);
        $file   = $locale . '.yaml';

        return $path . $folder . $file;
    }

    /**
     * Translations folder getter.
     *
     * @param string $component The component name.
     * @param string $path      The base path.
     * @param string $locale    The translations locale.
     *
     * @return string The translations folder.
     */
    protected function _getTranslationsFolder($component, $path, $locale)
    {
        return "/component/{$component}/resources/language/";
    }

    /**
     * @see TranslatorInterface::parseTranslations
     */
    public function parseTranslations($file)
    {
        return yaml_parse_file($file);
    }

    /**
     * @see TranslatorInterface::setCatalogue()
     */
    public function setCatalogue(TranslatorCatalogueInterface $catalogue)
    {
        $this->_catalogue = $catalogue;
        return $this;
    }

    /**
     * @see TranslatorInterface::getCatalogue()
     */
    public function getCatalogue()
    {
        if (!$this->_catalogue instanceof TranslatorCatalogueInterface) {
            $this->setCatalogue($this->getObject($this->_catalogue));
        }

        return $this->_catalogue;
    }

    /**
     * @see TranslatorInterface::setFallbackLocale()
     */
    public function setFallbackLocale($locale)
    {
        $this->_fallback_locale = $locale;
        return $this;
    }

    /**
     * @see TranslatorInterface::getFallbackLocale()
     */
    public function getFallbackLocale()
    {
        return $this->_fallback_locale;
    }

    /**
     * Checks if a string is translatable.
     *
     * @param $string String to check
     *
     * @return bool
     */
    public function isTranslatable($string)
    {
        return $this->getCatalogue()->hasString($string);
    }
}
