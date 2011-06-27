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
	 * @param	mixed 	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIdentifierInterface or valid identifier string
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return KDatabaseBehaviorAbstract
	 */
	public static function factory($behavior, $config = array())
	{		
		//Create the behavior
	    if(!($behavior instanceof KDatabaseBehaviorInterface))
		{   
		    if(is_string($behavior) && strpos($behavior, '.') === false ) {
		       $behavior = 'com.default.database.behavior.'.trim($behavior);
		    }    
			
		    $behavior = KFactory::tmp($behavior, $config);
		    
		    //Check the behavior interface
		    if(!($behavior instanceof KDatabaseBehaviorInterface)) 
		    {
			    $identifier = $behavior->getIdentifier();
			    throw new KDatabaseBehaviorException("Database behavior $identifier does not implement KDatabaseBehaviorInterface");
		    }
		}
	
		return $behavior;
	}
}