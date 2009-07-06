<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerViewPerson extends KViewHtml
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.person');

		KRequest::set( 'get.hidemainmenu', 1 );

		$this->assign('person', $model->getItem());

		// Create the toolbar
		KFactory::get('admin::com.beer.toolbar.person')
			->append('save')
			->append('apply')
    		->append('cancel');

		// Display the layout
		parent::display();
	}
}