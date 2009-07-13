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
 * Command handler
 * 
 * The command handler will translate the command name to a onCommandName format 
 * and call it for the object class to handle it if the method exists.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Command
 * @uses 		KFactory
 * @uses 		KInflector
 */
class KCommandHandler extends KObject implements KPatternCommandInterface 
{
	/**
	 * Command handler
	 * 
	 * @param string  The command name
	 * @param mixed   The command arguments
	 *
	 * @return boolean  Can return both true or false.  
	 */
	final public function execute( $name, $args) 
	{
		$parts    = explode('.', $name);	
		$function = 'on'.KInflector::implode($parts);
		
		if(method_exists($this, $function)) {
			return $this->$function($args);
		}
	
		return true;
	}
}