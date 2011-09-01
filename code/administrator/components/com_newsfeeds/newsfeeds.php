<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author      Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */

if (!KFactory::get('joomla:user')->authorize( 'com_newsfeeds', 'manage' )) {
	KFactory::get('joomla:application')->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

echo KFactory::get('com://admin/newsfeeds.dispatcher')->dispatch();