<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Command
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Command Interface 
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Command
 */
interface KCommandInterface
{
	/**
	 * Generic Command handler
	 * 
	 * @param 	string 	The command name
	 * @param 	object  The command context
	 * @return	boolean
	 */
	public function execute( $name, KCommandContext $context);
	
	/**
	 * Get an object handle
	 * 
	 * This function returns an unique identifier for the object. This id can be used as 
	 * a hash key for storing objects or for identifying an object
	 * 
	 * Override this function to implement implement dynamic commands. If you don't want
	 * the command to be enqueued in a chain return NULL instead of a valid handle.
	 * 
	 * @return string A string that is unique, or NULL
	 */
	public function getHandle();
}
