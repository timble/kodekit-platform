<?php
/**
 * @package		Koowa_Object
 * @subpackage  Manager
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Manager Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Manager
 */
interface ObjectManagerInterface
{
	/**
	 * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
	 *
	 * @param	string|object	$identifier The class identifier or identifier object
	 * @param	array  			$config     An optional associative array of configuration settings.
	 * @throws	ObjectException
	 * @return	object  		Return object on success, throws exception on failure
	 */
	public function get($identifier, array $config = array());

	/**
	 * Insert the object instance using the identifier
	 *
	 * @param mixed  $identifier The class identifier
	 * @param object $config     The object instance to store
	 */
	public function set($identifier, $object);

	/**
	 * Check if the object instance exists based on the identifier
	 *
	 * @param mixed $identifier The class identifier
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	public function has($identifier);

    /**
     * Load a file based on an identifier
     *
     * @param string|object $identifier The identifier or identifier object
     * @return boolean      Returns TRUE if the identifier could be loaded, otherwise returns FALSE.
     */
    public function load($identifier);

    /**
     * Register a mixin or an array of mixins for an identifier
     *
     * The mixins are mixed when the identified object is first instantiated see {@link get} Mixins are also added to
     * objects that already exist in the service registry.
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param  string $mixin    A mixin identifier string
     * @see Object::mixin()
     */
    public function registerMixin($identifier, $mixins);

    /**
     * Get the mixins for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @return array An array of mixins
     */
    public function getMixins($identifier);

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
    public function registerDecorator($identifier, $decorators);

    /**
     * Get the decorators for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @return array An array of decorators
     */
    public function getDecorators($identifier);

    /**
     * Returns an identifier object.
	 *
	 * Accepts various types of parameters and returns a valid identifier. Parameters can either be an object that
     * implements ObjectInterface, or a ObjectIdentifier object, or valid identifier string.
     *
     * Function will also check for identifier mappings and return the mapped identifier.
	 *
	 * @param	mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
	 * 					         or valid identifier string
	 * @return ObjectIdentifier
	 */
	public function getIdentifier($identifier);

	/**
	 * Set the configuration options for an identifier
	 *
	 * @param mixed	$identifier An object that implements ObjectInterface, ObjectIdentifier object
	 * 				            or valid identifier string
	 * @param array	$config An associative array of configuration options
	 */
	public function setConfig($identifier, array $config);

	/**
	 * Get the configuration options for an identifier
	 *
	 * @param mixed	$identifier An object that implements ObjectInterface, ObjectIdentifier object
	 * 				            or valid identifier string
	 * @return array  An associative array of configuration options
	 */
	public function getConfig($identifier);

	/**
     * Get the configuration options for all the identifiers
     *
     * @return array  An associative array of configuration options
     */
    public function getConfigs();

	/**
	 * Set an alias for an identifier
	 *
	 * @param string $alias      The alias
	 * @param mixed  $identifier The class identifier or identifier object
	 */
	public function setAlias($alias, $identifier);

    /**
     * Get the identifier for an alias
     *
     * @param string $alias The alias
     * @return mixed|false An object that implements ObjectInterface, ObjectIdentifier object
     *                     or valid identifier string
     */
    public function getAlias($alias);

	/**
     * Get a list of aliases
     *
     * @return array
     */
    public function getAliases();
}