<?php
/**
 * @package     Koowa_Service
 * @subpackage  Manager
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Service Manager Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 * @subpackage  Manager
 */
class ServiceManager implements ServiceManagerInterface
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
     * @var    array
     */
    protected static $_services = null;

    /**
     * The mixins
     *
     * @var    array
     */
    protected static $_mixins = array();

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
    final private function __construct(Config $config)
    {
        //Create the identifier registry
        self::$_identifiers = new ServiceIdentifierRegistry();

        if (isset($config['cache_prefix'])) {
            self::$_identifiers->setCachePrefix($config['cache_prefix']);
        }

        if (isset($config['cache_enabled'])) {
            self::$_identifiers->enableCache($config['cache_enabled']);
        }

        //Create the service container
        self::$_services = new ServiceContainer();

        //Auto-load the koowa adapter
        ServiceIdentifier::addLocator(new ServiceLocatorLibrary(new Config()));
        ServiceIdentifier::setNamespace('nooku', JPATH_ROOT . '/framework');
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
     * @return KService
     */
    public static function getInstance($config = array())
    {
        static $instance;

        if ($instance === NULL) {
            if (!$config instanceof Config) {
                $config = new Config($config);
            }

            $instance = new self($config);
        }

        return $instance;
    }

    /**
     * Get an instance of a class based on a class identifier only creating it
     * if it doesn't exist yet.
     *
     * @param  mixed  $identifier An object that implements ServiceInterface, ServiceIdentifier object
     *                            or valid identifier string
     * @param  array   $config    An optional associative array of configuration settings.
     * @return object  Return object on success, throws exception on failure
     */
    public static function get($identifier, array $config = array())
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (!self::$_services->offsetExists($strIdentifier))
        {
            //Instantiate the identifier
            $instance = self::_instantiate($objIdentifier, $config);

            //Perform the mixin
            self::_mixin($strIdentifier, $instance);
        }
        else $instance = self::$_services->offsetGet($strIdentifier);

        return $instance;
    }

    /**
     * Insert the object instance using the identifier
     *
     * @param mixed $identifier An object that implements ServiceInterface, ServiceIdentifier object
     *                          or valid identifier string
     * @param object $object    The object instance to store
     */
    public static function set($identifier, $object)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        self::$_services->offsetSet($strIdentifier, $object);
    }

    /**
     * Check if the object instance exists based on the identifier
     *
     * @param  mixed $identifier An object that implements ServiceInterface, ServiceIdentifier object
     *                           or valid identifier string
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public static function has($identifier)
    {
        try {
            $objIdentifier = self::getIdentifier($identifier);
            $strIdentifier = (string)$objIdentifier;
            $result = (bool)self::$_services->offsetExists($strIdentifier);

        } catch (\InvalidArgumentException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Add a mixin or an array of mixins for an identifier
     *
     * The mixins are mixed when the indentified object is first instantiated see {@link get}
     * Mixins are also added to objects that already exist in the service container.
     *
     * @param mixed $identifier An object that implements ServiceInterface, ServiceIdentifier object
     *                          or valid identifier string
     * @param  string A mixin identifier string
     * @see Object::mixin()
     */
    public static function addMixin($identifier, $mixin)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        if (!isset(self::$_mixins[$strIdentifier])) {
            self::$_mixins[$strIdentifier] = array();
        }

        //Prevent mixins from being added twice
        self::$_mixins[$strIdentifier][(string) self::getIdentifier($mixin)] = $mixin;

        //If the identifier already exists mixin the mixin
        if (self::$_services->offsetExists($strIdentifier))
        {
            $instance = self::$_services->offsetGet($strIdentifier);
            self::_mixin($strIdentifier, $instance);
        }
    }

    /**
     * Get the mixins for an identifier
     *
     * @param mixed $identifier An object that implements ServiceInterface, ServiceIdentifier object
     *                          or valid identifier string
     * @return array An array of mixins
     */
    public static function getMixins($identifier)
    {
        $objIdentifier = self::getIdentifier($identifier);
        $strIdentifier = (string)$objIdentifier;

        $result = array();
        if (isset(self::$_mixins[$strIdentifier])) {
            $result = self::$_mixins[$strIdentifier];
        }

        return $result;
    }

    /**
     * Returns an identifier object.
     *
     * Accepts various types of parameters and returns a valid identifier. Parameters can either be an object that
     * implements ServiceInterface, or a ServiceIdentifier object, or valid identifier string.
     *
     * Function will also check for identifier aliases and return the real identifier.
     *
     * @param mixed $identifier An object that implements ServiceInterface, ServiceIdentifier object
     *                          or valid identifier string
     * @return KServiceIdentifier
     */
    public static function getIdentifier($identifier)
    {
        if (!is_string($identifier))
        {
            if ($identifier instanceof ServiceInterface) {
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
                $identifier = new ServiceIdentifier($identifier);
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
     * @param mixed  $identifier An object that implements ServiceInterface, ServiceIdentifier object
     *                           or valid identifier string
     */
    public static function setAlias($alias, $identifier)
    {
        $alias = trim((string)$alias);
        $identifier = self::getIdentifier($identifier);

        self::$_aliases[$alias] = $identifier;
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
     * @param mixed  $identifier An object that implements ServiceInterface, ServiceIdentifier object
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
            self::$_configs[$strIdentifier] = new Config($config);
        }
    }

    /**
     * Get the configuration options for an identifier
     *
     * @param mixed $identifier An object that implements ServiceInterface, ServiceIdentifier object
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
     * Perform the actual mixin of all registered mixins with an object
     *
     * @param mixed $identifier An object that implements ServiceInterface, ServiceIdentifier object
     *                          or valid identifier string
     * @param  object $instance A Object instance to used as the mixer
     * @return void
     */
    protected static function _mixin($identifier, $instance)
    {
        if (isset(self::$_mixins[$identifier]) && $instance instanceof ObjectInterface)
        {
            $mixins = self::$_mixins[$identifier];
            foreach ($mixins as $mixin)
            {
                if(!$mixin instanceof MixinInterface) {
                    $mixin = self::get($mixin, array('mixer' => $instance));
                }

                $instance->mixin($mixin);
            }
        }
    }

    /**
     * Get an instance of a class based on a class identifier
     *
     * @param   object $identifier A ServiceIdentifier object
     * @param   array  $config     An optional associative array of configuration settings.
     * @throws	ServiceExceptionInvalidService	    If the object doesn't implement the ServiceInterface
     * @throws  ServiceExceptionNotFound           If service cannot be loaded
     * @throws  ServiceExceptionNotInstantiated    If service cannot be instantiated
     * @return  object  Return object on success, throws exception on failure
     */
    protected static function _instantiate(ServiceIdentifier $identifier, array $config = array())
    {
        $result = null;

        //Load the class manually using the basepath
        if (self::get('loader')->loadClass($identifier->classname, $identifier->basepath))
        {
            if (!array_key_exists(__NAMESPACE__.'\ServiceInterface', class_implements($identifier->classname)))
            {
                throw new ServiceExceptionInvalidService(
                    'Object: '.$identifier->classname.' does not implement ServiceInterface'
                );
            }

            //Create the configuration object
            $config = new Config(array_merge(self::getConfig($identifier), $config));

            //Set the service container and identifier
            $config->service_manager    = self::getInstance();
            $config->service_identifier = $identifier;

            // Delegate object instantiation.
            if (array_key_exists(__NAMESPACE__.'\ServiceInstantiatable', class_implements($identifier->classname))) {
                $result = call_user_func(array($identifier->classname, 'getInstance'), $config, self::getInstance());
            } else {
                $result = new $identifier->classname($config);
            }

            //Thrown an error if no object was instantiated
            if (!is_object($result)) {
                throw new ServiceExceptionNotInstantiated('Cannot instantiate service object: ' . $identifier->classname);
            }
        }
        else throw new ServiceExceptionNotFound('Cannot load service identifier: '. $identifier);

        return $result;
    }
}