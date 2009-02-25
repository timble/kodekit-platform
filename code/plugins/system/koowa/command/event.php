<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Command
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Event Command
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Command
 * @uses 		KFactory
 */
class KCommandEvent extends KObject implements KPatternCommandInterface 
{
	/**
	 * Command handler
	 * 
	 * @param string  $name		The command name
	 * @param mixed   $args		The command arguments
	 *
	 * @return boolean
	 */
	public function execute( $name, $args) 
	{
		$parts = explode('.', $name);	
		$event = 'on'.KInflector::implode($parts);
	
		$dispatcher = KFactory::get('lib.koowa.event.dispatcher');
		return $dispatcher->dispatch($event, $args);
	}
}
