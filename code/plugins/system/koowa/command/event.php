<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @package		Koowa_Command
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Event Command
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_Command
 */
class KCommandEvent extends KPatternCommandHandler
{
	/**
	 * Generic Command handler
	 * 
	 * This functions creates a specific command based on the command name and calls it
	 * 
	 * @param string  $name		The command name
	 * @param object  $args		The command arguments
	 *
	 * @return	boolean
	 */
	function onCommand( $name, $args ) 
	{
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger($name, (array) $args);

		return true;
	}
}
