<?php
/**
 * @package		Koowa_Loader
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Loader Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Loader
 */
interface LoaderInterface
{
    /**
     * Registers the loader with the PHP autoloader.
     *
     * @param Boolean $prepend Whether to prepend the autoloader or not
     * @see \spl_autoload_register();
     */
    public function register($prepend = false);

    /**
     * Unregisters the loader with the PHP autoloader.
     *
     * @see \spl_autoload_unregister();
     */
    public function unregister();

    /**
     * Get the class registry object
     *
     * @return LoaderRegistry
     */
    public function getRegistry();

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $path The path
     * @return string The file path
     */
    public function getFile($path);

    /**
     * Set an file path alias
     *
     * @param string  $alias    The alias
     * @param string  $path     The path
     */
    public function setAlias($alias, $path);

    /**
     * Get the path alias
     *
     * @param  string $path The path
     * @return string Return the file alias if one exists. Otherwise return FALSE.
     */
    public function getAlias($path);

    /**
     * Get a list of path aliases
     *
     * @return array
     */
    public function getAliases();

    /**
     * Add an application
     *
     * @param string $name The name of the application
     * @param string $path The path of the application
     * @return void
     */
    public static function addApplication($name, $path);

    /**
     * Get an application path
     *
     * @param string $name The name of the application
     * @return string The path of the application
     */
    public static function getApplication($name);

    /**
     * Get a list of applications
     *
     * @return array
     */
    public static function getApplications();

    /**
     * Load a class based on a class name
     *
     * @param  string   $class  The class name
     * @return boolean  Returns TRUE if the class could be loaded, otherwise returns FALSE.
     */
    public function loadClass($class);

	/**
     * Load a class based on an identifier
     *
     * @param string|object $identifier The identifier or identifier object
     * @return boolean Returns TRUE if the identifier could be loaded, otherwise returns FALSE.
     */
    public function loadIdentifier($identifier);

    /**
     * Load a class based on a path
     *
     * @param string	$path The file path
     * @return boolean  Returns TRUE if the file could be loaded, otherwise returns FALSE.
     */
    public function loadFile($path);

    /**
     * Get the path based on a class name
     *
     * @param string $class   The class name
     * @return string|false   Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
    public function findPath($class);
}