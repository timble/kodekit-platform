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
class comProfilesControllerDashboard extends KControllerAbstract
{
	protected function _actionRead()
	{
		KFactory::get('admin::com.profiles.view.dashboard.html')
			->display();
	}
}