<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author      Richie Mortimer <http://nooku.assembla.com/profile/ravenlife>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage   
 */

if (!KFactory::get('lib.joomla.user')->authorize( 'com_frontpage', 'manage' )) {
	KFactory::get('lib.joomla.application')->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

echo KFactory::get('admin::com.frontpage.dispatcher')->dispatch();