<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
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

// We like code reuse, so we map the frontend models to the backend models
KFactory::map('site::com.profiles.model.departments', 	'admin::com.profiles.model.departments');
KFactory::map('site::com.profiles.model.offices', 		'admin::com.profiles.model.offices');
KFactory::map('site::com.profiles.model.people', 		'admin::com.profiles.model.people');

// Create the controller dispatcher
KFactory::get('site::com.profiles.dispatcher')->dispatch(KRequest::get('get.view', 'cmd', 'people'));