<?php
/**
 * @package     Koowa_Service
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Service Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 */
interface KServiceInterface
{
	/**
	 * Get an instance of a class based on a class identifier only creating it if it doesn't exist yet.
	 *
	 * @param	string|object	$identifier The class identifier or identifier object
	 * @param	array  			$config     An optional associative array of configuration settings.
	 * @return	object  		Return object on success, throws exception on failure
	 */
	public function getService($identifier = null, array $config = array());

	/**
	 * Get a service identifier.
	 *
     * @param	string|object	$identifier The class identifier or identifier object
	 * @return	KServiceIdentifier
	 */
	public function getIdentifier($identifier = null);
}