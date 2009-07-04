<?php
class BeerViewPeople extends KViewHtml 
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.people');

	 	$this->assign('people', 	$model->getList());
		$this->assign('pagination', $model->getPagination());

		//Off to the layout
		parent::display();
	}
}