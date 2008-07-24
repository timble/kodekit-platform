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

// Require the library loader
require_once dirname(__FILE__).DS.'koowa'.DS.'koowa.php';

/**
 * Koowa System plugin
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package		Koowa
 * @version		1.0
 */
class plgSystemKoowa extends JPlugin
{
	/**
	 * Decorate the database connector object and then replace it with the decorated version.
	 *
	 * @access	public
	 * @return	void
	 */
	public function onAfterInitialise()
	{
		//Create the database proxy object
		$db  =& JFactory::getDBO();
		$db  = new KDatabaseDefault($db);
	}
}