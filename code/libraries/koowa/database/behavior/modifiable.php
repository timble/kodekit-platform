<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Database Modifiable Behavior
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorModifiable extends KDatabaseBehaviorAbstract
{
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return void
     */
	protected function _initialize(KConfig $config)
    {
    	$config->append(array(
			'priority'   => KCommand::PRIORITY_LOW,
	  	));

    	parent::_initialize($config);
   	}
	
	/**
	 * Get the methods that are available for mixin based
	 * 
	 * This function conditionaly mixes the behavior. Only if the mixer 
	 * has a 'modified_by' or 'modified_by' property the behavior will 
	 * be mixed in.
	 * 
	 * @param object The mixer requesting the mixable methods. 
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods = array();
		
		if(isset($mixer->modified_by) || isset($mixer->modified_on)) {
			$methods = parent::getMixableMethods($mixer);
		}
	
		return $methods;
	}
	
	/**
	 * Set modified information
	 * 	
	 * Requires a 'modified_on' and 'modified_by' column
	 * 
	 * @return void
	 */
	protected function _beforeTableUpdate(KCommandContext $context)
	{
		$row = $context->data; //get the row data being inserted
		
		//Get the modified columns
		$modified = $context->caller->filter(array_flip($row->getModified()));
		
		if(!empty($modified))
		{
			if(isset($row->modified_by)) {
				$row->modified_by = (int) KFactory::get('lib.koowa.user')->get('id');
			}
		
			if(isset($row->modified_on)) {
				$row->modified_on = gmdate('Y-m-d H:i:s');
			}
		}
	}
}