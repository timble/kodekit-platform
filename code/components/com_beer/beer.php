<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id$
 * @package		Beer
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
KFactory::map('site::com.beer.model.departments', 	'admin::com.beer.model.departments');
KFactory::map('site::com.beer.model.offices', 		'admin::com.beer.model.offices');
KFactory::map('site::com.beer.model.people', 		'admin::com.beer.model.people');

// Create the controller dispatcher
KFactory::get('site::com.beer.dispatcher', array('default_view' => 'people'))->dispatch();