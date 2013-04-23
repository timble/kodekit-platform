<?php
/**
 * @package     Koowa_Object
 * @subpackage  Manager
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Manager Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Manager
 */
class ObjectManager implements ObjectManagerInterface
{
    /**
     * The object registry
     *
     * @var ObjectRegistry
     */
    protected $_registry;

    /**
     * The object locators
     *
     * @var array
     */
    protected $_locators = array();

    /*
     * The class loader
     *
     * @var ClassLoader
     */
    protected $_loader;


    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     */
    protected function __construct(ObjectConfig $config)
    {
        //Create the identifier registry
        /*if (isset($config['cache_prefix'])) {
            $this->_registry->setCachePrefix($config['cache_prefix']);
        }

        if (isset($config['cache_enabled'])) {
            $this->_registry->enableCache($config['cache_enabled']);
        }*/

        if(isset($config['class_loader'])) {
            $this->setClassLoader($config['class_loader']);
        } else {
            $this->setClassLoader(ClassLoader::getInstance());
        }

        //Auto-load the library adapter
        $this->registerLocator(new ObjectLocatorLibrary(new ObjectConfig()));

        //Create the registries
        $this->_registry = new ObjectRegistry();
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
     * @return ObjectManager
     */
    final public static function getInstance($config = array())
    {
        static $instance;

        if ($instance === NULL)
        {
            if (!$config instanceof ObjectConfig) {
                $config = new ObjectConfig($config);
            }

            $instance = new self($config);
        }

        return $instance;
    }

    /**
     * Get an object instance based on an object identifier
     *
     * If the object implements the ObjectSingleton interface the object will be automatically registered in the
     * object registry.
     *
     * If the object implements the ObjectInstantiable interface the manager will delegate object instantiation
     * to the object itself.
     *
     * @param	string|object	$identifier  An ObjectIdentifier or identifier string
     * @param	array  			$config     An optional associative array of configuration settings.
     * @throws	ObjectException
     * @return	ObjectInterface  Return object on success, throws exception on failure
     */
    public function get($identifier, array $config = array())
    {
        $identifier = $this->getIdentifier($identifier);

        if (!$this->isRegistered($identifier))
        {
            //Instantiate the object
            $instance = $this->_instantiate($identifier, $config);

            //Mix the object
            $this->_mixin($identifier, $instance);

            //Decorate the object
            $this->_decorate($identifier, $instance);

            //Auto register the object
            if($instance instanceof ObjectSingleton) {
                $this->register($identifier, $instance);
            }
        }
        else $instance = $this->_registry->get($identifier);

        return $instance;
    }

    /**
     * Load a file based on an identifier
     *
     * @param string|object $identifier  An ObjectIdentifier or identifier string
     * @return boolean      Returns TRUE if the identifier could be loaded, otherwise returns FALSE.
     * @see ClassLoader::loadFile();
     */
    public function load($identifier)
    {
        $result = false;

        $identifier = $this->getIdentifier($identifier);

        //Get the path
        $path = $identifier->classpath;

        if ($path !== false) {
            $result = $this->getClassLoader()->loadFile($path);
        }

        return $result;
    }

    /**
     * Register an object instance for a specific object identifier
     *
     * @param string|object	 $identifier  The identifier string or identifier object
     * @param ObjectInterface $object     An object that implements ObjectInterface
     * @return ObjectManager
     */
    public function register($identifier, ObjectInterface $object)
    {
        $$identifier = $this->getIdentifier($identifier);
        $this->_registry->set($identifier, $object);

        return $this;
    }

    /**
     * Returns an identifier object.
     *
     * Function will also check for identifier aliases and return the real identifier.
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @return ObjectIdentifier
     */
    public function getIdentifier($identifier)
    {
        if (!is_string($identifier))
        {
            if ($identifier instanceof ObjectInterface) {
                $identifier = $identifier->getIdentifier();
            }
        }

        //Get the identifier object
        if (!$result = $this->_registry->find($identifier))
        {
            if (is_string($identifier)) {
                $result = new ObjectIdentifier($identifier, $this);
            } else {
                $result = $identifier;
            }

            $this->_registry->set($result);
        }

        return $result;
    }

    /**
     * Set the configuration options for an identifier
     *
     * @param mixed  $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @param array $config      An associative array of configuration options
     * @return ObjectManager
     */
    public function setIdentifier($identifier, array $config)
    {
        $identifier = $this->getIdentifier($identifier);
        $identifier->setConfig($config, false);

        return $this;
    }

    /**
     * Register a mixin or an array of mixins for an identifier
     *
     * The mixin is mixed when the identified object is first instantiated see {@link get} The mixin is also mixed with
     * with the represented by the identifier if the object is registered in the object manager. This mostly applies to
     * singletons but can also apply to other objects that are manually registered.
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @param mixed $mixin      An ObjectIdentifier, identifier string or object implementing ObjectMixinInterface
     * @return ObjectManagerInterface
     * @see Object::mixin()
     */
    public function registerMixin($identifier, $mixin)
    {
        $identifier = $this->getIdentifier($identifier);
        $identifier->addMixin($mixin);

        //If the identifier already exists mixin the mixin
        if ($this->isRegistered($identifier))
        {
            $mixer = $this->_registry->get($identifier);
            $this->_mixin($identifier, $mixer);
        }

        return $this;
    }

    /**
     * Register a decorator or an array of decorators for an identifier
     *
     * The object is decorated when it's first instantiated see {@link get} The object represented by the identifier is
     * also decorated if the object is registered in the object manager. This mostly applies to singletons but can also
     * apply to other objects that are manually registered.
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @param mixed $decorator  An ObjectIdentifier, identifier string or object implementing ObjectDecoratorInterface
     * @return ObjectManager
     * @see Object::decorate()
     */
    public function registerDecorator($identifier, $decorator)
    {
        $identifier = $this->getIdentifier($identifier);
        $identifier->addDecorator($decorator);

        //If the identifier already exists decorate it
        if ($this->isRegistered($identifier))
        {
            $delegate = $this->_registry->get($identifier);
            $this->_decorate($identifier, $delegate);
        }

        return $this;
    }

    /**
     * Register an object locator
     *
     * @param mixed $identifier An object that implements the ObjectInterface, an ObjectIdentifier or valid identifier string
     * @return ObjectManager
     */
    public function registerLocator($identifier)
    {
        if(!$identifier instanceof ObjectLocatorInterface)
        {
            $locator = $this->get($identifier);

            if(!$locator instanceof ObjectLocatorInterface)
            {
                throw new \UnexpectedValueException(
                    'Locator: '.get_class($locator).' does not implement ObjectLocatorInterface'
                );
            }
        }
        else $locator = $identifier;

        $this->_locators[$locator->getType()] = $locator;

        return $this;
    }

    /**
     * Get the registered object locators
     *
     * @return array
     */
    public function getLocators()
    {
        return $this->_locators;
    }

    /**
     * Register an alias for an identifier
     *
     * @param string $alias      The alias
     * @param mixed  $identifier The class identifier or identifier object
     * @return ObjectManagerInterface
     */
    public function registerAlias($alias, $identifier)
    {
        $alias      = trim((string) $alias);
        $identifier = $this->getIdentifier($identifier);

        $this->_registry->alias($alias, $identifier);

        return $this;
    }

    /**
     * Get the aliases for an identifier
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @return array   An array of aliases
     */
    public function getAliases($identifier)
    {
        return array_search((string) $identifier, $this->_registry->getAliases());
    }

    /**
     * Get the class loader
     *
     * @return ClassLoaderInterface
     */
    public function getClassLoader()
    {
        return $this->_loader;
    }

    /**
     * Set the class loader
     *
     * @param ClassLoaderInterface $loader
     * @return ObjectManagerInterface
     */
    public function setClassLoader(ClassLoaderInterface $loader)
    {
        $this->_loader = $loader;
        return $this;
    }

    /**
     * Check if an object instance was registered for the identifier
     *
     * @param mixed $identifier An object that implements the ObjectInterface, an ObjectIdentifier or valid identifier string
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function isRegistered($identifier)
    {
        try
        {
            $object = $this->_registry->get($this->getIdentifier($identifier));

            //If the object implements ObjectInterface we have registered an object
            if($object instanceof ObjectInterface) {
                $result = true;
            } else {
                $result = false;
            }

        } catch (ObjectExceptionInvalidIdentifier $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Check if the object is a singleton
     *
     * @param mixed $identifier An object that implements the ObjectInterface, an ObjectIdentifier or valid identifier string
     * @return boolean Returns TRUE if the object is a singleton, FALSE otherwise.
     */
    public function isSingleton($identifier)
    {
        try {
            $result = $this->getIdentifier($identifier)->isSingleton();
        } catch (ObjectExceptionInvalidIdentifier $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Perform the actual mixin of all registered mixins for an object
     *
     * @param  ObjectIdentifier $identifier
     * @param  ObjectMixable    $mixer
     * @return void
     */
    protected function _mixin(ObjectIdentifier $identifier, $mixer)
    {
        if ($mixer instanceof ObjectMixable)
        {
            $mixins = $identifier->getMixins();

            foreach ($mixins as $mixin) {
                $mixer->mixin($mixin);
            }
        }
    }

    /**
     * Perform the actual decoration of all registered decorators for an object
     *
     * @param ObjectIdentifier $identifier
     * @param ObjectDecoratable $delegate
     * @return void
     */
    protected function _decorate(ObjectIdentifier $identifier, $delegate)
    {
        if ($delegate instanceof ObjectDecoratable)
        {
            $decorators = $identifier->getDecorators();

            foreach ($decorators as $decorator) {
                $delegate = $delegate->decorate($decorator);
            }
        }
    }

    /**
     * Configure an identifier
     *
     * @param ObjectIdentifier $identifier
     * @param array $config
     * @return ObjectConfig
     */
    protected function _configure(ObjectIdentifier $identifier, $data)
    {
        //Prevent config settings from being stored in the identifier
        $config = clone $identifier->getConfig();

        //Merge the config data
        $config->append($data);

        //Set the service container and identifier
        $config->object_manager    = $this;
        $config->object_identifier = $identifier;

        return $config;
    }

    /**
     * Get an instance of a class based on a class identifier
     *
     * @param   ObjectIdentifier $identifier
     * @param   array            $config    An optional associative array of configuration settings.
     * @throws	ObjectExceptionInvalidObject	  If the object doesn't implement the ObjectInterface
     * @throws  ObjectExceptionNotFound           If object cannot be loaded
     * @throws  ObjectExceptionNotInstantiated    If object cannot be instantiated
     * @return  object  Return object on success, throws exception on failure
     */
    protected function _instantiate(ObjectIdentifier $identifier, array $config = array())
    {
        $result = null;

        if ($this->getClassLoader()->loadClass($identifier->classname))
        {
            if (!array_key_exists(__NAMESPACE__.'\ObjectInterface', class_implements($identifier->classname, false)))
            {
                throw new ObjectExceptionInvalidObject(
                    'Object: '.$identifier->classname.' does not implement ObjectInterface'
                );
            }

            //Configure the identifier
            $config = $this->_configure($identifier, $config);

            // Delegate object instantiation.
            if (array_key_exists(__NAMESPACE__.'\ObjectInstantiable', class_implements($identifier->classname, false))) {
                $result = call_user_func(array($identifier->classname, 'getInstance'), $config, $this);
            } else {
                $result = new $identifier->classname($config);
            }

            //Thrown an error if no object was instantiated
            if (!is_object($result)) {
                throw new ObjectExceptionNotInstantiated('Cannot instantiate object from identifier: ' . $identifier->classname);
            }
        }
        else throw new ObjectExceptionNotFound('Cannot load object from identifier: '. $identifier);

        return $result;
    }
}