<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */

if (!JFactory::getUser()->authorize( 'com_banners', 'manage' )) {
	JFactory::getApplication()->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

echo KFactory::get('com://admin/banners.dispatcher')->dispatch();