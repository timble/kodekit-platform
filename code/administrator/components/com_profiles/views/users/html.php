<?php
/**
 * @version		$Id: html.php 234 2009-09-30 01:40:02Z johan $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ProfilesViewUsers extends KViewDefault
{
	public function display()
	{
		// Mixin a menubar object
		$this->mixin( KFactory::get('admin::com.profiles.mixin.menu', array('mixer' => $this)));
		$this->displayMenutitle();
		$this->displayMenubar();

		//Create the toolbar
		KFactory::get('admin::com.profiles.toolbar.users')
			->append('divider')	
			->append('enable')
			->append('disable');

		//Display the layout
		parent::display();
	}
}