<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerViewDashboard extends KViewDefault
{
	public function display()
	{
		$people			= KFactory::get('admin::com.beer.model.people');
		$departments 	= KFactory::get('admin::com.beer.model.departments');
		$offices 		= KFactory::get('admin::com.beer.model.offices');
		
		$people->getState()->order 		= 'beer_person_id';
		$people->getState()->direction 	= 'desc';
		$people->getState()->limit 		= '5';
		
		$departments->getState()->order 	= 'people';
		$departments->getState()->direction = 'desc';
		$departments->getState()->limit 	= '5';
		
		$offices->getState()->order 	= 'people';
		$offices->getState()->direction = 'desc';
		$offices->getState()->limit 	= '5';
		
		// Mixin a menubar object
		$this->mixin( KFactory::get('admin::com.beer.mixin.menu', array('mixer' => $this)));
		$this->displayMenutitle();
		$this->displayMenubar();
		
		//Create the toolbar
		KFactory::get('admin::com.beer.toolbar.dashboard');
		
		$this->assign('departments', 	$departments->getList());
		$this->assign('offices', 		$offices->getList());
		$this->assign('people', 		$people->getList());

		//Display the layout
		parent::display();
	}
}