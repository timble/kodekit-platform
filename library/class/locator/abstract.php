<?php
/**
 * @package     Koowa_Class
 * @subpackage  Locator
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Abstract ClassLoader Adapter
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Class
 * @subpackage  Locator
 */
abstract class ClassLocatorAbstract implements ClassLocatorInterface
{
    /**
     * Namespace/directory pairs to search
     *
     * @var array
     */
    protected $_namespaces = array();

    /**
     * Register a namespace
     *
     * @param  string $namespace
     * @param  string|array $paths The location(s) of the namespace
     * @return LoaderAdapterInterface
     */
    public function registerNamespace($namespace, $paths)
    {
        $namespace = trim($namespace, '\\');
        $this->_namespaces['\\'.$namespace] = (array) $paths;

        krsort($this->_namespaces, SORT_STRING);

        return $this;
    }

    /**
     * Get the registered namespaces
     *
     * @return array An array with namespaces as keys and path as values
     */
    public function getNamespaces()
    {
        return $this->_namespaces;
    }
}