<?php
class BeerViewDepartments extends KViewHtml 
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.departments');

	 	$this->assign('departments', $model->getList());
		$this->assign('pagination', $model->getPagination());

		//Off to the layout
		parent::display();
	}
}