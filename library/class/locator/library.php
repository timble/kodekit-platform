<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Library Class Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Class
 */
class ClassLocatorLibrary extends ClassLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = 'lib';

    /**
     *  Get a fully qualified path based on a class name
     *
     * @param  string   $class The class name
     * @return string|false   Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
	public function locate($class)
	{
		$path = false;

        foreach($this->_namespaces as $namespace => $paths)
        {
            if(strpos('\\'.$class, $namespace) !== 0) {
                continue;
            }

            $pos       = strrpos($class, '\\');
            $namespace = substr($class, 0, $pos);
            $class     = substr($class, $pos + 1);
            $paths     = $this->_namespaces['\\'.$namespace];

            /*
             * Exception rule for Exception classes
             *
             * Transform class to lower case to always load the exception class from the /exception/ folder.
             */
            if($pos = strpos($class, 'Exception'))
            {
                $filename  = substr($class, $pos + strlen('Exception'));
                $class = str_replace($filename, ucfirst(strtolower($filename)), $class);
            }

            $parts = explode(' ', preg_replace('/(?<=\\w)([A-Z])/', ' \\1',  $class));
            $path  = strtolower(implode('/', $parts));

            if(count($parts) == 1) {
                $path = $path.'/'.$path;
            }

            foreach ($paths as $basepath)
            {
                $file = $basepath.'/'.$path.'.php';
                if(is_file($file)) {
                    return $file;
                }

                $file = $basepath.'/'.$path.'/'.strtolower(array_pop($parts)).'.php';
                if (is_file($file)) {
                    return $file;
                }
            }

        }

		return false;
	}
}