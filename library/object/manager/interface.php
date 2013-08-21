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
 * Object Manager Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
interface ObjectManagerInterface
{
    /**
     * Returns an identifier object.
     *
     * Accepts various types of parameters and returns a valid identifier. Parameters can either be an
     * object that implements KObjectInterface, or a KObjectIdentifier object, or valid identifier
     * string. Function recursively resolves identifier aliases and returns the aliased identifier.
     *
     * If no identifier is passed the object identifier of this object will be returned.
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @return ObjectIdentifier
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
    public function getIdentifier($identifier = null);

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
     * @return	ObjectInterface  Return object on success, throws exception on failure
     * @throws ObjectExceptionInvalidIdentifier   If the identifier is not valid
     * @throws	ObjectExceptionInvalidObject	  If the object doesn't implement the ObjectInterface
     * @throws  ObjectExceptionNotFound           If object cannot be loaded
     * @throws  ObjectExceptionNotInstantiated    If object cannot be instantiated
     * @return  object  Return object on success, throws exception on failure
     */
    public function getObject($identifier, array $config = array());

    /**
     * Register an object instance for a specific object identifier
     *
     * @param string|object	 $identifier  The identifier string or identifier object
     * @param ObjectInterface $object     An object that implements ObjectInterface
     * @return ObjectManagerInterface
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
	public function setObject($identifier, ObjectInterface $object);

    /**
     * Get the object configuration based on an object identifier
     *
     * @param mixed  $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @return ObjectConfig
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
    public function getConfig($identifier = null);

    /**
     * Register object configuration for  specific object identifier
     *
     * @param mixed  $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @param array $config      An optional associative array of configuration options
     * @return ObjectManagerInterface
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
    public function setConfig($identifier, array $config = null);

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
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     * @see ObjectMixable::mixin()
     */
    public function registerMixin($identifier, $mixins);

    /**
     * Register a decorator or an array of decorators for an identifier
     *
     * The object is decorated when it's first instantiated see {@link get} The object represented by the identifier is
     * also decorated if the object is registered in the object manager. This mostly applies to singletons but can also
     * apply to other objects that are manually registered.
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @param mixed $decorator  An ObjectIdentifier, identifier string or object implementing ObjectDecoratorInterface
     * @return ObjectManagerInterface
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     * @see ObjectDecoratable::decorate()
     */
    public function registerDecorator($identifier, $decorator);

    /**
     * Register an alias for an identifier
     *
     * @param string $alias      The alias
     * @param mixed  $identifier The class identifier or identifier object
     * @return ObjectManagerInterface
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
    public function registerAlias($alias, $identifier);

    /**
     * Get the aliases for an identifier
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @return array   An array of aliases
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
    public function getAliases($identifier);

    /**
     * Register an object locator
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectLocatorInterface
     * @return ObjectManagerInterface
     * @throws ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
    public function registerLocator($identifier, array $config = array());

    /**
     * Get a registered object locator based on his type
     *
     * @param string $type The locator type
     * @return ObjectLocatorInterface|null  Returns the object locator or NULL if it cannot be found.
     */
    public function getLocator($type);

    /**
     * Get the class loader
     *
     * @return ClassLoaderInterface
     */
    public function getClassLoader();

    /**
     * Set the class loader
     *
     * @param ClassLoaderInterface $loader
     * @return ObjectManagerInterface
     */
    public function setClassLoader(ClassLoaderInterface $loader);

    /**
     * Check if an object instance was registered for the identifier
     *
     * @param mixed $identifier An object that implements the ObjectInterface, an ObjectIdentifier or valid identifier string
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function isRegistered($identifier);

    /**
     * Check if the object is a multiton
     *
     * @param mixed $identifier An object that implements the ObjectInterface, an ObjectIdentifier or valid identifier string
     * @return boolean Returns TRUE if the object is a singleton, FALSE otherwise.
     */
    public function isMultiton($identifier);

    /**
     * Check if the object is a singleton
     *
     * @param mixed $identifier An object that implements the ObjectInterface, an ObjectIdentifier or valid identifier string
     * @return boolean Returns TRUE if the object is a singleton, FALSE otherwise.
     */
    public function isSingleton($identifier);

}