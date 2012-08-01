<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author      Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package     Nooku_Server
 * @subpackage  Contacts   
 */

if (!JFactory::getUser()->authorize( 'com_contacts', 'manage' )) {
	JFactory::getApplication()->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

KLoader::loadIdentifier('com://admin/contacts.aliases');

echo KService::get('com://admin/contacts.dispatcher')->dispatch();