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
	 * @param	string 	Behavior indentifier
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return KControllerBehaviorAbstract
	 */
	public static function factory($identifier, $config = array())
	{		
		//Create the behavior
		try 
		{
			if(is_string($identifier) && strpos($identifier, '.') === false ) {
				$identifier = 'com.default.controller.behavior.'.trim($identifier);
			} 
			
			$behavior = KFactory::tmp($identifier, $config);
			
		} catch(KFactoryAdapterException $e) {
			throw new KControllerBehaviorException('Invalid identifier: '.$identifier);
		}
		
		//Check the behavior interface
		if(!($behavior instanceof KControllerBehaviorInterface)) 
		{
			$identifier = $behavior->getIdentifier();
			throw new KControllerBehaviorException("Controller behavior $identifier does not implement KControllerBehaviorInterface");
		}
		
		return $behavior;
	}
}