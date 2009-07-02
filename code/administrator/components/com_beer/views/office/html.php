<?php

class BeerViewOffice extends KViewHtml
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.office');

		KRequest::set( 'get.hidemainmenu', 1 );

		$this->assign('office', $model->getItem());

		// Create the toolbar
		KFactory::get('admin::com.beer.toolbar.office')
			->append('save')
			->append('apply')
    		->append('cancel');

		// Display the layout
		parent::display();
	}
}