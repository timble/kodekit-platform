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
	 * @param  string		  	The class name
	 * @return string|false		Returns the path on success FALSE on failure
	 */
	public function findPath($classname, $basepath = null)
	{
        static $base;

        $path = false;

        if (false !== $pos = strrpos($classname, '\\'))
        {
            //Find the classname
            foreach($this->_namespaces as $namespace => $path)
            {
                if(strpos($classname, $namespace) == 0)
                {
                    $classname = str_replace(array($namespace, '\\'), '', $classname);
                    $basepath   = $path;
                    break;
                }
            }
        }
        else
        {
            //Prefixed classname
            $parts  = explode(' ', preg_replace('/(?<=\\w)([A-Z])/', ' \\1', $classname));

            $prefix    = array_shift($parts);
            $classname = implode($parts);

            if(!empty($basepath)) {
                $base = $basepath;
            }

            $basepath = $base;
        }

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

		$parts = explode(' ', strtolower(preg_replace('/(?<=\\w)([A-Z])/', ' \\1', $classname)));

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