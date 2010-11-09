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
 * Database Hittable Behavior
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorHittable extends KDatabaseBehaviorAbstract
{
	/**
	 * Get the methods that are available for mixin based
	 * 
	 * This function conditionaly mixies the behavior. Only if the mixer 
	 * has a 'hits' property the behavior will be mixed in.
	 * 
	 * @param object The mixer requesting the mixable methods. 
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods = array();
		
		if(isset($mixer->hits)) {
			$methods = parent::getMixableMethods($mixer);
		}
	
		return $methods;
	}
	
	/**
     * Increase hit counter by 1
     *
     * Requires a 'hits' column
     */
	public function hit()
	{
		$this->hits++;
		
		if(!$this->isNew()) {
			$this->save();
		}

		return $this->_mixer;
	}
}