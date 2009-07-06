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

class BeerViewDepartments extends KViewHtml
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.departments');

		// Mixin a menubar object
		$this->mixin( KFactory::get('admin::com.beer.mixin.menu', array('mixer' => $this)));
		$this->displayMenutitle();
		$this->displayMenubar();

	 	$this->assign('departments', 	$model->getList());
		$this->assign('filter',     $model->getFilters());
		$this->assign('pagination', $model->getPagination());

		//Create the toolbar
		KFactory::get('admin::com.beer.toolbar.departments')
			->append('enable')
			->append('disable')
			->append('divider')
			->append('new')
			->append('edit')
			->append('delete');

		//Display the layout
		parent::display();
	}
}