<?php
/**
 * @package		Koowa_Class
 * @subpackage 	Locator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Standard Class Locator
 *
 * PSR-0 compliant autoloader. Allows autoloading of namespaced classes.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Class
 * @subpackage 	Locator
 */
class ClassLocatorStandard extends ClassLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = 'psr';

    /**
     * Get the path based on a class name
     *
     * @param  string   $class The class name
     * @return string|false   Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
	public function locate($class)
	{
        //Find the class
        foreach($this->_namespaces as $namespace => $paths)
        {
            if(strpos('\\'.$class, $namespace) !== 0) {
                continue;
            }

            if ($pos = strrpos($class, '\\'))
            {
                $namespace = substr($class, 0, $pos);
                $class     = substr($class, $pos + 1);
            }

            $path  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            $path .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

            foreach ($paths as $basepath)
            {
                $file = $basepath.'/'.$path;
                if (is_file($file)) {
                    return $file;
                }
            }
        }

        return false;
	}
}