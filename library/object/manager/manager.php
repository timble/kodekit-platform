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
     * The identifier registry
     *
     * @var array
     */
    protected static $_identifiers = null;

    /**
     * The identifier aliases
     *
     * @var    array
     */
    protected static $_aliases = array();

    /**
     * The services
     *
     * @var array
     */
    protected static $_objects = null;

    /**
     * The object mixins
     *
     * @var    array
     */
    protected static $_mixins = array();

    /**
     * The object decorators
     *
     * @var    array
     */
    protected static $_decorators = array();

    /**
     * The configs
     *
     * @var    array
     */
    protected static $_configs = array();

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     */
    final private function __construct(ObjectConfig $config)
    {
        //Create the identifier registry
        self::$_identifiers = new ObjectIdentifierRegistry();

        if (isset($config['cache_prefix'])) {
            self::$_identifiers->setCachePrefix($config['cache_prefix']);
        }

        if (isset($config['cache_enabled'])) {
            self::$_identifiers->enableCache($config['cache_enabled']);
        }

        //Create the service container
        self::$_objects = new ObjectContainer();

        //Auto-load the library adapter
        ObjectIdentifier::addLocator(new ObjectLocatorLibrary(new ObjectConfig()));
    }

    /**
     * Clone
     *
     * Prevent creating clones of this class
     */
    final private function __clone()
    {

    }

    /**
     * Force creation of a singleton
     *
     * @param  array  $config An optional array with configuration options.
     * @return ObjectManager
     */
    public static function getInstance($config = array())
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
     * Get an instance of a class based on a class identifier only creating it
     * if it doesn't exist yet.
     *
     * @param  mixed  $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                            or valid identifier string
     * @param  array   $config    An optional associative array of configuration settings.
     * @return object  Return object on success, throws exception on failure
     */
    public static function get($identifier, array $config = array())
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (!self::$_objects->offsetExists($strIdentifier))
        {
            //Instantiate
            $instance = self::_instantiate($objIdentifier, $config);

            //Mix
            self::_mixin($strIdentifier, $instance);

            //Decorate
            self::_decorate($strIdentifier, $instance);
        }
        else $instance = self::$_objects->offsetGet($strIdentifier);

        return $instance;
    }

    /**
     * Insert the object instance using the identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param object $object    The object instance to store
     */
    public static function set($identifier, $object)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        self::$_objects->offsetSet($strIdentifier, $object);
    }

    /**
     * Check if the object instance exists based on the identifier
     *
     * @param  mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                           or valid identifier string
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public static function has($identifier)
    {
        try {
            $objIdentifier = self::getIdentifier($identifier);
            $strIdentifier = (string)$objIdentifier;
            $result = (bool)self::$_objects->offsetExists($strIdentifier);

        } catch (\InvalidArgumentException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Register a mixin or an array of mixins for an identifier
     *
     * The mixins are mixed when the indentified object is first instantiated see {@link get} Mixins are also added to
     * services that already exist in the object registry.
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param  string $mixin    A mixin identifier string
     * @see Object::mixin()
     */
    public static function registerMixin($identifier, $mixin)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (!isset(self::$_mixins[$strIdentifier])) {
            self::$_mixins[$strIdentifier] = array();
        }

        //Prevent mixins from being added twice
        self::$_mixins[$strIdentifier][(string) self::getIdentifier($mixin)] = $mixin;

        //If the identifier already exists mixin the mixin
        if (self::$_objects->offsetExists($strIdentifier))
        {
            $instance = self::$_objects->offsetGet($strIdentifier);
            self::_mixin($strIdentifier, $instance);
        }
    }

    /**
     * Get the mixins for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @return array An array of mixins
     */
    public static function getMixins($identifier)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string) $objIdentifier;

        $result = array();
        if (isset(self::$_mixins[$strIdentifier])) {
            $result = self::$_mixins[$strIdentifier];
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
    public static function registerDecorator($identifier, $decorator)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (!isset(self::$_decorators[$strIdentifier])) {
            self::$_decorators[$strIdentifier] = array();
        }

        //Prevent decorators from being added twice
        self::$_decorators[$strIdentifier][(string) self::getIdentifier($decorator)] = $decorator;

        //If the identifier already exists decorate
        if (self::$_objects->offsetExists($strIdentifier))
        {
            $instance = self::$_objects->offsetGet($strIdentifier);
            self::_decorate($strIdentifier, $instance);
        }
    }

    /**
     * Get the decorators for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @return array An array of decorators
     */
    public static function getDecorators($identifier)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        $result = array();
        if (isset(self::$_decorators[$strIdentifier])) {
            $result = self::$_decorators[$strIdentifier];
        }

        return $result;
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
    public static function getIdentifier($identifier)
    {
        if (!is_string($identifier))
        {
            if ($identifier instanceof ObjectInterface) {
                $identifier = $identifier->getIdentifier();
            }
        }

        //Find the identifier by checking the alias map
        while(array_key_exists((string) $identifier, self::$_aliases)) {
            $identifier = self::$_aliases[(string) $identifier];
        }

        //Get the identifier object
        if (!self::$_identifiers->offsetExists((string)$identifier))
        {
            if (is_string($identifier)) {
                $identifier = new ObjectIdentifier($identifier);
            }

            self::$_identifiers->offsetSet((string)$identifier, $identifier);
        }
        else $identifier = self::$_identifiers->offsetGet((string)$identifier);

        return $identifier;
    }

    /**
     * Set an alias for an identifier
     *
     * @param string $alias      The alias
     * @param mixed  $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                           or valid identifier string
     */
    public static function setAlias($alias, $identifier)
    {
        $alias = trim((string)$alias);
        $identifier = self::getIdentifier($identifier);

        self::$_aliases[$alias] = $identifier;
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
        return isset(self::$_aliases[$alias]) ? self::$_aliases[$alias] : false;
    }

    /**
     * Get a list of aliases
     *
     * @return array
     */
    public static function getAliases()
    {
        return self::$_aliases;
    }

    /**
     * Set the configuration options for an identifier
     *
     * @param mixed  $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                           or valid identifier string
     * @param array $config      An associative array of configuration options
     */
    public static function setConfig($identifier, array $config)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (isset(self::$_configs[$strIdentifier])) {
            self::$_configs[$strIdentifier] = self::$_configs[$strIdentifier]->append($config);
        } else {
            self::$_configs[$strIdentifier] = new ObjectConfig($config);
        }
    }

    /**
     * Get the configuration options for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param array An associative array of configuration options
     */
    public static function getConfig($identifier)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        return isset(self::$_configs[$strIdentifier]) ? self::$_configs[$strIdentifier]->toArray() : array();
    }

    /**
     * Get the configuration options for all the identifiers
     *
     * @return array  An associative array of configuration options
     */
    public static function getConfigs()
    {
        return self::$_configs;
    }

    /**
     * Perform the actual mixin of all registered mixins for an object
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param  object $instance A Object instance to used as the mixer
     * @return void
     */
    protected static function _mixin($identifier, $instance)
    {
        if (isset(self::$_mixins[$identifier]) && $instance instanceof ObjectMixable)
        {
            $mixins = self::$_mixins[$identifier];
            foreach ($mixins as $mixin) {
                $instance->mixin($mixin);
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
    protected static function _decorate($identifier, $instance)
    {
        if (isset(self::$_decorators[$identifier]) && $instance instanceof ObjectDecoratable)
        {
            $decorators = self::$_decorators[$identifier];
            foreach ($decorators as $decorator) {
                $instance = $instance->decorate($decorator);
            }
        }
    }

    /**
     * Get an instance of a class based on a class identifier
     *
     * @param   object $identifier A ObjectIdentifier object
     * @param   array  $config     An optional associative array of configuration settings.
     * @throws	ObjectExceptionInvalidObject	    If the object doesn't implement the ObjectInterface
     * @throws  ObjectExceptionNotFound           If service cannot be loaded
     * @throws  ObjectExceptionNotInstantiated    If service cannot be instantiated
     * @return  object  Return object on success, throws exception on failure
     */
    protected static function _instantiate(ObjectIdentifier $identifier, array $config = array())
    {
        $result = null;

        //Load the class manually using the basepath
        if (self::get('loader')->loadClass($identifier->classname))
        {
            if (!array_key_exists(__NAMESPACE__.'\ObjectInterface', class_implements($identifier->classname, false)))
            {
                throw new ObjectExceptionInvalidObject(
                    'Object: '.$identifier->classname.' does not implement ObjectInterface'
                );
            }

            //Create the configuration object
            $config = new ObjectConfig(array_merge(self::getConfig($identifier), $config));

            //Set the service container and identifier
            $config->service_manager    = self::getInstance();
            $config->object_identifier = $identifier;

            // Delegate object instantiation.
            if (array_key_exists(__NAMESPACE__.'\ObjectInstantiatable', class_implements($identifier->classname, false))) {
                $result = call_user_func(array($identifier->classname, 'getInstance'), $config, self::getInstance());
            } else {
                $result = new $identifier->classname($config);
            }

            //Thrown an error if no object was instantiated
            if (!is_object($result)) {
                throw new ObjectExceptionNotInstantiated('Cannot instantiate service object: ' . $identifier->classname);
            }
        }
        else throw new ObjectExceptionNotFound('Cannot load service identifier: '. $identifier);

        return $result;
    }
}