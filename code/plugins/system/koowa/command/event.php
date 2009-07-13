<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Command
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.koowa.org
 */

/**
 * Event Command
 * 
 * The event commend will translate the command name to a onCommandName format 
 * and let the event dispatcher dispatch to any registered event handlers.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Command
 * @uses 		KFactory
 * @uses 		KEventDispatcher
 * @uses 		KInflector
 */
class KCommandEvent extends KObject implements KPatternCommandInterface 
{
	/**
	 * Command handler
	 * 
	 * @param string  The command name
	 * @param mixed   The command arguments
	 *
	 * @return boolean	Always returns true
	 */
	final public function execute( $name, $args) 
	{
		$parts = explode('.', $name);	
		$event = 'on'.KInflector::implode($parts);
	
		$dispatcher = KFactory::get('lib.koowa.event.dispatcher');
		$dispatcher->dispatch($event, $args);
		
		return true;
	}
}
