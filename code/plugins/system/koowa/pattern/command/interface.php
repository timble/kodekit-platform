<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Pattern
 * @subpackage	Command
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Command Interface 
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Pattern
 * @subpackage  Command
 */
interface KPatternCommandInterface
{
	/**
	 * Generic Command handler
	 * 
	 * @param string $name		The command name
	 * @param mixed  $args		The command arguments
	 *
	 * @return	boolean
	 */
	public function execute( $name, $args);
	
	/**
	 * This function returns an unique identifier for the object. This id can be used as 
	 * a hash key for storing objects or for identifying an object
	 * 
	 * @return string A string that is unique
	 */
	public function getHandle();
}
