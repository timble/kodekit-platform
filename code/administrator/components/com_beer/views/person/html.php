<?php

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
			//->append('apply')
    		->append('cancel');

		// Display the layout
		parent::display();
	}
}