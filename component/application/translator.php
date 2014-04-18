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
                'paths' => array())
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
     * @return TranslatorInterface
     */
    public function import($component)
    {
        $catalogue = $this->getCatalogue();

        // Append current locale to source.
        $source = 'com:' . $component . '.' . $this->getLocale();

        if (!$catalogue->isLoaded($source))
        {
            $paths = $this->getConfig()->options->paths;

            foreach ($paths as $path)
            {
                $path .= "/component/{$component}/resources/language/";

                if (($file = $this->find($path)) && !$this->load($file, true))
                {
                    throw new \RuntimeException('Unable to load translations from .' . $file);
                }
            }

            // Set component as loaded.
            $catalogue->setLoaded($source);
        }

        return $this;
    }

    public function find($path)
    {
        $locale          = $this->getLocale();
        $fallback_locale = $this->getFallbackLocale();

        $locales = array($locale);

        if ($fallback_locale && ($locale !== $fallback_locale)) $locales[] = $fallback_locale;

        $file = null;

        foreach ($locales as $locale)
        {
            $candidate = $path . $locale . '.' . $this->getParser()->getFileExtension();

            if (file_exists($candidate))
            {
                $file = $candidate;
                break;
            }
        }

        return $file;
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
