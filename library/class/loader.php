<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

require_once dirname(__FILE__).'/interface.php';
require_once dirname(__FILE__).'/locator/interface.php';
require_once dirname(__FILE__).'/locator/abstract.php';
require_once dirname(__FILE__).'/locator/library.php';
require_once dirname(__FILE__).'/registry/interface.php';
require_once dirname(__FILE__).'/registry/registry.php';
require_once dirname(__FILE__).'/registry/cache.php';

/**
 * Class Loader
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Class|Loader
 */
class ClassLoader implements ClassLoaderInterface
{
    /**
     * The class registry
     *
     * @var array
     */
    private $__registry = null;

    /**
     * The class locators
     *
     * @var array
     */
    protected $_locators = array();

    /**
     * The loader basepath
     *
     * @var  string
     */
    protected $_base_path;

    /**
     * Debug
     *
     * @var boolean
     */
    protected $_debug = false;

    /**
     * Config array
     *
     * @var array
     */
    protected $_config = array();

    /**
     * Constructor
     *
     * @param array $config Array of configuration options.
     */
    final private function __construct($config = array())
    {
        //Set the config
        $this->setConfig($config);

        //Register the library locator
        $this->registerLocator(new ClassLocatorLibrary($config));

        //Register the Nooku\Library namesoace
        $this->getLocator('library')->registerNamespace(__NAMESPACE__, dirname(dirname(__FILE__)));

        //Register the loader with the PHP autoloader
        $this->register();
    }

    /**
     * Clone
     *
     * Prevent creating clones of this class
     */
    final private function __clone()
    {
        throw new \Exception("An instance of ".get_called_class()." cannot be cloned.");
    }

    /**
     * Force creation of a singleton
     *
     * @param  array  $config An optional array with configuration options.
     * @return ClassLoader
     */
    final public static function getInstance($config = array())
    {
        static $instance;

        if ($instance === NULL) {
            $instance = new self($config);
        }

        return $instance;
    }

