<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

// Check if Koowa is active
if(!defined('KOOWA')) {
    JError::raiseWarning(0, JText::_("Koowa wasn't found. Please install the Koowa plugin and enable it."));
    return;
}

// Require the defines
KLoader::load('admin::com.profiles.defines');


/**
 * Until lib.koowa.template.helper.behavior.framework is here, map it to our profiles helper for testing.
 * Remove it once the patch is accepted.
 */
KFactory::map('lib.koowa.template.helper.behavior', 'admin::com.profiles.helper.behavior'); 

/**
 * Patch KTemplateFilterToken with this later
 */
KTemplate::addRules(array(KFactory::get('admin::com.profiles.filter.token')));

 
// Create the controller dispatcher
KFactory::get('admin::com.profiles.dispatcher')->dispatch(KRequest::get('get.view', 'cmd', 'dashboard'));
	