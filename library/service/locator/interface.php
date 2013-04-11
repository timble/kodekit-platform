<?php
/**
 * @package		Koowa_Service
 * @subpackage 	Locator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

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
     * Find the identifier class
     *
     * @param ServiceIdentifier$identifier An identifier object
     * @return string|false  Return the class name on success, returns FALSE on failure
     */
	public function findClass(ServiceIdentifier $identifier);

    /**
     * Find the identifier path
     *
     * @param  ServiceIdentifier $identifier  	An identifier object
     * @return string	Returns the path
     */
    public function findPath(ServiceIdentifier $identifier);
}