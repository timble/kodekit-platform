<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Class Locator Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Class
 */
interface ClassLocatorInterface
{
    /**
     * Get the path based on a class name
     *
     * @param  string $classname    The class name
     * @param  string $basepath     The base path
     * @return string|false         Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
    public function locate($classname, $basepath = null);

    /**
     * Register a namespace
     *
     * @param  string $namespace
     * @param  string $path The location of the namespace
     * @return ClassLocatorInterface
     */
    public function registerNamespace($namespace, $paths);

    /**
     * Registers an array of namespaces
     *
     * @param array $namespaces An array of namespaces (namespaces as keys and locations as values)
     * @return ClassLocatorInterface
     */
    public function registerNamespaces($namespaces);

    /**
     * Get a the namespace paths
     *
     * @param string $namespace The namespace
     * @return array The namespace paths
     */
    public function getNamespace($namespace);

    /**
     * Get the registered namespaces
     *
     * @return array An array with namespaces as keys and path as values
     */
    public function getNamespaces();

    /**
     * Get the locator type
     *
     * @return string
     */
    public function getType();
}