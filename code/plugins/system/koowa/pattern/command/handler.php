<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @package		Koowa_Pattern
 * @subpackage	Command
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Command interface
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_Pattern
 * @subpackage  Command
 */
abstract class KPatternCommandHandler extends KObject
{
	/**
	 * Generic Command handler
	 * 
	 * This functions creates a specific command based on the command name and calls it
	 * 
	 * @param string  $name		The command name
	 * @param object  $args		The command arguments
	 *
	 * @return	mixed
	 */
	function execute( $name, $args ) 
	{
		$result = null;
		if(method_exists($this, $name)) {
			$result = $this->$name($args);
		}
		
		return $result;
	}
}
