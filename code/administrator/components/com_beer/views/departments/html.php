<?php
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