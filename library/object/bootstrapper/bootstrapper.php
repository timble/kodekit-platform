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
 * Abstract Object Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Bootstrapper
 */
class ObjectBootstrapper extends ObjectBootstrapperAbstract implements ObjectSingleton
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

        $this->_bootstrapped = false;

        //Force a reload if cache is enabled and we have already bootstrapped
        if($config->force_reload && $config->bootstrapped)
        {
            $config->bootstrapped = false;
            $config->directories = array();
            $config->components  = array();
            $config->aliases     = array();
            $config->identifiers = array();
        }

        $this->_directories  = ObjectConfig::unbox($config->directories);
        $this->_components   = ObjectConfig::unbox($config->components);
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
        $identifiers = $this->_identifiers;
        $aliases     = $this->_aliases;

        if(!$this->isBootstrapped())
        {
            foreach($this->_components as $identifier => $component)
            {
                $name   = $component['name'];
                $path   = $component['path'];
                $vendor = $component['vendor'];

                /*
                 * Setup the component class and object locators
                 *
                 * Locators are always setup as the data cannot be cached in the registry objects.
                 */
                if($vendor)
                {
                    //Register class namespace
                    $namespace = ucfirst($vendor).'\Component\\'.ucfirst($name);
                    $this->getClassLoader()->getLocator('component')->registerNamespace($namespace, $path);

                    //Register object manager package
                    $this->getObjectManager()->getLocator('com')->registerPackage($name, $vendor);
                }

                /*
                * Load resources
                *
                * If cache is enabled and the bootstrapper has been run we do not reload the config resources
                */
                if(!$this->getConfig()->bootstrapped)
                {
                    //Register the component bootstrapper
                    $config = $path .'/resources/config/bootstrapper.php';
                    if(file_exists($config))
                    {
                        $array = $this->getObject('object.config.factory')->fromFile($config, false);

                        if(isset($array['priority'])) {
                            $priority = $array['priority'];
                        } else {
                            $priority = self::PRIORITY_NORMAL;
                        }

                        if(isset($array['aliases']))
                        {
                            if(!isset($aliases[$priority])) {
                                $aliases[$priority] = array();
                            }

                            $aliases[$priority] = array_merge($aliases[$priority], $array['aliases']);;
                        }

                        if(isset($array['identifiers']))
                        {
                            if(!isset($identifiers[$priority])) {
                                $identifiers[$priority] = array();
                            }

                            $identifiers[$priority] = array_merge_recursive($identifiers[$priority], $array['identifiers']);;
                        }
                    }
                }

                /*
                 * Set the identifiers
                 *
                 * Collect identifiers by priority and then flatten the array.
                 */
                $result = array();

                foreach ($identifiers as $priority => $merges) {
                    $result = array_merge_recursive($merges, $result);
                }

                foreach ($result as $identifier => $config) {
                    $this->getObjectManager()->setIdentifier(new ObjectIdentifier($identifier, $config));
                }

                /*
                 * Set the aliases
                 *
                 * Collect aliases by priority and then flatten the array.
                 */
                $result = array();

                foreach ($aliases as $priority => $merges) {
                    $result = array_merge($merges, $result);
                }

                foreach($result as $alias => $identifier) {
                    $this->getObjectManager()->registerAlias($identifier, $alias);
                }
            }

            /*
             * Set the bootstrapper config.
             *
             * If cache is enabled this will prevent the bootstrapper from reloading the config resources
             */
            if(!$this->getConfig()->bootstrapped)
            {
                $this->getObjectManager()->setIdentifier(new ObjectIdentifier('lib:object.bootstrapper', array(
                    'bootstrapped' => true,
                    'directories'  => $this->_directories,
                    'components'   => $this->_components,
                    'aliases'      => $aliases,
                )));
            }

            $this->_bootstrapped = true;
        }
    }

    /**
     * Register a component
     *
     * This method will setup the class and object locators for vendor component and register the bootstrapper for both
     * vendor and application components if one can be found.
     *
     * @param string $name      The component name
     * @param string $path      The component path
     * @param string $vendor    The vendor name. Vendor is optional and can be NULL
     * @return ObjectBootstrapper
     */
    public function registerComponent($name, $path, $vendor = null)
    {
        //Get the bootstrapper identifier
        if($vendor) {
            $identifier = 'com://'.$vendor.'/'.$name.'.object.bootstrapper.component';
        } else {
            $identifier = 'com:'.$name.'.object.bootstrapper.component';
        }

        if(!isset($this->_components[$identifier]))
        {
            $this->_components[$identifier] = array(
                'name'   => $name,
                'path'   => $path,
                'vendor' => $vendor
            );
        }

        return $this;
    }

    /**
     * Register components from a directory
     *
     * @param string  $directory
     * @param string  $vendor
     * @return ObjectBootstrapper
     */
    public function registerDirectory($directory, $vendor = null)
    {
        if(!isset($this->_directories[$directory]))
        {
            foreach (new \DirectoryIterator($directory) as $dir)
            {
                //Only get the component directory names
                if ($dir->isDot() || !$dir->isDir() || !preg_match('/^[a-zA-Z]+/', $dir->getBasename())) {
                    continue;
                }

                //Get the component path
                $path = $dir->getPathname();

                //Get the component name (strip prefix if it exists)
                $parts = explode('_', (string) $dir);

                if(count($parts) > 1) {
                    $name = $parts[1];
                } else {
                    $name = $parts[0];
                }

                $this->registerComponent($name, $dir->getPathname(), $vendor);
            }

            $this->_directories[$directory] = true;
        }

        return $this;
    }

    /**
     * Check if the bootstrapper has been run
     *
     * @return bool TRUE if the bootstrapping has run FALSE otherwise
     */
    public function isBootstrapped()
    {
        return $this->_bootstrapped;
    }
}