    /**
     * Registers the loader with the PHP autoloader.
     *
     * @param Boolean $prepend Whether to prepend the autoloader or not
     * @see \spl_autoload_register();
     */
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'load'), true, $prepend);
    }

    /**
     * Unregisters the loader with the PHP autoloader.
     *
     * @see \spl_autoload_unregister();
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'load'));
    }

    /**
     * Get the class registry
     *
     * @return ClassRegistryInterface
     */
    protected function getRegistry()
    {
        if(!$this->__registry)
        {
            $config = $this->getConfig();

            if(isset($config['registry']))
            {
                $this->__registry = $config;
            }
            elseif(isset($config['cache']) && $config['cache'] && ClassRegistryCache::isSupported())
            {
                //Create the class registry
                $this->__registry = new ClassRegistryCache();

                if(isset($config['cache_namespace'])) {
                    $this->__registry->setNamespace($config['cache_namespace']);
                }
            }
            else $this->__registry = new ClassRegistry();
        }

        return $this->__registry;
    }

    /**
     * Get the config array
     *
     * @param null $key [optional] Lookup specific key in config
     * @return null
     */
    public function getConfig($key = null)
    {
        return $key ? (isset($this->_config[$key]) ? $this->_config[$key] : null) : $this->_config;
    }

    /**
     * Set the config array
     *
     * @param array $config
     * @throw \InvalidArgumentException if setting cache key and registry already initialized
     */
    public function setConfig(array $config)
    {
        if(isset($this->__registry) && isset($config['cache']) && $config['cache'] != $this->getConfig('cache'))
        {
            throw new \InvalidArgumentException('Class loader registry can not be changed once initialized');
        }

        //Set the debug mode
        $this->setDebug(isset($config['debug']) && $config['debug']);

        //Store the config
        $this->_config = $config;

        return $this;
    }

    /**
     * Load a class based on a class name
     *
     * @param string  $class    The class name
     * @throws \RuntimeException If debug is enabled and the class could not be found in the file.
     * @return boolean  Returns TRUE if the class could be loaded, otherwise returns FALSE.
     */
    public function load($class)
    {
        $result = false;

        //Get the path
        $path = $this->getPath( $class, $this->_base_path);

        if ($path !== false)
        {
            if (!in_array($path, get_included_files()) && file_exists($path))
            {
                require_once $path;

                if($this->_debug)
                {
                    if(!$this->isDeclared($class))
                    {
                        throw new \RuntimeException(sprintf(
                            'The autoloader expected class "%s" to be defined in file "%s".
                            The file was found but the class was not in it, the class name
                            or namespace probably has a typo.', $class, $path
                        ));
                    }
                }
            }
            else $result = false;
        }

        return $result;
    }

    /**
     * Get the path based on a class name
     *
     * @param string $class    The class name
     * @param string $base     The base_path. If NULL the global base path will be used.
     * @return string|boolean  Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
    public function getPath($class, $base = null)
    {
        $result = false;

        $base = $base ? $base : $this->_base_path;
        $key  = $base ? $class.'-'.$base : $class;

        //Switch the namespace
        if(!$this->getRegistry()->has($key))
        {
            //Locate the class
            foreach($this->_locators as $locator)
            {
                if(false !== $result = $locator->locate($class, $base)) {
                    break;
                };
            }

            //Also store if the class could not be found to prevent repeated lookups.
            $this->getRegistry()->set($key, $result);

        } else $result = $this->getRegistry()->get($key);

        return $result;
    }

    /**
     * Get the path based on a class name
     *
     * @param string $class     The class name
     * @param string $path      The class path
     * @param string $namespace The global namespace. If NULL the active global namespace will be used.
     * @return void
     */
    public function setPath($class, $path, $base = null)
    {
        $base = $base ? $base : $this->_base_path;
        $key  = $base ? $class.'-'.$base : $class;

        $this->getRegistry()->set($key, $path);
    }

    /**
     * Register a class locator
     *
     * @param  ClassLocatorInterface $locator
     * @param  bool $prepend If true, the locator will be prepended instead of appended.
     * @return void
     */
    public function registerLocator(ClassLocatorInterface $locator, $prepend = false )
    {
        $array = array($locator->getName() => $locator);

        if($prepend) {
            $this->_locators = $array + $this->_locators;
        } else {
            $this->_locators = $this->_locators + $array;
        }
    }

    /**
     * Get a registered class locator based on his type
     *
     * @param string $type The locator type
     * @return ClassLocatorInterface|null  Returns the object locator or NULL if it cannot be found.
     */
    public function getLocator($type)
    {
        $result = null;

        if(isset($this->_locators[$type])) {
            $result = $this->_locators[$type];
        }

        return $result;
    }

    /**
     * Register an alias for a class
     *
     * @param string  $class The original
     * @param string  $alias The alias name for the class.
     */
    public function registerAlias($class, $alias)
    {
        $alias = trim($alias);
        $class = trim($class);

        $this->getRegistry()->alias($class, $alias);
    }

    /**
     * Get the registered alias for a class
     *
     * @param  string $class The class
     * @return array   An array of aliases
     */
    public function getAliases($class)
    {
        return array_search($class, $this->getRegistry()->getAliases());
    }

    /**
     * Get the base path
     *
     * @return string The base path
     */
    public function getBasePath()
    {
        return $this->_base_path;
    }

    /**
     * Set the base path
     *
     * @param string $base_path The base path
     * @return ClassLoader
     */
    public function setBasePath($path)
    {
        $this->_base_path = $path;
        return $this;
    }

    /**
     * Enable or disable class loading
     *
     * If debug is enabled the class loader will throw an exception if a file is found but does not declare the class.
     *
     * @param bool $debug True or false.
     * @return ClassLoader
     */
    public function setDebug($debug)
    {
        return $this->_debug = (bool) $debug;
    }

    /**
     * Check if the loader is running in debug mode
     *
     * @return bool
     */
    public function isDebug()
    {
        return $this->_debug;
    }

    /**
     * Tells if a class, interface or trait exists.
     *
     * @params string $class
     * @return boolean
     */
    public function isDeclared($class)
    {
        return class_exists($class, false)
            || interface_exists($class, false)
            || (function_exists('trait_exists') && trait_exists($class, false));
    }
}