<?php
/**
 * @package		Koowa_Service
 * @subpackage 	Locator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Service Locator for the Koowa framework
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 * @subpackage 	Locator
 */
class ServiceLocatorLibrary extends ServiceLocatorAbstract
{
	/**
	 * The type
	 *
	 * @var string
	 */
	protected $_type = 'lib';

	/**
	 * Get the classname
	 *
	 * @param 	mixed  		 An identifier object - koowa:[path].name
	 * @return string|false  Return object on success, returns FALSE on failure
	 */
	public function findClass(ServiceIdentifier $identifier)
	{
        $namespace = 'Nooku\Framework';

        $classes   = array();
        $classname = $namespace.'\\'.ucfirst($identifier->package).Inflector::implode($identifier->path).ucfirst($identifier->name);

		if (!class_exists($classname))
		{
            //Add the classname to prevent re-look up
            $classes[] = $classname;

			// use default class instead
			$classname = $namespace.'\\'.ucfirst($identifier->package).Inflector::implode($identifier->path).'Default';

			if (class_exists($classname)) {
				$classname = false;
			} else {
                $classes[] = $classname;
            }
		}
        else $classes[] = $classname;

		return $classname;
	}

	/**
	 * Get the path
	 *
	 * @param  object  	An identifier object - koowa:[path].name
	 * @return string	Returns the path
	 */
	public function findPath(ServiceIdentifier $identifier)
	{
	    $path = '';

	    if(count($identifier->path)) {
			$path .= implode('/',$identifier->path);
		}

		if(!empty($identifier->name)) {
			$path .= '/'.$identifier->name;
		}

		$path = $identifier->basepath.'/'.$path.'.php';
		return $path;
	}
}