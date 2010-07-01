<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesViewDashboardHtml extends ComProfilesViewHtml
{
	public function display()
	{
		//Reset the toolbar
		KFactory::get('admin::com.profiles.toolbar.dashboard')->reset();
	
		return parent::display();
	}
}