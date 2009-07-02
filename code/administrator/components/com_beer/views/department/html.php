<?php

class BeerViewDepartment extends KViewHtml
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.department');

		KRequest::set( 'get.hidemainmenu', 1 );

		$this->assign('department', $model->getItem());

		// Create the toolbar
		KFactory::get('admin::com.beer.toolbar.department')
			->append('save')
			->append('apply')
    		->append('cancel');

		// Display the layout
		parent::display();
	}
}