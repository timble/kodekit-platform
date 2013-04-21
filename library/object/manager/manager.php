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
     * Object registry
     *
     * @var ObjectRegistry
     */
    protected $_objects = null;

    /**
     * The object locators
     *
     * @var array
     */
    protected $_locators = array();

    /**
     * The identifier registry
     *
     * @var ObjectIdentifierRegistry
     */
    protected $_identifiers = null;

    /**
     * The object mixins
     *
     * @var    array
     */
    protected $_mixins = array();

    /**
     * The object decorators
     *
     * @var    array
     */
    protected $_decorators = array();

    /**
     * The identifier aliases
     *
     * @var    array
     */
    protected $_aliases = array();

    /**
     * The object configs
     *
     * @var    array
     */
    protected $_configs = array();

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     */
    protected function __construct(ObjectConfig $config)
    {
        //Create the identifier registry
        $this->_identifiers = new ObjectIdentifierRegistry();

        if (isset($config['cache_prefix'])) {
            $this->_identifiers->setCachePrefix($config['cache_prefix']);
        }

        if (isset($config['cache_enabled'])) {
            $this->_identifiers->enableCache($config['cache_enabled']);
        }

        //Create the service container
        $this->_objects = new ObjectRegistry();

        //Auto-load the library adapter
        $this->registerLocator(new ObjectLocatorLibrary(new ObjectConfig()));
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
     * @param	string|object	$identifier The identifier string or identifier object
     * @param	array  			$config     An optional associative array of configuration settings.
     * @throws	ObjectException
     * @return	ObjectInterface  Return object on success, throws exception on failure
     */
    public function get($identifier, array $config = array())
    {
        $objIdentifier = $this->getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (!$this->_objects->offsetExists($strIdentifier))
        {
            //Instantiate the object
            $instance = $this->_instantiate($objIdentifier, $config);

            //Mix the object
            $this->_mixin($objIdentifier, $instance);

            //Decorate the object
            $this->_decorate($objIdentifier, $instance);

            //Register the object
            if($instance instanceof ObjectSingleton) {
                $this->register($objIdentifier, $instance);
            }
        }
        else $instance = $this->_objects->offsetGet($strIdentifier);

        return $instance;
    }

    /**
     * Load a file based on an identifier
     *
     * @param string|object $identifier The identifier or identifier object
     * @return boolean      Returns TRUE if the identifier could be loaded, otherwise returns FALSE.
     */
    public function load($identifier)
    {
        $result = false;

        $identifier = $this->getIdentifier($identifier);

        //Get the path
        $path = $identifier->filepath;

        if ($path !== false) {
            $result = ClassLoader::getInstance()->loadFile($path);
        }

        return $result;
    }

    /**
     * Register an object instance for a specific object identifier
     *
     * @param string|object	 $identifier The identifier string or identifier object
     * @param ObjectInterface $config     The object instance to store
     */
    public function register($identifier, ObjectInterface $object)
    {
        $objIdentifier = $this->getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        $this->_objects->offsetSet($strIdentifier, $object);
    }

    /**
     * Check if the object instance exists based on the identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function isRegistered($identifier)
    {
        try {
            $objIdentifier = $this->getIdentifier($identifier);
            $strIdentifier = (string)$objIdentifier;
            $result = (bool)$this->_objects->offsetExists($strIdentifier);

        } catch (\InvalidArgumentException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Register a mixin or an array of mixins for an identifier
     *
     * The mixins are mixed when the identified object is first instantiated see {@link get} Mixins are also added to
     * services that already exist in the object registry.
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param  string $mixin    A mixin identifier string
     * @see Object::mixin()
     */
    public function registerMixin($identifier, $mixin)
    {
        $objIdentifier = $this->getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (!isset($this->_mixins[$strIdentifier])) {
            $this->_mixins[$strIdentifier] = array();
        }

        //Prevent mixins from being added twice
        $this->_mixins[$strIdentifier][(string) $this->getIdentifier($mixin)] = $mixin;

        //If the identifier already exists mixin the mixin
        if ($this->_objects->offsetExists($strIdentifier))
        {
            $instance = $this->_objects->offsetGet($strIdentifier);
            $this->_mixin($objIdentifier, $instance);
        }
    }

    /**
     * Get the mixins for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @return array An array of mixins
     */
    public function getMixins($identifier)
    {
        $objIdentifier = $this->getIdentifier($identifier);
        $strIdentifier = (string) $objIdentifier;

        $result = array();
        if (isset($this->_mixins[$strIdentifier])) {
            $result = $this->_mixins[$strIdentifier];
        }

        return $result;
    }

    /**
     * Register a decorator or an array of decorators for an identifier
     *
     * The object is decorated when it's first instantiated see {@link get} Decorators are also added to objects that
     * already exist in the object registry.
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param  string $decorator  A decorator identifier
     * @see Object::decorate()
     */
    public function registerDecorator($identifier, $decorator)
    {
        $objIdentifier = $this->getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (!isset($this->_decorators[$strIdentifier])) {
            $this->_decorators[$strIdentifier] = array();
        }

        //Prevent decorators from being added twice
        $this->_decorators[$strIdentifier][(string) $this->getIdentifier($decorator)] = $decorator;

        //If the identifier already exists decorate
        if ($this->_objects->offsetExists($strIdentifier))
        {
            $instance = $this->_objects->offsetGet($strIdentifier);
            $this->_decorate($objIdentifier, $instance);
        }
    }

    /**
     * Get the decorators for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @return array An array of decorators
     */
    public function getDecorators($identifier)
    {
        $objIdentifier = $this->getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        $result = array();
        if (isset($this->_decorators[$strIdentifier])) {
            $result = $this->_decorators[$strIdentifier];
        }

        return $result;
    }

    /**
     * Register an object locator
     *
     * @param ObjectLocatorInterface $locator  An object locator
     * @return void
     */
    public function registerLocator(ObjectLocatorInterface $locator)
    {
        $this->_locators[$locator->getType()] = $locator;
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
     * Returns an identifier object.
     *
     * Accepts various types of parameters and returns a valid identifier. Parameters can either be an object that
     * implements ObjectInterface, or a ObjectIdentifier object, or valid identifier string.
     *
     * Function will also check for identifier aliases and return the real identifier.
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
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

        //Find the identifier by checking the alias map
        while(array_key_exists((string) $identifier, $this->_aliases)) {
            $identifier = $this->_aliases[(string) $identifier];
        }

        //Get the identifier object
        if (!$this->_identifiers->offsetExists((string)$identifier))
        {
            if (is_string($identifier)) {
                $identifier = new ObjectIdentifier($identifier, $this);
            }

            $this->_identifiers->offsetSet((string)$identifier, $identifier);
        }
        else $identifier = $this->_identifiers->offsetGet((string)$identifier);

        return $identifier;
    }

    /**
     * Set an alias for an identifier
     *
     * @param string $alias      The alias
     * @param mixed  $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                           or valid identifier string
     */
    public function setAlias($alias, $identifier)
    {
        $alias = trim((string)$alias);
        $identifier = $this->getIdentifier($identifier);

        $this->_aliases[$alias] = $identifier;
    }

    /**
     * Get the identifier for an alias
     *
     * @param string $alias The alias
     * @return mixed|false An object that implements ObjectInterface, ObjectIdentifier object
     *                     or valid identifier string
     */
    public function getAlias($alias)
    {
        return isset($this->_aliases[$alias]) ? $this->_aliases[$alias] : false;
    }

    /**
     * Get a list of aliases
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->_aliases;
    }

    /**
     * Set the configuration options for an identifier
     *
     * @param mixed  $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                           or valid identifier string
     * @param array $config      An associative array of configuration options
     */
    public function setConfig($identifier, array $config)
    {
        $objIdentifier = $this->getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (isset($this->_configs[$strIdentifier])) {
            $this->_configs[$strIdentifier] = $this->_configs[$strIdentifier]->append($config);
        } else {
            $this->_configs[$strIdentifier] = new ObjectConfig($config);
        }
    }

    /**
     * Get the configuration options for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param array An associative array of configuration options
     */
    public function getConfig($identifier)
    {
        $objIdentifier = $this->getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        return isset($this->_configs[$strIdentifier]) ? $this->_configs[$strIdentifier]->toArray() : array();
    }

    /**
     * Get the configuration options for all the identifiers
     *
     * @return array  An associative array of configuration options
     */
    public function getConfigs()
    {
        return $this->_configs;
    }

    /**
     * Perform the actual mixin of all registered mixins for an object
     *
     * @param  ObjectIdentifier $identifier
     * @param  ObjectMixable    $instance
     * @return void
     */
    protected function _mixin(ObjectIdentifier $identifier, $mixer)
    {
        $identifier = (string) $identifier;

        if (isset($this->_mixins[$identifier]) && $mixer instanceof ObjectMixable)
        {
            $mixins = $this->_mixins[$identifier];
            foreach ($mixins as $mixin) {
                $mixer->mixin($mixin);
            }
        }
    }

    /**
     * Perform the actual decoration of all registered decorators for an object
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param  object $instance A Object instance to used as the mixer
     * @return void
     */
    protected function _decorate(ObjectIdentifier $identifier, $delegate)
    {
        $identifier = (string) $identifier;

        if (isset($this->_decorators[$identifier]) && $delegate instanceof ObjectDecoratable)
        {
            $decorators = $this->_decorators[$identifier];
            foreach ($decorators as $decorator) {
                $delegate = $delegate->decorate($decorator);
            }
        }
    }

    /**
     * Configure an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param array $config
     *
     * @return ObjectConfig
     */
    protected function _configure(ObjectIdentifier $identifier, $config)
    {
        //Create the object configuration
        $config = new ObjectConfig(array_merge($this->getConfig($identifier), $config));

        //Set the service container and identifier
        $config->object_manager    = $this;
        $config->object_identifier = $identifier;

        return $config;
    }

    /**
     * Get an instance of a class based on a class identifier
     *
     * @param   ObjectIdentifier $identifier
     * @param   array            $config        An optional associative array of configuration settings.
     * @throws	ObjectExceptionInvalidObject	  If the object doesn't implement the ObjectInterface
     * @throws  ObjectExceptionNotFound           If object cannot be loaded
     * @throws  ObjectExceptionNotInstantiated    If object cannot be instantiated
     * @return  object  Return object on success, throws exception on failure
     */
    protected function _instantiate(ObjectIdentifier $identifier, array $config = array())
    {
        $result = null;

        if (ClassLoader::getInstance()->loadClass($identifier->classname))
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