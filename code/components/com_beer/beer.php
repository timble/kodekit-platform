<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

// Check if Koowa is active
if(!defined('KOOWA')) {
	JError::raiseWarning(0, JText::_("Koowa wasn't found. Please install the Koowa plugin and enable it."));
	return;
}

// We like code reuse, so we inject the backend models in the frontend models
KFactory::set('site::com.beer.model.departments', 	KFactory::get('admin::com.beer.model.departments'));
KFactory::set('site::com.beer.model.offices', 		KFactory::get('admin::com.beer.model.offices'));
KFactory::set('site::com.beer.model.people', 		KFactory::get('admin::com.beer.model.people'));

// Create the controller dispatcher
KFactory::get('site::com.beer.dispatcher', array('default_view' => 'people'))->dispatch();