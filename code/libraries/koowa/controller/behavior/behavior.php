<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Behavior Factory
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @subpackage 	Behavior
 */
class KControllerBehavior
{
	/**
	 * Factory method for KControllerBehaviorInterface classes.
	 *
	 * @param	mixed 	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return KControllerBehaviorAbstract
	 */
	public static function factory($behavior, $config = array())
	{		
	    //Create the behavior
	    if(!($behavior instanceof KControllerBehaviorInterface))
		{   
		    if(is_string($behavior) && strpos($behavior, '.') === false ) {
		       $behavior = 'com.default.controller.behavior.'.trim($behavior);
		    }    
			
		    $behavior = KFactory::tmp($behavior, $config);
		    
		    //Check the behavior interface
		    if(!($behavior instanceof KControllerBehaviorInterface)) 
		    {
			    $identifier = $behavior->getIdentifier();
			    throw new KControllerBehaviorException("Controller behavior $identifier does not implement KControllerBehaviorInterface");
		    }
		}
	 
		return $behavior;
	}
}