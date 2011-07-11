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

/*if (!KFactory::get('lib.joomla.user')->authorize( 'com_modules', 'manage' )) {
	KFactory::get('lib.joomla.application')->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

if (!KFactory::get('lib.joomla.user')->authorize( 'com_languages', 'manage' )) {
	KFactory::get('lib.joomla.application')->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

if (!KFactory::get('lib.joomla.user')->authorize( 'com_templates', 'manage' )) {
	KFactory::get('lib.joomla.application')->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

if (!KFactory::get('lib.joomla.user')->authorize( 'com_plugins', 'manage' )) {
	KFactory::get('lib.joomla.application')->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}*/

echo KFactory::get('admin::com.extensions.dispatcher')->dispatch();