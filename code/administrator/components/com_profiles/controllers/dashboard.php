<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Dashboard Controller
 *
 * @package		Profiles
 */
class ComProfilesControllerDashboard extends ComProfilesControllerDefault
{
	public function displayView()
	{
		KRequest::set('get.hidemainmenu', 0);
		KRequest::set('get.layout', 'default');
		
		parent::displayView();
	}
}