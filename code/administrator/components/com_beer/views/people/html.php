<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerViewPeople extends KViewDefault
{
	public function display()
	{
		// Mixin a menubar object
		$this->mixin( KFactory::get('admin::com.beer.mixin.menu', array('mixer' => $this)));
		$this->displayMenutitle();
		$this->displayMenubar();
		
		$this->setLayout('form'); //@todo added this line because the View is looking for the default.php layout

		//Create the toolbar
		KFactory::get('admin::com.beer.toolbar.people')
			->append('divider')	
			->append('enable')
			->append('disable');

		//Display the layout
		parent::display();
	}
}