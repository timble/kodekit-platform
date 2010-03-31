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
		$people			= KFactory::get('admin::com.profiles.model.people');
		$departments 	= KFactory::get('admin::com.profiles.model.departments');
		$offices 		= KFactory::get('admin::com.profiles.model.offices');
		
		$people->getState()->order 		= 'profiles_person_id';
		$people->getState()->direction 	= 'desc';
		$people->getState()->limit 		= '5';
		
		$departments->getState()->order 	= 'people';
		$departments->getState()->direction = 'desc';
		$departments->getState()->limit 	= '5';
		
		$offices->getState()->order 	= 'people';
		$offices->getState()->direction = 'desc';
		$offices->getState()->limit 	= '5';	
		
		//Reset the toolbar
		KFactory::get('admin::com.profiles.toolbar.dashboard')->reset();
	
		$this->assign('departments', 	$departments->getList());
		$this->assign('offices', 		$offices->getList());
		$this->assign('people', 		$people->getList());
		
		parent::display();
	}
}