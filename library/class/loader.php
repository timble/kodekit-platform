<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

require_once dirname(__FILE__).'/interface.php';
require_once dirname(__FILE__).'/locator/interface.php';
require_once dirname(__FILE__).'/locator/abstract.php';
require_once dirname(__FILE__).'/locator/library.php';
require_once dirname(__FILE__).'/registry/interface.php';
require_once dirname(__FILE__).'/registry/registry.php';

/**
 * Class Loader
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Class
 */
class ClassLoader implements ClassLoaderInterface
{
    /**
     * The class locators
     *
     * @var array
     */
    protected $_locators = array();

    /**
     * The class registry
     *
     * @var array
     */
    protected $_registry = null;

    /**
     * Class aliases
     *
     * @var    array
     */
    protected $_aliases = array();

    /**
     * List of applications
     *
     * @var array
     */
    protected $_applications = array();

    /**
     * Constructor
     *
     * @param array $config Array of configuration options.
     */
    final private function __construct($config = array())
    {
        //Create the class registry
        if(isset($config['cache_enabled']) && $config['cache_enabled'])
        {
            $this->_registry = new ClassRegistryCache();

            if(isset($config['cache_prefix'])) {
                $this->_registry->setCachePrefix($config['cache_prefix']);
            }
        }
        else $this->_registry = new ClassRegistry();

        //Register the library locator
        $this->registerLocator(new ClassLocatorLibrary());

        //Register the Nooku\Library namesoace
        $this->getLocator('lib')->registerNamespace(__NAMESPACE__, dirname(dirname(__FILE__)));

        //Register the loader with the PHP autoloader
        $this->register();

        //Register the component locator
        $this->registerLocator(new ClassLocatorComponent());

        //Register the standard locator
        $this->registerLocator(new ClassLocatorStandard());
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
     * Load a class based on a class name
     *
     * @param string    $class    The class name
     * @return boolean  Returns TRUE if the class could be loaded, otherwise returns FALSE.
     */
    public function load($class)
    {
        $result = true;

        if(!$this->isDeclared($class))
        {
            //Get the path
            $path = $this->find( $class );

            if ($path !== false)
            {
                if (!in_array($path, get_included_files()) && file_exists($path)){
                    require $path;
                } else {
                    $result = false;
                }

            }
            else $result = false;
        }

        return $result;
    }

    /**
     * Get the path based on a class name
     *
     * @param   string $class   The class name
     * @return  string|false    Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
    public function find($class)
    {
        $result = false;

        //Recursively resolve the real class if an alias was passed
        while(array_key_exists((string) $class, $this->_aliases)) {
            $class = $this->_aliases[(string) $class];
        }

        if(!$this->_registry->offsetExists($class))
        {
            //Locate the class
            foreach($this->_locators as $locator)
            {
                if(false !== $result = $locator->locate($class)) {
                    break;
                };
            }

            if ($result !== false)
            {
                //Get the canonicalized absolute pathname
                if($result = realpath($result)) {
                    $this->_registry->offsetSet($class, $result);
                }
            }
        }
        else $result = $this->_registry->offsetGet($class);

        return $result;
    }

    /**
     * Get the class registry object
     *
     * @return ClassRegistry
     */
    public function getRegistry()
    {
        return $this->_registry;
    }

 	/**
     * Register a class locator
     *
     * @param ClassLocatorInterface $locator
     * @return void
     */
    public function registerLocator(ClassLocatorInterface $locator)
    {
        $this->_locators[$locator->getType()] = $locator;
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

        $this->_aliases[$alias] = $class;
    }

    /**
     * Get the registered alias for a class
     *
     * @param  string $class The class
     * @return array   An array of aliases
     */
    public function getAliases($class)
    {
        return isset($this->_aliases[$class]) ? $this->_aliases[$class] : array();
    }

    /**
     * Add an application
     *
     * @param string $name The name of the application
     * @param string $path The path of the application
     * @return void
     */
    public function addApplication($name, $path)
    {
        $this->_applications[$name] = $path;
    }

    /**
     * Get an application path
     *
     * @param string $name The name of the application
     * @return string The path of the application
     */
    public function getApplication($name)
    {
        return isset($this->_applications[$name]) ? $this->_applications[$name] : null;
    }

    /**
     * Get a list of applications
     *
     * @return array
     */
    public function getApplications()
    {
        return $this->_applications;
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