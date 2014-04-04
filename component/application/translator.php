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
class Translator extends Library\TranslatorAbstract implements Library\ObjectMultiton, TranslatorInterface
{
    /**
     * Fallback locale.
     *
     * @var string
     */
    protected $_fallback_locale;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_fallback_locale = $config->fallback_locale;
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
            'fallback_locale' => 'en-GB',
            'locale'          => 'en-GB',
            'options'         => array(
                'sources' => array())
        ))->append(array('catalogue' => 'com:application.translator.catalogue' . ($config->caching ? '.cache' : '')));

        parent::_initialize($config);
    }

    /**
     * Imports component translations.
     *
     * @param string $component The component name.
     *
     * @throws \RuntimeException if a translation file is not loaded.
     *
     * @return bool True on success, false otherwise.
     */
    public function import($component) {

        $catalogue = $this->getCatalogue();

        if (!$catalogue->isLoaded($component))
        {
            $sources = Library\ObjectConfig::unbox($this->getConfig()->options->sources);

            foreach ($sources as $source)
            {
                if ($file = $this->_findTranslations($component, $source))
                {
                    // Always override while loading.
                    if (!$this->load($file, true)
                    ) throw new \RuntimeException('Unable to load translations from .' . $file);
                }
            }

            // Set component as loaded.
            $catalogue->setLoaded($component);
        }
    }

    /**
     * Translations finder.
     *
     * @param string $component The component to look for translations.
     * @param string $path      The path to look for translation files.
     *
     * @return null|string The file path or null if a translation file wasn't found.
     */
    protected function _findTranslations($component, $path)
    {
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
        $file   = $locale . '.' . $this->getParser()->getFileExtension();

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
