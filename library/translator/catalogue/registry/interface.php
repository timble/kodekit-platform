<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Translator Catalogue Registry Interface
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */

interface TranslatorCatalogueRegistryInterface
{
    /**
     * Checks is a given key is present in the registry.
     *
     * @param mixed $key The registry key.
     *
     * @return bool True if exists, false otherwise.
     */
    public function has($key);

    /**
     * Gets data from the registry.
     *
     * @param mixed $key The registry key.
     *
     * @return mixed The data.
     */
    public function get($key);

    /**
     * Sets data in the registry.
     *
     * @param mixed $key   The registry key.
     * @param mixed $value The registry value.
     *
     * @return bool True on success, false otherwise.
     */
    public function set($key, $value);

    /**
     * Removes and entry from the registry.
     *
     * @param mixed $key
     *
     * @return bool True on success, false otherwise.
     */
    public function remove($key);

    /**
     * Clears the registry.
     *
     * @return bool True on success, false otherwise.
     */
    public function clear();

    /**
     * Sets the registry namespace.
     *
     * @param string $namespace The namespace.
     *
     * @return TranslatorCatalogueRegistryInterface
     */
    public function setNamespace($namespace);

    /**
     * Gets the registry namespace.
     *
     * @return string The namespace.
     */
    public function getNamespace();
}