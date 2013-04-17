<?php
/**
 * @package		Koowa_Loader
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

require_once dirname(__FILE__).'/adapter/interface.php';
require_once dirname(__FILE__).'/adapter/abstract.php';
require_once dirname(__FILE__).'/adapter/library.php';
require_once dirname(__FILE__).'/registry/interface.php';
require_once dirname(__FILE__).'/registry/registry.php';

/**
 * Loader class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Loader
 */
class Loader
{
    /**
     * The file container
     *
     * @var array
     */
    protected $_registry = null;

    /**
     * File aliases
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
     * Namespace map
     *
     * @var array
     */
    protected $_namespaces = array();

    /**
     * Constructor
     *
     * @param array $config Array of configuration options.
     */
    public function __construct($config = array())
    {
        //Create the class registry
        $this->_registry = new LoaderRegistry();

        if(isset($config['cache_prefix'])) {
            $this->_registry->setCachePrefix($config['cache_prefix']);
        }

        if(isset($config['cache_enabled'])) {
            $this->_registry->enableCache($config['cache_enabled']);
        }

        //Register the framework adapter
        $loader = new LoaderAdapterLibrary();
        $loader->registerNamespace(__NAMESPACE__, dirname(dirname(__FILE__)));

        $this->addAdapter($loader);

        //Auto register the loader
        $this->register();
    }

    /**
     * Registers the loader with the PHP autoloader.
     *
     * @param Boolean $prepend Whether to prepend the autoloader or not
     * @see \spl_autoload_register();
     */
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    /**
     * Unregisters the loader with the PHP autoloader.
     *
     * @see \spl_autoload_unregister();
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Get the class registry object
     *
     * @return LoaderRegistry
     */
    public function getRegistry()
    {
        return $this->_registry;
    }

 	/**
     * Add a loader adapter
     *
     * @param LoaderAdapterInterface $adapter
     * @return void
     */
    public function addAdapter(LoaderAdapterInterface $adapter)
    {
        foreach($adapter->getNamespaces() as $namespace => $paths) {
            $this->_namespaces[$namespace] = $adapter;
        }

        krsort($this->_namespaces, SORT_STRING);
    }

    /**
     * Set an file path alias
     *
     * @param string  $alias    The alias
     * @param string  $path     The path
     */
    public function setAlias($alias, $path)
    {
        $alias = trim($alias);
        $path  = trim($path);

        $this->_aliases[$alias] = $path;
    }

    /**
     * Get the path from an alias
     *
     * @param  string $path The path
     * @return string|false Return the file alias if one exists. Otherwise returns FALSE.
     */
    public function getAlias($alias)
    {
        return isset($this->_aliases[$alias]) ? $this->_aliases[$alias] : false;
    }

    /**
     * Get a list of path aliases
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->_aliases;
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
     * Load a class based on a class name
     *
     * @param string    $class    The class name
     * @return boolean  Returns TRUE if the class could be loaded, otherwise returns FALSE.
     */
    public function loadClass($class)
    {
        //Get the path
        $path = self::findPath( $class );

        if ($path !== false) {
            $result = $this->loadFile($path);
        } else {
            $result = false;
        }

        return $result;
    }

	/**
     * Load a class based on an identifier
     *
     * @param string|object $identifier The identifier or identifier object
     * @return boolean      Returns TRUE if the identifier could be loaded, otherwise returns FALSE.
     */
    public function loadIdentifier($identifier)
    {
        $result = false;

        $identifier = ServiceManager::getIdentifier($identifier);

        //Get the path
        $path = $identifier->filepath;

        if ($path !== false) {
            $result = $this->loadFile($path);
        }

        return $result;
    }

    /**
     * Load a class based on a path
     *
     * @param string	$path The file path
     * @return boolean  Returns TRUE if the file could be loaded, otherwise returns FALSE.
     */
    public function loadFile($path)
    {
        //Don't re-include files and stat the file if it exists.
        if (!in_array($path, get_included_files()) && file_exists($path)) {
            require $path;
        }

        return true;
    }

    /**
     * Get the path based on a class name
     *
     * @param   string $class   The class name
     * @return  string|false    Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
    public function findPath($class)
    {
        $result = false;

        if(!$this->_registry->offsetExists($class))
        {
            //Find the adapter
            foreach($this->_namespaces as $namespace => $adapter)
            {
                if(strpos('\\'.$class, $namespace) === 0)
                {
                    $result = $adapter->findPath($class);
                    break;
                }
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
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $path The path
     * @return string The real file path
     */
    public function realPath($path)
    {
        //Find the path by checking the alias map
        while(array_key_exists((string) $path, $this->_aliases)) {
            $path = $this->_aliases[(string) $path];
        }

        //Realpath is needed to resolve symbolic links.
        return realpath($path);
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