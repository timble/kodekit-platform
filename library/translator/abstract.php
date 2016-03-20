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
 * Abstract Translator
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Kodekit\Library\Translator\Abstract
 */
abstract class TranslatorAbstract extends Object implements TranslatorInterface, ObjectInstantiable
{
    /**
     * Language
     *
     * @var string
     */
    protected $_language;

    /**
     * Language Fallback
     *
     * @var string
     */
    protected $_language_fallback;

    /**
     * The translator catalogue.
     *
     * @var TranslatorCatalogueInterface
     */
    protected $_catalogue;

    /**
     * List of urls that have been loaded.
     *
     * @var array
     */
    protected $_loaded;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_catalogue = $config->catalogue;
        $this->_loaded   = array();

        $this->setLanguage($config->language);
        $this->setLanguageFallback($this->_language_fallback);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config Configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'language'          => locale_get_default(),
            'language_fallback' => 'en-GB',
            'cache'           => \Kodekit::getInstance()->isCache(),
            'cache_namespace' => 'kodekit',
            'catalogue'       => 'default',
        ));

        parent::_initialize($config);
    }

    /**
     * Instantiate the translator and decorate with the cache decorator if cache is enabled.
     *
     * @param   ObjectConfig            $config   A ObjectConfig object with configuration options
     * @param   ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  TranslatorInterface
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        $instance = new static($config);
        $config   = $instance->getConfig();

        if($config->cache)
        {
            $class = $manager->getClass('lib:translator.cache');

            if(call_user_func(array($class, 'isSupported'))/*$class::isSupported()*/)
            {
                $instance = $instance->decorate('lib:translator.cache');
                $instance->setNamespace($config->cache_namespace);
            }
        }

        return $instance;
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
        $translation = '';

        if(!empty($string))
        {
            $catalogue   = $this->getCatalogue();
            $translation = $catalogue->has($string) ? $catalogue->{$string} : $string;

            if (count($parameters)) {
                $translation = $this->_replaceParameters($translation, $parameters);
            }
        }

        return $translation;
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
        if (count($strings) < 2) {
            throw new \InvalidArgumentException('Choose method requires at least 2 strings to choose from');
        }

        $choice = TranslatorInflector::getPluralPosition($number, $this->getLanguage());

        if ($choice !== 0)
        {
            while ($choice > 0)
            {
                $candidate = $strings[1] . ($choice === 1 ? '' : ' ' . $choice);

                if ($this->getCatalogue()->has($candidate))
                {
                    $string = $candidate;
                    break;
                }

                $choice--;
            }
        }
        else  $string = $strings[0];

        return $this->translate(isset($string) ? $string : $strings[1], $parameters);
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

            foreach($this->find($url) as $file)
            {
                try {
                    $loaded = $this->getObject('object.config.factory')->fromFile($file)->toArray();
                } catch (\Exception $e) {
                    return false;
                    break;
                }

                $translations = array_merge($translations, $loaded);
            }

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
        $language = $this->getLanguage();
        $fallback = $this->getLanguageFallback();
        $locator  = $this->getObject('translator.locator.factory')->createLocator($url);

        //Find translation based on the language
        $result = $locator->setLanguage($language)->locate($url);

        //If no translations found, try using the fallback language
        if(empty($result) && $fallback && $fallback != $language) {
            $result = $locator->setLanguage($fallback)->locate($url);
        }

        return $result;
    }

    /**
     * Sets the language
     *
     * The language should be a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     * @see $language
     *
     * @param string $language  The language tag
     * @return TranslatorAbstract
     */
    public function setLanguage($language)
    {
        if($this->_language != $language)
        {
            $this->_language = $language;

            //Set runtime locale information for date and time formatting
            setlocale(LC_TIME, $language);

            //Sets the default runtime locale
            locale_set_default($language);

            //Clear the catalogue
            $this->getCatalogue()->clear();

            //Load the library translations
            $this->load(dirname(dirname(__FILE__)).'/resources/language');
        }

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
        return $this->_language;
    }

    /**
     * Set the fallback language
     *
     * The language should be a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     * @see $language
     *
     * @param string $language The fallback language tag
     * @return TranslatorAbstract
     */
    public function setLanguageFallback($language)
    {
        $this->_labguage_fallback = $language;
        return $this;
    }

    /**
     * Get the fallback language
     *
     * Should return a properly formatted language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string The language tag
     */
    public function getLanguageFallback()
    {
        return $this->_language_fallback;
    }

    /**
     * Get the catalogue
     *
     * @throws \UnexpectedValueException    If the catalogue doesn't implement the TranslatorCatalogueInterface
     * @return TranslatorCatalogueInterface The translator catalogue.
     */
    public function getCatalogue()
    {
        if (!$this->_catalogue instanceof TranslatorCatalogueInterface)
        {
            if(!($this->_catalogue instanceof ObjectIdentifier)) {
                $this->setCatalogue($this->_catalogue);
            }

            $this->_catalogue = $this->getObject($this->_catalogue);
        }

        if(!$this->_catalogue instanceof TranslatorCatalogueInterface)
        {
            throw new \UnexpectedValueException(
                'Catalogue: '.get_class($this->_catalogue).' does not implement TranslatorCatalogueInterface'
            );
        }

        return $this->_catalogue;
    }

    /**
     * Set a catalogue
     *
     * @param   mixed   $catalogue An object that implements KObjectInterface, KObjectIdentifier object
     *                             or valid identifier string
     * @return TranslatorInterface
     */
    public function setCatalogue($catalogue)
    {
        if(!($catalogue instanceof ModelInterface))
        {
            if(is_string($catalogue) && strpos($catalogue, '.') === false )
            {
                $identifier			= $this->getIdentifier()->toArray();
                $identifier['path']	= array('translator', 'catalogue');
                $identifier['name'] = $catalogue;

                $identifier = $this->getIdentifier($identifier);
            }
            else $identifier = $this->getIdentifier($catalogue);

            $catalogue = $identifier;
        }

        $this->_catalogue = $catalogue;

        return $this;
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
        return $this->getCatalogue()->has($string);
    }

    /**
     * Handles parameter replacements
     *
     * @param string $string String
     * @param array  $parameters An array of parameters
     * @return string String after replacing the parameters
     */
    protected function _replaceParameters($string, array $parameters = array())
    {
        //Adds curly braces around keys to make strtr work in replaceParameters method
        $replace_keys = function ($key) {
            return  '{'.$key.'}';
        };

        $keys       = array_map($replace_keys, array_keys($parameters));
        $parameters = array_combine($keys, $parameters);

        return strtr($string, $parameters);
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
    public function __invoke($string, array $parameters = array())
    {
        return $this->translate($string, $parameters);
    }
}
