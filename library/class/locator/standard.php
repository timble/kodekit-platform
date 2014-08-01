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
 * Standard Class Locator
 *
 * PSR-0 compliant autoloader. Allows autoloading of namespaced and prefixed classes. Standard class names are not case
 * sensitive and follow the PSR-0 naming convention. Classes must be namespaced using a class name prefix or namespace.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Class
 */
class ClassLocatorStandard extends ClassLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = 'standard';

    /**
     * Get the path based on a class name
     *
     * @param  string $class     The class name
     * @param  string $classpath  The base path
     * @return string|false   Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
    public function locate($class, $classpath = null)
	{
        //Find the class
        foreach($this->_namespaces as $namespace => $basepath)
        {
            if(empty($namespace) && strpos($class, '\\')) {
                continue;
            }

            if(strpos('\\'.$class, '\\'.$namespace) !== 0) {
                continue;
            }

            if ($pos = strrpos($class, '\\'))
            {
                $namespace = substr($class, 0, $pos);
                $class     = substr($class, $pos + 1);
            }

            $path  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            $path .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

            return $basepath.'/'.$path;
        }

        return false;
	}
}