<?php
/**
 * @package		Koowa_Loader
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

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
     * Prefix map
     *
     * @var array
     */
    protected static $_prefix_map = array();

    /**
     * Prefix map
     *
     * @var array
     */
    protected static $_namespace_map = array();

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
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $path The path
     * @return string The file path
     */
    public function getFile($path)
    {
        //Find the path by checking the alias map
        while(array_key_exists($path, $this->_aliases)) {
            $path = $this->_aliases[$path];
        }

        return $path;
    }

 	/**
     * Add a loader adapter
     *
     * @param LoaderAdapterInterface $adapter
     * @return void
     */
    public function addAdapter(LoaderAdapterInterface $adapter)
    {
        foreach($adapter->getPrefixes() as $prefix => $path) {
            self::$_prefix_map[$prefix] = $adapter;
        }

        foreach($adapter->getNamespaces() as $namespace => $path) {
            self::$_namespace_map[$namespace] = $adapter;
        }
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
     * Get the path alias
     *
     * @param  string $path The path
     * @return string Return the file alias if one exists. Otherwise return FALSE.
     */
    public function getAlias($path)
    {
        return array_search($path, $this->_aliases);
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
     * Load a class based on a class name
     *
     * @param string    $class    The class name
     * @param string    $basepath The basepath
     * @return boolean  Returns TRUE if the class could be loaded, otherwise returns FALSE.
     */
    public function loadClass($class, $basepath = null)
    {
        $result = false;

        //Pre-empt further searching for the named class or interface.
        //Do not use autoload, because this method is registered with
        //spl_autoload already.
        if (!class_exists($class, false) && !interface_exists($class, false))
        {
            //Get the path
            $path = self::findPath( $class, $basepath );

            if ($path !== false) {
                $result = $this->loadFile($path);
            }
        }
        else $result = true;

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
        $result = false;
        $path   = $this->getFile($path);

        //Don't re-include files and stat the file if it exists.
        //Realpath is needed to resolve symbolic links.
        if (!in_array(realpath($path), get_included_files()) && file_exists($path))
        {
            if($included = include $path) {
                $result = true;
            };
        }

        return $result;
    }

    /**
     * Get the path based on a class name
     *
     * @param string $class    The class name
     * @param string $basepath The basepath
     * @return string|false   Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
    public function findPath($class, $basepath = null)
    {
        static $base;

        //Switch the base
        $base = $basepath ? $basepath : $base;

        if(!$this->_registry->offsetExists($base.'-'.(string) $class))
        {
            $result = false;

            if (false !== $pos = strrpos($class, '\\'))
            {
                //Namespaced classname
                foreach(self::$_namespace_map as $namespace => $adapter)
                {
                    if(strpos($class, $namespace) === 0)
                    {
                        $result = $adapter->findPath( $class, $basepath);
                        break;
                    }
                }
            }
            else
            {
                //Prefixed classname
                $parts  = explode(' ', preg_replace('/(?<=\\w)([A-Z])/', ' \\1', $class));
                $prefix = $parts[0];

                if(isset(self::$_prefix_map[$prefix])) {
                    $result = self::$_prefix_map[$prefix]->findPath( $class, $basepath);
                }
            }

            if ($result !== false)
            {
                //Get the canonicalized absolute pathname
                if($result = realpath($result)) {
                    $this->_registry->offsetSet($base.'-'.(string) $class, $result);
                }
            }
        }
        else $result = $this->_registry->offsetGet($base.'-'.(string)$class);

        return $result;
    }
}