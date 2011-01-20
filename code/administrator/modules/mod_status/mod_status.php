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
$user	= JFactory::getUser();
$lang	= JFactory::getLanguage();

$output = array();

if ($task == 'edit' || $task == 'editA' || JRequest::getInt('hidemainmenu') ) {
	 // Print the logout message
	 $class = "class='disabled'";
} else {
	$class = "";
}

// Print the preview button
$output[] = '<li class="preview"><a href="'.JURI::root().'" target="_blank">'.JText::_('Preview').'</a></li>';

// Print the logout message
$output[] = '<li '.$class.'><a href="index.php?option=com_users&view=user&task=edit&cid[0]='.$user->id.'">'.JText::_('My Profile').'</a></li>';

// Print the logout message
$output[] = '<li '.$class.'><a href="index.php?option=com_login&amp;task=logout">'.JText::_('Logout').'</a></li>';

// reverse rendering order for rtl display
if ( $lang->isRTL() ) {
	$output = array_reverse( $output );
}

?>

<ul id="statusmenu">

<?php
// output the module
foreach ($output as $item){
	echo $item;
} ?>

</ul>