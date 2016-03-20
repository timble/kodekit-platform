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
 * Translator Cache
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Kodekit\Library\Translator\Cache
 */
class TranslatorCache extends ObjectDecorator implements TranslatorInterface
{
    /**
     * The registry cache namespace
     *
     * @var boolean
     */
    protected $_namespace = 'kodekit';

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
            $prefix       = $this->getNamespace().'-translator-'.$this->getLanguage();

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
     * Sets the language
     *
     * The language should be a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     * @see $language
     *
     * @param string $language
     * @return TranslatorCache
     */
    public function setLanguage($language)
    {
        $this->getDelegate()->setLanguage($language);
        return $this;
    }

    /**
     * Gets the language
     *
     * Should return a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string|null The language tag
     */
    public function getLanguage()
    {
        return $this->getDelegate()->getLanguage();
    }

    /**
     * Set the fallback language
     *
     * The language should be a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     * @see $language
     *
     * @param string $language The fallback language
     * @return TranslatorCache
     */
    public function setLanguageFallback($language)
    {
        $this->getDelegate()->setLanguageFallback($language);
        return $this;
    }

    /**
     * Set the fallback language
     *
     * @return string
     */
    public function getLanguageFallback()
    {
        return $this->getDelegate()->getLanguageFallback();
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
