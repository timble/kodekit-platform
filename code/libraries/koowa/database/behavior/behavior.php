<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Behavior Factory
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehavior
{
	/**
	 * Factory method for KDatabaseBehaviorInterface classes.
	 *
	 * @param	string 	Behavior indentifier
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return KDatabaseBehaviorAbstract
	 */
	public static function factory($identifier, $config = array())
	{		
		//Create the behavior
		try 
		{
			if(is_string($identifier) && strpos($identifier, '.') === false ) {
				$identifier = 'com.default.database.behavior.'.trim($identifier);
			} 
			
			$behavior = KFactory::tmp($identifier, $config);
			
		} catch(KFactoryAdapterException $e) {
			throw new KDatabaseBehaviorException('Invalid identifier: '.$identifier);
		}
		
		//Check the behavior interface
		if(!($behavior instanceof KDatabaseBehaviorInterface)) 
		{
			$identifier = $behavior->getIdentifier();
			throw new KDatabaseBehaviorException("Database behavior $identifier does not implement KDatabaseBehaviorInterface");
		}
		
		return $behavior;
	}
}