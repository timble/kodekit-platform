<?php
/**
 * @version     $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
 * @package     Koowa
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPLv2
 * @link        http://www.koowa.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Koowa System plugin
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package		Koowa
 * @version		1.0
 */
class plgSystemKoowa extends JPlugin
{
	public function onAfterInitialise()
	{
		if( !plgSystemKoowa::isKoowaFriendly()) {	
			return;	
		}
		
		// Decorate the database connector object and then replace it with the decorated version.
		$db  =& JFactory::getDBO();
		$db  = new KDatabaseDefault($db);
	}
	
	/**
	 * Check if the current request requires Koowa to be turned off
	 * 
	 * Eg. Koowa should be disabled when uninstalling plugins
	 *
	 * @return	bool
	 */
	public static function isKoowaFriendly()
	{
		$result = true;
		
		
		// are we uninstalling a plugin?
		if(JRequest::getCmd('option') == 'com_installer' 
			AND JRequest::getCmd('task') == 'remove'
			AND JRequest::getCmd('type') == 'plugins' ) {
			$result = false;
		}
		
		return $result;
	}
}


if( plgSystemKoowa::isKoowaFriendly()) {	
	// Require the library loader
	require_once dirname(__FILE__).DS.'koowa'.DS.'koowa.php';	
}