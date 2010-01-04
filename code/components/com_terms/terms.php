<?php
/**
 * @version		$Id$
 * @package		profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

// Check if Koowa is active
if(!defined('KOOWA')) {
	JError::raiseWarning(0, JText::_("Koowa wasn't found. Please install the Koowa plugin and enable it."));
	return;
}

// Factory mappings
KFactory::map('site::com.terms.controller.term', 'admin::com.terms.controller.terms');

// Create the controller dispatcher
KFactory::get('admin::com.terms.dispatcher')->dispatch(KRequest::get('get.view', 'cmd', 'terms'));