<?php
class BeerViewOffices extends KViewHtml 
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.offices');

	 	$this->assign('offices', $model->getList());
		$this->assign('pagination', $model->getPagination());

		//Off to the layout
		parent::display();
	}
}