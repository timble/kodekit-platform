<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Translator Cache
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Nooku\Library\Translator\Cache
 */
class TranslatorCache extends ObjectDecorator implements TranslatorInterface
{
    /**
     * The registry cache namespace
     *
     * @var boolean
     */
    protected $_namespace = 'nooku';

    /**
     * List of url that have been loaded.
     *
     * @var array
     */
    protected $_loaded;

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  A ObjectConfig object with optional configuration options
     * @throws \RuntimeException    If the APC PHP extension is not enabled or available
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (!self::isSupported()) {
            throw new \RuntimeException('Unable to use TranslatorCache. APC is not enabled.');
        }

        $this->_loaded = array();
    }

    /**
     * Get the registry cache namespace
     *
     * @param string $namespace
     * @return TranslatorCache
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
        return $this;
    }

    /**
     * Get the registry cache namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Translates a string and handles parameter replacements
     *
     * Parameters are wrapped in curly braces. So {foo} would be replaced with bar given that $parameters['foo'] = 'bar'
     * 
     * @param string $string String to translate
     * @param array  $parameters An array of parameters
     * @return string Translated string
     */
    public function translate($string, array $parameters = array())
    {
        return $this->getDelegate()->translate($string, $parameters);
    }

    /**
     * Translates a string based on the number parameter passed
     *
     * @param array   $strings Strings to choose from
     * @param integer $number The number of items
     * @param array   $parameters An array of parameters
     * @throws \InvalidArgumentException
     * @return string Translated string
     */
    public function choose(array $strings, $number, array $parameters = array())
    {
        return $this->getDelegate()->choose($strings, $number, $parameters);
    }

    /**
     * Loads translations from a url
     *
     * @param string $url      The translation url
     * @param bool   $override If TRUE override previously loaded translations. Default FALSE.
     * @return bool TRUE if translations are loaded, FALSE otherwise
     */
    public function load($url, $override = false)
    {
        if (!$this->isLoaded($url))
        {
            $translations = array();
            $prefix       = $this->getNamespace().'-translator-'.$this->getLocale();

            if(!apc_exists($prefix.'_'.$url))
            {
                foreach($this->find($url) as $file)
                {
                    try {
                        $loaded = $this->getObject('object.config.factory')->fromFile($file)->toArray();
                    } catch (\Exception $e) {
                        return false;
                    }

                    $translations = array_merge($translations, $loaded);
                }

                apc_store($prefix.'_'.$url, $translations);
            }
            else $translations = apc_fetch($prefix.'_'.$url);

            //Add the translations to the catalogue
            $this->getCatalogue()->add($translations, $override);

            $this->_loaded[] = $url;
        }

        return true;
    }

    /**
     * Find translations from a url
     *
     * @param string $url      The translation url
     * @return array An array with physical file paths
     */
    public function find($url)
    {
        return $this->getDelegate()->find($url);
    }

    /**
     * Sets the locale
     *
     * @param string $locale
     * @return TranslatorCache
     */
    public function setLocale($locale)
    {
        $this->getDelegate()->setLocale($locale);
        return $this;
    }

    /**
     * Gets the locale
     *
     * @return string|null
     */
    public function getLocale()
    {
        return $this->getDelegate()->getLocale();
    }

    /**
     * Set the fallback locale
     *
     * @param string $locale The fallback locale
     * @return TranslatorCache
     */
    public function setLocaleFallback($locale)
    {
        $this->getDelegate()->setLocaleFallback($locale);
        return $this;
    }

    /**
     * Set the fallback locale
     *
     * @return string
     */
    public function getLocaleFallback()
    {
        return $this->getDelegate()->getLocaleFallback();
    }

    /**
     * Get the catalogue
     *
     * @throws \UnexpectedValueException    If the catalogue doesn't implement the TranslatorCatalogueInterface
     * @return TranslatorCatalogueInterface The translator catalogue.
     */
    public function getCatalogue()
    {
        return $this->getDelegate()->getCatalogue();
    }

    /**
     * Set a catalogue
     *
     * @param   mixed   $catalogue An object that implements ObjectInterface, ObjectIdentifier object
     *                             or valid identifier string
     * @return TranslatorInterface
     */
    public function setCatalogue($catalogue)
    {
        return $this->getDelegate()->setCatalogue($catalogue);
    }

    /**
     * Checks if translations from a given url are already loaded.
     *
     * @param mixed $url The url to check
     * @return bool TRUE if loaded, FALSE otherwise.
     */
    public function isLoaded($url)
    {
        return in_array($url, $this->_loaded);
    }

    /**
     * Checks if the translator can translate a string
     *
     * @param $string String to check
     * @return bool
     */
    public function isTranslatable($string)
    {
        return $this->getDelegate()->isTranslatable($string);
    }

    /**
     * Checks if the APC PHP extension is enabled
     *
     * @return bool
     */
    public static function isSupported()
    {
        return extension_loaded('apc');
    }

    /**
     * Set the decorated translator
     *
     * @param   TranslatorInterface $delegate The decorated translator
     * @return  TranslatorCache
     * @throws \InvalidArgumentException If the delegate does not implement the TranslatorInterface
     */
    public function setDelegate($delegate)
    {
        if (!$delegate instanceof TranslatorInterface) {
            throw new \InvalidArgumentException('Delegate: '.get_class($delegate).' does not implement TranslatorInterface');
        }

        return parent::setDelegate($delegate);
    }

    /**
     * Get the decorated object
     *
     * @return TranslatorCache
     */
    public function getDelegate()
    {
        return parent::getDelegate();
    }
}
