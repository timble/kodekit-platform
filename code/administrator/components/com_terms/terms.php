<?php
/** 
 * @version		$Id: tags.php 301 2009-10-24 21:32:57Z johan $
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

// Check if Koowa is active
if(!defined('KOOWA')) {
    JError::raiseWarning(0, JText::_("Koowa wasn't found. Please install the Koowa plugin and enable it."));
    return;
}

// Create the controller dispatcher
KFactory::get('admin::com.terms.dispatcher')->dispatch(KRequest::get('get.view', 'cmd', 'terms'));
	