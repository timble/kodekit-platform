<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions   
 */

/*if (!JFactory::getUser()->authorize( 'com_modules', 'manage' )) {
	JFactory::getApplication()->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

if (!JFactory::getUser()->authorize( 'com_languages', 'manage' )) {
	JFactory::getApplication()->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

if (!JFactory::getUser()->authorize( 'com_templates', 'manage' )) {
	JFactory::getApplication()->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

if (!JFactory::getUser()->authorize( 'com_plugins', 'manage' )) {
	JFactory::getApplication()->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}*/

echo KFactory::get('com://admin/extensions.dispatcher')->dispatch();