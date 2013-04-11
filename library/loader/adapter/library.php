<?php
/**
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Loader Adapter for framework libraries
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 */
class LoaderAdapterLibrary extends LoaderAdapterAbstract
{
    /**
     * Get the path based on a class name
     *
     * @param  string   $class The class name
     * @return string|false   Returns canonicalized absolute pathname or FALSE of the class could not be found.
     */
	public function findPath($class)
	{
		$path = false;

        $pos       = strrpos($class, '\\');
        $namespace = substr($class, 0, $pos);
        $class     = substr($class, $pos + 1);
        $basepath  = $this->_namespaces['\\'.$namespace];

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

		if(!is_file($basepath.'/'.$path.'.php')) {
			$path = $path.'/'.strtolower(array_pop($parts));
		}

		$path = $basepath.'/'.$path.'.php';

		return $path;
	}
}