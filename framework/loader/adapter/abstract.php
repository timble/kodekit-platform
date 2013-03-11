<?php
/**
 * @package     Koowa_Loader
 * @subpackage  Adapter
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Abstract Loader Adapter
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Loader
 * @subpackage  Adapter
 */
abstract class LoaderAdapterAbstract implements LoaderAdapterInterface
{
    /**
     * Namespace/directory pairs to search
     *
     * @var array
     */
    protected $_namespaces = array();

    /**
     * Prefix/directory pairs to search
     *
     * @var array
     */
    protected $_prefixes = array();

    /**
     * Register a namespace
     *
     * @param  string $namespace
     * @param  string $paths The location(s) of the namespace
     * @return LoaderAdapterInterface
     */
    public function registerNamespace($namespace, $paths)
    {
        $this->_namespaces[$namespace] = $paths;
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

    /**
     * Register a prefix
     *
     * @param  string $prefix
     * @param  string $paths The location(s) of the classes
     * @return LoaderAdapterInterface
     */
    public function registerPrefix($prefix, $paths)
    {
        $this->_prefixes[$prefix] = $paths;
        return $this;
    }

    /**
     * Get the registered class prefixes
     *
     * @return array Returns the class prefixes
     */
    public function getPrefixes()
    {
        return $this->_prefixes;
    }
}