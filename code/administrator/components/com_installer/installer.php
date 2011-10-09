<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */

/*if (!JFactory::getUser()->authorize( 'com_content', 'manage' )) {
	JFactory::getApplication()->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}*/

// You can put makeup on jimport() but it's still disgusting
JLoader::register('JInstaller'      , JPATH_LIBRARIES.'/joomla/installer/installer.php');
JLoader::register('JInstallerHelper', JPATH_LIBRARIES.'/joomla/installer/helper.php');

echo KService::get('com://admin/installer.dispatcher')->dispatch();