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
     * The locator type
     *
     * @var string
     */
    protected $_type = '';

    /**
     * Namespace/directory pairs to search
     *
     * @var array
     */
    protected $_namespaces = array();

    /**
     * Constructor
     *
     * @param array $config Array of configuration options.
     */
    public function __construct($config = array())
    {
        if(isset($config['namespaces']))
        {
            $namespaces = (array) $config['namespaces'];
            foreach($namespaces as $namespace => $path) {
                $this->registerNamespace($namespace, $path);
            }
        }
    }

    /**
     * Register a namespace
     *
     * @param  string|array $namespaces
     * @param  string|array $paths The location(s) of the namespace
     * @return ClassLocatorInterface
     */
    public function registerNamespace($namespace, $paths)
    {
        $namespace = trim($namespace, '\\');
        $this->_namespaces['\\'.$namespace] = (array) $paths;

        krsort($this->_namespaces, SORT_STRING);

        return $this;
    }

    /**
     * Registers an array of namespaces
     *
     * @param array $namespaces An array of namespaces (namespaces as keys and locations as values)
     * @return ClassLocatorInterface
     */
    public function registerNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $paths)
        {
            $namespace = trim($namespace, '\\');
            $this->_namespaces['\\'.$namespace] = (array) $paths;
        }

        krsort($this->_namespaces, SORT_STRING);

        return $this;
    }

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
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