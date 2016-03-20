<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Class Registry Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Class\Registry\Interface
 */
interface ClassRegistryInterface
{
    /**
     * Get a class from the registry
     *
     * @param  string $class
     * @return  string  The class path
     */
    public function get($class);

    /**
     * Set a class path in the registry
     *
     * @param  string $class
     * @param  string $path
     * @return ClassRegistryInterface
     */
    public function set($class, $path);

    /**
     * Check if a class exists in the registry
     *
     * @param  string $class
     * @return  boolean
     */
    public function has($class);

    /**
     * Remove a class from the registry
     *
     * @param  string $class
     * @return  ClassRegistryInterface
     */
    public function remove($class);

    /**
     * Clears out all classes from the registry
     *
     * @return  ObjectRegistryInterface
     */
    public function clear();

    /**
     * Try to find an class path based on a class name
     *
     * @param   string  $class
     * @return  string The class path, or NULL if the class is not registered
     */
    public function find($class);

    /**
     * Register an alias for a class
     *
     * @param string $class
     * @param string $alias
     * @return ClassRegistryInterface
     */
    public function alias($class, $alias);

    /**
     * Get a list of all the class aliases
     *
     * @return array
     */
    public function getAliases();

    /**
     * Get a list of all classes in the registry
     *
     * @return  array  An array of classes
     */
    public function getClasses();
}