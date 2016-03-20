<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Object Bootstrapper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Bootstrapper
 */
final class ObjectBootstrapper extends Object implements ObjectBootstrapperInterface, ObjectSingleton
{
    /**
     * List of bootstrapped directories
     *
     * @var array
     */
    protected $_directories;

    /**
     * List of bootstrapped components
     *
     * @var array
     */
    protected $_components;

    /**
     * Namespace/path map
     *
     * @var array
     */
    protected $_namespaces;

    /**
     * List of config files
     *
     * @var array
     */
    protected $_files;

    /**
     * List of identifier aliases
     *
     * @var array
     */
    protected $_aliases;

    /**
     * Bootstrapped status.
     *
     * @var bool
     */
    protected $_bootstrapped;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        parent::__construct($config);

        $this->_bootstrapped = false;

        //Force a reload if cache is enabled and we have already bootstrapped
        if($config->force_reload && $config->bootstrapped)
        {
            $config->bootstrapped   = false;
            $config->directories    = array();
            $config->components     = array();
            $config->namespaces     = array();
            $config->files          = array();
            $config->aliases        = array();
            $config->identifiers    = array();
        }

        $this->_directories  = ObjectConfig::unbox($config->directories);
        $this->_components   = ObjectConfig::unbox($config->components);
        $this->_namespaces   = ObjectConfig::unbox($config->namespaces);
        $this->_files        = ObjectConfig::unbox($config->files);
        $this->_aliases      = ObjectConfig::unbox($config->aliases);
        $this->_identifiers  = ObjectConfig::unbox($config->identifiers);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'force_reload' => false,
            'bootstrapped' => false,
            'directories'  => array(),
            'components'   => array(),
            'namespaces'   => array(),
            'files'        => array(),
            'aliases'      => array(),
            'identifiers'  => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Bootstrap
     *
     * The bootstrap cycle can be run only once
     *
     * @return void
     */
    public function bootstrap()
    {
        if(!$this->isBootstrapped())
        {
            $manager = $this->getObject('manager');

            /*
             * Load resources
             *
             * If cache is enabled and the bootstrapper has been run we do not reload the config resources
             */
            if(!$this->getConfig()->bootstrapped)
            {
                $factory = $this->getObject('object.config.factory');

                foreach($this->_files as $path)
                {
                    $array = $factory->fromFile($path, false);

                    //Priority
                    if(isset($array['priority'])) {
                        $priority = $array['priority'];
                    } else {
                        $priority = self::PRIORITY_NORMAL;
                    }

                    //Aliases
                    if(isset($array['aliases']))
                    {
                        if(!isset($aliases[$priority])) {
                            $aliases[$priority] = array();
                        }

                        $aliases[$priority] = array_merge($aliases[$priority], $array['aliases']);;
                    }

                    //Identifiers
                    if(isset($array['identifiers']))
                    {
                        if(!isset($identifiers[$priority])) {
                            $identifiers[$priority] = array();
                        }

                        $identifiers[$priority] = array_merge_recursive($identifiers[$priority], $array['identifiers']);;
                    }
                }

                /*
                 * Set the identifiers
                 *
                 * Collect identifiers by priority and then flatten the array.
                 */
                $identifiers_flat = array();

                krsort($identifiers);
                foreach ($identifiers as $priority => $merges) {
                    $identifiers_flat = array_merge_recursive($identifiers_flat, $merges);
                }

                foreach ($identifiers_flat as $identifier => $config) {
                    $manager->setIdentifier(new ObjectIdentifier($identifier, $config));
                }

                /*
                 * Set the aliases
                 *
                 * Collect aliases by priority and then flatten the array.
                 */
                $aliases_flat = array();

                foreach ($aliases as $priority => $merges) {
                    $aliases_flat = array_merge($merges, $aliases_flat);
                }

                foreach($aliases_flat as $alias => $identifier) {
                    $manager->registerAlias($identifier, $alias);
                }

                /*
                 * Reset the bootstrapper in the object manager
                 *
                 * If cache is enabled this will prevent the bootstrapper from reloading the config resources
                 */
                $identifier = new ObjectIdentifier('lib:object.bootstrapper', array(
                    'bootstrapped' => true,
                    'directories'  => $this->_directories,
                    'components'   => $this->_components,
                    'namespaces'   => $this->_namespaces,
                    'files'        => $this->_files,
                    'aliases'      => $aliases_flat,
                ));

                $manager->setIdentifier($identifier)
                        ->setObject('lib:object.bootstrapper', $this);
            }
            else
            {
                foreach($this->_aliases as $alias => $identifier) {
                    $manager->registerAlias($identifier, $alias);
                }
            }

            /*
             * Setup the component class locator
             *
             * Locators are always setup as the  cannot be cached in the registry objects.
             */
            foreach($this->_namespaces as $identifier => $namespaces)
            {
                //Register the namespace in the component class locator
                foreach($namespaces as $namespace => $paths) {
                    $manager->getClassLoader()->getLocator('component')->registerNamespace($namespace, $paths);
                }

                //Register the namespace in the component objects locator
                $manager->getLocator('com')->registerIdentifier($identifier, array_keys($namespaces));
            }

            $this->_bootstrapped = true;
        }
    }

