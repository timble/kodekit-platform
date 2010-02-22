<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Database Behavior Factory
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehavior
{
	/**
	 * instantiate method for KDatabaseBehaviorInterface classes.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @return KFilterAbstract
	 */
	public static function instantiate(array $options = array())
	{		
		if(!isset($options['behavior'])) {
			throw new InvalidArgumentException('behavior [string] option is required');
		}
	
		//Get the behavior
		$behavior = $options['behavior'];
		
		try 
		{
			if(is_string($behavior) && strpos($behavior, '.') === false ) 
			{
				$behavior = 'KDatabaseBehavior'.ucfirst($behavior);
				$behavior = new $behavior();
				
			} else $behavior = KFactory::get($behavior);
			
		} catch(KFactoryAdapterException $e) {
			throw new KDatabaseBehaviorException('Invalid behavior: '.$behavior);
		}

		return $behavior;
	}
}