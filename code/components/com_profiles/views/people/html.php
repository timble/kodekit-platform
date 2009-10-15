<?php
/**
 * @version		$Id: html.php 215 2009-09-20 03:27:20Z johan $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ProfilesViewPeopleHtml extends KViewHtml
{
	public function display()
	{		
		$this->assign('letters_name', $this->getModel()->getLetters());

		//Display the layout
		parent::display();
	}
}