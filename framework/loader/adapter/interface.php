<?php
/**
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Loader Adapter Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 */
interface LoaderAdapterInterface
{
    /**
     * Register a namespace
     *
     * @param  string $namespace
     * @param  string $paths The location(s) of the namespace
     * @return LoaderAdapterInterface
     */
    public function registerNamespace($namespace, $paths);

    /**
     * Get the registered namespaces
     *
     * @return array An array with namespaces as keys and path as values
     */
    public function getNamespaces();

    /**
     * Register a prefix
     *
     * @param  string $prefix
     * @param  string $paths The location(s) of the classes
     * @return LoaderAdapterInterface
     */
    public function registerPrefix($prefix, $paths);

    /**
     * Get the registered class prefixes
     *
     * @return array Returns the class prefixes
     */
    public function getPrefixes();

    /**
     * Get the path based on a class name
     *
     * @param  string           The class name
     * @return string|false     Returns the path on success FALSE on failure
     */
    public function findPath($classname, $basepath = null);
}