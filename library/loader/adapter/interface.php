<?php
/**
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

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
     * Get the path based on a class name
     *
     * @param  string  $classname The class name
     * @return string|false   Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
    public function findPath($class);
}