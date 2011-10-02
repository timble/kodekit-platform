<?php
/**
 * @version     $Id$
 * @category    Koowa
 * @package     Koowa_Service
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Object Serviceable Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Service
 */
interface KObjectServiceable
{
	/**
	 * Get an instance of a class based on a class identifier only creating it
	 * if it doesn't exist yet.
	 *
	 * @param	string|object	The class identifier or identifier object
	 * @param	array  			An optional associative array of configuration settings.
	 * @throws	KServiceServiceException
	 * @return	object  		Return object on success, throws exception on failure
	 */
	public function getService($identifier, array $config = array());

	/**
	 * Get a service identifier.
	 *
	 * @return	KServiceIdentifier
	 */
	public function getIdentifier($identifier = null);
}