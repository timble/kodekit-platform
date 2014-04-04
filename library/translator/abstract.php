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
 * Abstract Translator
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Translator
 */
abstract class TranslatorAbstract extends Object implements TranslatorInterface
{
    /**
     * Locale
     *
     * @var string
     */
    protected $_locale;

    /**
     * The translator catalogue.
     *
     * @var TranslatorCatalogueInterface
     */
    protected $_catalogue;

    /**
     * The translator parser.
     *
     * @var TranslatorParserInterface
     */
    protected $_parser;

    /**
     * Constructor.
     *
     * @param   ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->setLocale($config->locale);
        $this->_parser    = $config->parser;
        $this->_catalogue = $config->catalogue;
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
            'caching' => false,
            'parser'  => 'lib:translator.parser.yaml',
            'locale'  => 'en-GB'
        ))->append(array('catalogue' => 'lib:translator.catalogue' . ($config->caching ? '.cache' : '')));

        parent::_initialize($config);
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
        $catalogue = $this->getCatalogue();

        $translation = $catalogue->hasString($string) ? $catalogue->{$string} : $string;

        if (count($parameters)) {
            $translation = $this->replaceParameters($translation, $parameters);
        }

        return $translation;
    }

    /**
     * Handles parameter replacements
     *
     * @param string $string String
     * @param array  $parameters An array of parameters
     * @return string String after replacing the parameters
     */
    public function replaceParameters($string, array $parameters = array())
    {
        $keys       = array_map(array($this, '_replaceKeys'), array_keys($parameters));
        $parameters = array_combine($keys, $parameters);

        return strtr($string, $parameters);
    }

    /**
     * Translates a string based on the number parameter passed
     *
     * @param array   $strings Strings to choose from
     * @param integer $number The umber of items
     * @param array   $parameters An array of parameters
     * @throws \InvalidArgumentException
     * @return string Translated string
     */
    public function choose(array $strings, $number, array $parameters = array())
    {
        if (count($strings) < 2) {
            throw new \InvalidArgumentException('Choose method requires at least 2 strings to choose from');
        }

        $choice = TranslatorInflector::getPluralPosition($number, $this->getLocale());

        if ($choice === 0)
        {
            $string = $strings[0];
        }
        else
        {
            while ($choice > 0)
            {
                $candidate = $strings[1] . ($choice === 1 ? '' : ' ' . $choice);

                if ($this->getCatalogue()->hasString($candidate))
                {
                    $string = $candidate;
                    break;
                }

                $choice--;
            }
        }

        return $this->translate(isset($string) ? $string : $strings[1], $parameters);
    }

    /**
     * Checks if the translator can translate a string
     *
     * @param $string String to check
     * @return bool
     */
    public function isTranslatable($string)
    {
        return $this->getCatalogue()->hasString($string);
    }

    /**
     * Sets the locale
     *
     * @param string $locale
     * @return TranslatorAbstract
     */
    public function setLocale($locale)
    {
        $this->_locale = $locale;
        return $this;
    }

    /**
     * Gets the locale
     *
     * @return string|null
     */
    public function getLocale()
    {
        return $this->_locale;
    }

    /**
     * Adds curly braces around keys to make strtr work in replaceParameters method
     *
     * @param string $key
     * @return string
     */
    protected function _replaceKeys($key)
    {
        return '{'.$key.'}';
    }

    public function load($source, $override = false)
    {
        $result = false;

        if (file_exists($source) && ($string = file_get_contents($source)))
        {
            $translations = $this->getParser()->parse($string);
            $result       = $this->getCatalogue()->load($translations, $override);
        }

        return $result;
    }

    public function setParser(TranslatorParserInterface $parser)
    {
        $this->_parser = $parser;
        return $this;
    }

    public function getParser()
    {
        if (!$this->_parser instanceof TranslatorParserInterface)
        {
            $this->setParser($this->getObject($this->_parser));
        }

        return $this->_parser;
    }

    public function getCatalogue()
    {
        if (!$this->_catalogue instanceof TranslatorCatalogueInterface)
        {
            $this->setCatalogue($this->getObject($this->_catalogue));
        }

        return $this->_catalogue;
    }

    public function setCatalogue(TranslatorCatalogueInterface $catalogue)
    {
        $this->_catalogue = $catalogue;
        return $this;
    }
}
