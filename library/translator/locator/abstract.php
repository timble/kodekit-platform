<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Translator Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Translator\Locator\Abstract
 */
abstract class TranslatorLocatorAbstract extends Object implements TranslatorLocatorInterface, ObjectMultiton
{
    /**
     * The stream name
     *
     * @var string
     */
    protected static $_name = '';

    /**
     * The locale
     *
     * @var string
     */
    protected $_locale;

    /**
     * Found locations map
     *
     * @var array
     */
    protected $_locations;

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     *
     * @param ObjectConfig $config   An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the locale
        $this->setLocale($config->locale);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'locale' => 'en-GB'
        ));

        parent::_initialize($config);
    }

    /**
     * Get the locator name
     *
     * @return string The stream name
     */
    public static function getName()
    {
        return static::$_name;
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
     * Sets the locale
     *
     * @param string $locale
     * @return TranslatorLocatorAbstract
     */
    public function setLocale($locale)
    {
        $this->_locale = $locale;
        return $this;
    }

    /**
     * Locate the translation based on a physical path
     *
     * @param  string $url       The translation url
     * @return string  The real file path for the translation
     */
    public function locate($url)
    {
        $key = $this->getLocale().'-'.$url;

        if(!isset($this->_locations[$key]))
        {
            $result = array();
            $info   = array(
                'url'     => $url,
                'locale'  => $this->getLocale(),
                'path'    => '',
            );

            $this->_locations[$key] = $this->find($info);
        }

        return $this->_locations[$key];
    }

    /**
     * Find a translation path
     *
     * @param array  $info  The path information
     * @return array
     */
    public function find(array $info)
    {
        $result = array();

        if($info['path'] && $info['locale'])
        {
            $pattern = $info['path'].'/'.$info['locale'].'.*';
            $results = glob($pattern);

            if ($results)
            {
                foreach($results as $file)
                {
                    if($path = $this->realPath($file))
                    {
                        $result[] = $path;
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    final public function realPath($file)
    {
        $result = false;
        $path   = dirname($file);

        // Is the path based on a stream?
        if (strpos($path, '://') === false)
        {
            // Not a stream, so do a realpath() to avoid directory traversal attempts on the local file system.
            $path = realpath($path); // needed for substr() later
            $file = realpath($file);
        }

        // The substr() check added to make sure that the realpath() results in a directory registered so that
        // non-registered directories are not accessible via directory traversal attempts.
        if (file_exists($file) && substr($file, 0, strlen($path)) == $path) {
            $result = $file;
        }

        return $result;
    }

    /**
     * Returns true if the translation is still fresh.
     *
     * @param  string $url    The translation url
     * @param int     $time   The last modification time of the cached translation (timestamp)
     * @return bool TRUE if the template is still fresh, FALSE otherwise
     */
    public function isFresh($url, $time)
    {
        if($file = $this->locate($url)) {
            return filemtime($file) < $time;
        }

        return false;
    }
}
