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
     * Get the path based on a class name
     *
     * @param  string $class     The class name
     * @param  string $basepath  The base path
     * @return string|boolean   Returns the path on success FALSE on failure
     */
    public function locate($class, $basepath = null)
    {
        //Find the class
        foreach($this->getNamespaces() as $prefix => $basepath)
        {
            if(strpos('\\'.$class, '\\'.$prefix) !== 0) {
                continue;
            }

            if (strpos($class, $prefix) === 0) {
                $class = trim(substr($class, strlen($prefix)), '\\');
            }

            $path = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';

            $file = $basepath.'/'.$path;
            if (is_file($file)) {
                return $file;
            }
        }

        return false;
    }
}