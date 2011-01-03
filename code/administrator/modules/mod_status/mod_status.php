<?php
/**
* @version		$Id: mod_status.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

global $task;

// Initialize some variables
$config		=& JFactory::getConfig();
$user		=& JFactory::getUser();
$db			=& JFactory::getDBO();
$lang		=& JFactory::getLanguage();
$session	=& JFactory::getSession();

$sid	= $session->getId();
$output = array();

// Print the preview button
$output[] = "<span class=\"preview\"><a href=\"".JURI::root()."\" target=\"_blank\">".JText::_('Preview')."</a></span>";

if ($task == 'edit' || $task == 'editA' || JRequest::getInt('hidemainmenu') ) {
	 // Print the logout message
	 $output[] = "<span class=\"logout\">".JText::_('Logout')."</span>";
} else {
	// Print the logout message
	$output[] = "<span class=\"logout\"><a href=\"index.php?option=com_login&amp;task=logout\">".JText::_('Logout')."</a></span>";
}

// reverse rendering order for rtl display
if ( $lang->isRTL() ) {
	$output = array_reverse( $output );
}

// output the module
foreach ($output as $item){
	echo $item;
}