<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Component Loader
 *   
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */

if (!KFactory::get('lib.joomla.user')->authorize( 'com_weblinks', 'manage' )) {
	KFactory::get('lib.joomla.application')->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

echo KFactory::get('admin::com.weblinks.dispatcher')->dispatch();