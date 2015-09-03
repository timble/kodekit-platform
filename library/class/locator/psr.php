<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Standard Class Locator
 *
 * PSR-4 compliant autoloader. Allows autoloading of namespaced classes.
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Library\Class\Locator
 * @link    http://www.php-fig.org/psr/psr-4/
 */
class ClassLocatorPsr extends ClassLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected static $_name = 'psr';

    /**
     * @var array First letter prefixes to namespaces, improves performance rather than searching entire array
     */
    protected $_namespace_prefixes = array();

    /**
     * Register a namespace
     *
     * @param  string $namespace
     * @param  string $path The location of the namespace
     * @return ClassLocatorPsr
     */
    public function registerNamespace($namespace, $path)
    {
        // PSR namespaces can be registered to multiple paths
        parent::registerNamespace($namespace, (array) $path);

        // Extract first letter for index
        $namespace = trim($namespace, '\\');
        $first = substr($namespace, 0, 1);
        if (!isset($this->_namespace_prefixes[$first])) {
            $this->_namespace_prefixes[$first] = array();
        }

        $this->_namespace_prefixes[$first][$namespace] = strlen($namespace);

        return $this;
    }

    /**
     * Get the path based on a class name
     *
     * @param  string $class     The class name
     * @param  string $basepath  The base path
     * @return string|boolean   Returns the path on success FALSE on failure
     */
    public function locate($class, $basepath = null)
    {
        if (strpos($class, '\\') === false) {
            return false;
        }

        // Ensure we have namespaces matching the first letter
        $first = $class[0];
        if (!isset($this->_namespace_prefixes[$first])) {
            return false;
        }

        foreach ($this->_namespace_prefixes[$first] as $prefix => $length) {

            // Ensure whole namespace prefix matches
            if (0 !== strpos($class, $prefix)) {
                continue;
            }

            foreach ($this->_namespaces[$prefix] as $basepath) {

                if (strpos('\\' . $class, '\\' . $prefix) !== 0) {
                    continue;
                }

                if (strpos($class, $prefix) === 0) {
                    $class = trim(substr($class, $length), '\\');
                }

                $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

                $file = $basepath . '/' . $path;
                if (is_file($file)) {
                    return $file;
                }
            }
        }

        return false;
    }
}
