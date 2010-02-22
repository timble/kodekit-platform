<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Factory
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 */
class KDatabase
{
	/**
	 * Database operations
	 */
	const OPERATION_SELECT = 1;
	const OPERATION_INSERT = 2;
	const OPERATION_UPDATE = 4;
	const OPERATION_DELETE = 8; 

	/**
	 * instantiate method KDatabaseAdapterInterface classes.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * 				  Recognized key values include 'adapter', ...(this list is 
	 *                not meant to be comprehensive).
	 * @return KDatabaseAdapterAbstract
	 * @throws KDatabaseException
	 */
	public static function instantiate(array $options = array())
	{
		if(!isset($options['adapter'])) {
			throw new InvalidArgumentException('adapter [string] option is required');
		}
	
		$class = 'KDatabaseAdapter'.ucfirst($options['adapter']);
		if(!class_exists($class)) {
			throw new KDatabaseException('Adapter class '.$class.' not found');
		}
		
		return new $class($options);
	}
}