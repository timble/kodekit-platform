<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ProfilesViewPerson extends KViewDefault
{
	public function display()
	{
		$user = KFactory::get('lib.joomla.user');
		$this->assign('user', $user);
		
		//Display the layout
		parent::display();
	}
}