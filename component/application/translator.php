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
     * @param Library\ObjectConfig $config
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_fallback_locale = $config->fallback_locale;
        $this->_catalogue       = $config->catalogue;
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
            'catalogue'       => 'com:application.translator.catalogue',
            'fallback_locale' => 'en-GB',
            'locale'          => 'en-GB'
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
        $result = '';

        if ($key = $this->getKey($string))
        {
            $catalogue = $this->getCatalogue();

            $result = parent::translate($catalogue->hasKey($key) ? $catalogue->{$key} : $string, $parameters);
        }

        return $result;
    }

    /**
     * Translates a string based on the number parameter passed
     *
     * @param array   $strings    Strings to choose from
     * @param integer $number     The number of items
     * @param array   $parameters An array of parameters
     *
     * @throws InvalidArgumentException
     * @return string Translated string
     */
    public function choose(array $strings, $number, array $parameters = array())
    {
        if (count($strings) < 2)
        {
            throw new InvalidArgumentException('Choose method requires at least 2 strings to choose from');
        }

        $choice = Library\TranslatorInflector::getPluralPosition($number, $this->getLocale());

        if ($choice === 0)
        {
            return $this->translate($strings[0], $parameters);
        }

        $key   = $this->getKey($strings[1]);
        $found = null;

        while ($choice > 0)
        {
            $looking_for = $key . ($choice === 1 ? '' : '_' . $choice);
            if ($this->getCatalogue()->hasKey($looking_for))
            {
                $found = $looking_for;
                break;
            }

            $choice--;
        }

        return $this->translate($found ? $found : $strings[1], $parameters);
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
        return $this->getCatalogue()->hasKey($this->getKey($string));
    }

    /**
     * Translation key getter.
     *
     * @param string $string String to translate.
     *
     * @return string The translation key.
     */
    public function getKey($string)
    {
        // TODO: Just returning upper cased string for now, since current language file keys are upper cased.
        return strtoupper($string);
    }

    /**
     * @see TranslatorInterface::load()
     */
    public function load($component, $subcomponent = null, $base_path = null)
    {
        $signature = $component;

        if ($subcomponent)
        {
            $signature .= '.' . (string) $subcomponent;
        }

        if (!isset($this->_loaded[$signature])) {

            if ($base_path) {
                // Use provided base path only.
                $paths = (array) $base_path;
            } else {
                // Default fallback/override sequence.
                $paths = array(JPATH_ROOT, JPATH_APPLICATION);
            }

            foreach ($paths as $path)
            {
                if (($file = $this->_getLanguageFile($signature,
                        $path)) && ($translations = $this->_loadLanguageFile($file)))
                {
                    // Always override while importing through translator.
                    $this->getCatalogue()->import($translations, true);
                }
            }

            $this->_loaded[$signature] = true;
        }
    }

    /**
     * Language file getter.
     *
     * Returns a language file for the provided signature.
     *
     * @param string $signature A string representing a component or one of its subcomponents.
     * @param string $base_path The base path to look for language files.
     *
     * @return null|string The file path or null if a language file wasn't found.
     */
    protected function _getLanguageFile($signature, $base_path = JPATH_BASE)
    {
        $file = null;

        $locale          = $this->getLocale();
        $fallback_locale = $this->getFallbackLocale();

        $locales = array($locale);

        if ($fallback_locale && ($locale !== $fallback_locale)) $locales[] = $fallback_locale;

        $parts = explode('.', $signature);

        $string = $base_path . $this->_getLanguageFolder($parts[0]) . '%s';

        if (isset($parts[1]))
        {
            $string .= '.' . $parts[1];
        }

        $string .= '.ini';

        foreach ($locales as $locale)
        {
            $candidate = sprintf($string, $locale);

            if (file_exists($candidate))
            {
                $file = $candidate;
                break;
            }
        }

        return $file;
    }

    /**
     * Language folder getter.
     *
     * @param string $component The component name.
     *
     * @return string The language folder.
     */
    protected function _getLanguageFolder($component)
    {
        return "/component/{$component}/resources/language/";
    }

    /**
     * Language File loader.
     *
     * @param string $file The file path.
     *
     * @return array|bool The file content, false if not loaded.
     */
    protected function _loadLanguageFile($file)
    {
        $result = false;

        if ($content = @file_get_contents($file))
        {
            //Take off BOM if present in the ini file
            if ($content[0] == "\xEF" && $content[1] == "\xBB" && $content[2] == "\xBF")
            {
                $content = substr($content, 3);
            }

            // TODO: Review other formats for language files and get rid of the JRegistry dependency afterwards.
            $registry = new \JRegistry();
            $registry->loadINI($content);
            $result = $registry->toArray();
        }

        return $result;
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

        if (!$this->_catalogue instanceof TranslatorCatalogueInterface)
        {
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
}
