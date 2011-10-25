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
 * Abstract Controller Behavior
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @subpackage 	Behavior
 */
abstract class KControllerBehaviorAbstract extends KBehaviorAbstract
{
	/**
	 * Command handler
	 * 
	 * This function transmlated the command name to a command handler function of 
	 * the format '_before[Command]' or '_after[Command]. Command handler
	 * functions should be declared protected.
	 * 
	 * @param 	string  	The command name
	 * @param 	object   	The command context
	 * @return 	boolean		Can return both true or false.  
	 */
	public function execute( $name, KCommandContext $context) 
	{
		$this->setMixer($context->caller);
		
		return parent::execute($name, $context);
	}
	
	/**
     * Get an object handle
     * 
     * Reload the controller actions when the behavior is being enqueued into the
     * command chain. 
     * 
     * @return string A string that is unique, or NULL
     * @see execute()
     */
    public function getHandle()
    {
        if($handle = parent::getHandle()) {
             $this->_mixer->getActions(true);
        }
       
        return $handle;
    }
}