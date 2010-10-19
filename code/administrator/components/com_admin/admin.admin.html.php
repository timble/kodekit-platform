<?php
/**
 * @version		$Id: admin.admin.html.php 18162 2010-07-16 07:00:47Z ian $
 * @package		Joomla
 * @subpackage	Admin
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

/**
* @package		Joomla
* @subpackage	Admin
*/
class HTML_admin_misc
{
	function get_php_setting($val)
	{
		$r =  (ini_get($val) == '1' ? 1 : 0);
		return $r ? JText::_( 'ON' ) : JText::_( 'OFF' ) ;
	}

	function get_server_software()
	{
		if (isset($_SERVER['SERVER_SOFTWARE'])) {
			return $_SERVER['SERVER_SOFTWARE'];
		} else if (($sf = getenv('SERVER_SOFTWARE'))) {
			return $sf;
		} else {
			return JText::_( 'n/a' );
		}
	}

	function system_info( )
	{
		global $mainframe;

		//Load switcher behavior
		JHTML::_('behavior.switcher');

		$db =& JFactory::getDBO();

		$contents = '';
		ob_start();
		require_once(JPATH_COMPONENT.DS.'tmpl'.DS.'navigation.php');
		$contents = ob_get_contents();
		ob_clean();

		$document =& JFactory::getDocument();
		$document->setBuffer($contents, 'modules', 'submenu');
		?>
		<form action="index.php" method="post" name="adminForm">

		<div id="config-document">
			<div id="page-site">
				<table class="noshow">
				<tr>
					<td>
						<?php require_once(JPATH_COMPONENT.DS.'tmpl'.DS.'sysinfo_system.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-phpsettings">
				<table class="noshow">
				<tr>
					<td>
						<?php require_once(JPATH_COMPONENT.DS.'tmpl'.DS.'sysinfo_phpsettings.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-config">
				<table class="noshow">
				<tr>
					<td>
						<?php require_once(JPATH_COMPONENT.DS.'tmpl'.DS.'sysinfo_config.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-directory">
				<table class="noshow">
				<tr>
					<td>
						<?php require_once(JPATH_COMPONENT.DS.'tmpl'.DS.'sysinfo_directory.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-phpinfo">
				<table class="noshow">
				<tr>
					<td>
						<?php require_once(JPATH_COMPONENT.DS.'tmpl'.DS.'sysinfo_phpinfo.php'); ?>
					</td>
				</tr>
				</table>
			</div>
		</div>

		<div class="clr"></div>
		<?php
	}
}

function writableCell( $folder, $relative=1, $text='', $visible=1 )
{
	$writeable		= '<b><font color="green">'. JText::_( 'Writable' ) .'</font></b>';
	$unwriteable	= '<b><font color="red">'. JText::_( 'Unwritable' ) .'</font></b>';

	echo '<tr>';
	echo '<td class="item">';
	echo $text;
	if ( $visible ) {
		echo $folder . '/';
	}
	echo '</td>';
	echo '<td >';
	if ( $relative ) {
		echo is_writable( "../$folder" )	? $writeable : $unwriteable;
	} else {
		echo is_writable( "$folder" )		? $writeable : $unwriteable;
	}
	echo '</td>';
	echo '</tr>';
}