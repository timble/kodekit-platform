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
     * Find the identifier class
     *
     * @param ServiceIdentifier$identifier An identifier object
     * @return string|false  Return the class name on success, returns FALSE on failure
     */
	public function findClass(ServiceIdentifier $identifier)
	{
        $namespace = 'Nooku\Framework';
        $class     = $namespace.'\\'.ucfirst($identifier->package).Inflector::implode($identifier->path).ucfirst($identifier->name);

		if (!class_exists($class))
		{
			// use default class instead
			$class = $namespace.'\\'.ucfirst($identifier->package).Inflector::implode($identifier->path).'Default';

			if (!class_exists($class)) {
				$class = false;
			}
		}

		return $class;
	}

    /**
     * Find the identifier path
     *
     * @param  ServiceIdentifier $identifier  	An identifier object
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