    /**
     * Register components from a directory to be bootstrapped
     *
     * All the first level directories are assumed to be component folders and will be registered.
     *
     * @param string  $directory
     * @param bool    $bootstrap If TRUE bootstrap all the components in the directory. Default TRUE
     * @return ObjectBootstrapper
     */
    public function registerComponents($directory, $bootstrap = true)
    {
        if(!isset($this->_directories[$directory]))
        {
            foreach (new \DirectoryIterator($directory) as $dir)
            {
                //Only get the component directory names
                if ($dir->isDot() || !$dir->isDir() || !preg_match('/^[a-zA-Z]+/', $dir->getBasename())) {
                    continue;
                }

                $this->registerComponent($dir->getPathname(), $bootstrap);
            }

            $this->_directories[$directory] = true;
        }

        return $this;
    }

    /**
     * Register a component to be bootstrapped.
     *
     * Class and object locators will be setup based on the information in the composer.json file.
     * If the component contains a /resources/config/bootstrapper.php file it will be registered.
     *
     * @param string $path          The component path
     * @param bool   $bootstrap     If TRUE bootstrap all the components in the directory. Default TRUE
     * @param array  $directories   Additional array of directories
     * @return ObjectBootstrapper
     */
    public function registerComponent($path, $bootstrap = true, array $directories = array())
    {
        $hash = md5($path);

        if(!isset($this->_components[$hash]))
        {
            if(file_exists($path.'/component.json'))
            {
                $array = $this->getObject('object.config.factory')->fromFile($path . '/component.json');

                if (isset($array['identifier']))
                {
                    $identifier = $array['identifier'];

                    //Set the components
                    if(!isset($this->_components[$identifier])) {
                        $this->_components[$identifier] = array($path);
                    } else {
                        $this->_components[$identifier][] = $path;
                    }

                    //Merge the additional directories
                    $this->_components[$identifier] = array_merge(
                        $this->_components[$identifier],
                        $directories
                    );

                    //Set the namespace
                    if ($array['namespace'])
                    {
                        $namespace = $array['namespace'];

                        if(isset($this->_namespaces[$identifier]))
                        {
                            if(isset($this->_namespaces[$identifier][$namespace])) {
                                $this->_namespaces[$identifier][$namespace][] = $path;
                            } else {
                                $this->_namespaces[$identifier][$namespace] = array($path);
                            }
                        }
                        else $this->_namespaces[$identifier] = array($namespace => array($path));

                        //Merge the additional directories
                        $this->_namespaces[$identifier][$namespace] = array_merge(
                            $this->_namespaces[$identifier][$namespace],
                            $directories
                        );
                    }

                    if($array['extends'])
                    {
                        $extends = $array['extends'];

                        if(isset($this->_namespaces[$extends]))
                        {
                            $this->_namespaces[$identifier] = array_merge(
                                $this->_namespaces[$identifier],
                                $this->_namespaces[$extends]
                            );
                        }
                    }
                }

                //Register the config file
                if ($bootstrap && file_exists($path . '/resources/config/bootstrapper.php')) {
                    $this->registerFile($path . '/resources/config/bootstrapper.php');
                }
            }
        }

        return $this;
    }

    /**
     * Register a configuration file to be bootstrapped
     *
     * @param string $path  The absolute path to the file
     * @return ObjectBootstrapper
     */
    public function registerFile($path)
    {
        $hash = md5($path);

        if(!isset($this->_files[$hash])) {
            $this->_files[$hash] = $path;
        }

        return $this;
    }

    /**
     * Get the registered components
     *
     * @param string $domain  The component domain. Domain is optional and can be NULL
     * @return array
     */
    public function getComponents($domain = null)
    {
        $components = $result = array_keys($this->_components);

        if($domain)
        {
            foreach($components as $key => $component)
            {
                if(strpos($component, 'com://'.$domain) === false) {
                    unset($components[$key]);
                }
            }
        }

        return $components;
    }

    /**
     * Get a hash based on a name and domain
     *
     * @param string $name    The component name
     * @param string $domain  The component domain. Domain is optional and can be NULL
     * @return string The hash
     */
    public function getComponentIdentifier($name, $domain = null)
    {
        if($domain && ($domain != $name)) {
            $hash = 'com://'.$domain.'/'.$name;
        } else {
            $hash = 'com:'.$name;
        }

        return $hash;
    }

    /**
     * Get a registered component path
     *
     * @param string $name    The component name
     * @param string $domain  The component domain. Domain is optional and can be NULL
     * @return string Returns the component path if the component is registered. FALSE otherwise
     */
    public function getComponentPath($name, $domain = null)
    {
        $result = null;

        $identifier = $this->getComponentIdentifier($name, $domain);
        if(isset($this->_components[$identifier])) {
            $result = $this->_components[$identifier];
        }

        return $result;
    }

    /**
     * Check if the bootstrapper has been run
     *
     * If you specify a specific component name the function will check if this component was bootstrapped.
     *
     * @param string $name    The component name
     * @param string $domain  The component domain. Domain is optional and can be NULL
     * @return bool TRUE if the bootstrapping has run FALSE otherwise
     */
    public function isBootstrapped($name = null, $domain = null)
    {
        if($name)
        {
            $identifier = $this->getComponentIdentifier($name, $domain);
            $result = $this->_bootstrapped && isset($this->_components[$identifier]);
        }
        else $result = $this->_bootstrapped;

        return $result;
    }
}
