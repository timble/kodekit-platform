<?php
/**
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Loader Adapter for a component
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 */
class LoaderAdapterComponent extends LoaderAdapterAbstract
{
    /**
     * Get the path based on a class name
     *
     * @param  string   $class The class name
     * @return string|false   Returns canonicalized absolute pathname or FALSE if the class could not be found.
     */
	public function findPath($class)
	{
        $path = false;

        //Find the class
        foreach($this->_namespaces as $namespace => $basepath)
        {
            if(strpos('\\'.$class, $namespace) === 0)
            {
                $class = str_replace(array($namespace, '\\'), '', '\\'.$class);
                break;
            }
        }

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

		$parts = explode(' ', strtolower(preg_replace('/(?<=\\w)([A-Z])/', ' \\1', $class)));

        $component = strtolower(array_shift($parts));
		$file 	   = array_pop($parts);

		if(count($parts))
		{
            if(!in_array($parts[0], array('view', 'module')))
	        {
			    foreach($parts as $key => $value) {
			        $parts[$key] = Inflector::pluralize($value);
		        }
		    }
	        else $parts[0] = Inflector::pluralize($parts[0]);

			$path = implode('/', $parts).'/'.$file;
		}
		else $path = $file;

		$path = $basepath.'/'.$component.'/'.$path.'.php';

		return $path;
	}
}