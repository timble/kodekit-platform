<?php
/**
 * @package		Koowa_Service
 * @subpackage 	Locator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Service Locator Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 * @subpackage 	Locator
 */
interface ServiceLocatorInterface
{
    /**
     * Get the type
     *
     * @return string	Returns the type
     */
    public function getType();

    /**
	 * Get the classname based on an identifier
	 *
	 * @param 	object 			An identifier object - [application::]type.package.[.path].name
	 * @return 	string|false 	Returns the class on success, returns FALSE on failure
	 */
	public function findClass(ServiceIdentifier $identifier);

	 /**
     * Get the path based on an identifier
     *
     * @param  object   An identifier object - [application::]type.package.[.path].name
     * @return string	Returns the path
     */
    public function findPath(ServiceIdentifier $identifier);
}