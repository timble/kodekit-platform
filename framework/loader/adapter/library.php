<?php
/**
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

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
	 * @param  string		  	The class name
	 * @return string|false		Returns the path on success FALSE on failure
	 */
	public function findPath($classname, $basepath = null)
	{
		$path = false;

        $pos       = strrpos($classname, '\\');
        $namespace = substr($classname, 0, $pos);
        $classname = substr($classname, $pos + 1);
        $basepath  = $this->_namespaces[$namespace];

        /*
         * Exception rule for Exception classes
         *
         * Transform classname to lower case to always load the exception class from the /exception/ folder.
         */
        if($pos = strpos($classname, 'Exception'))
        {
            $filename  = substr($classname, $pos + strlen('Exception'));
            $classname = str_replace($filename, ucfirst(strtolower($filename)), $classname);
        }

		$parts = explode(' ', preg_replace('/(?<=\\w)([A-Z])/', ' \\1',  $classname));
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