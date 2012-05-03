<?php
/**
 * @version     $Id$
 * @package     Koowa_Object
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Object Serviceable Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 */
interface KObjectServiceable
{
	/**
	 * Get an instance of a class based on a class identifier only creating it
	 * if it doesn't exist yet.
	 *
	 * @param	string|object	The class identifier or identifier object
	 * @param	array  			An optional associative array of configuration settings.
	 * @throws	KObjectException
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