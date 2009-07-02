<?php
class BeerViewOffices extends KViewHtml 
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.offices');

		// Mixin a menubar object
		$this->mixin( new BeerMixinMenu(array('mixer' => $this)));
		$this->displayMenutitle();
		$this->displayMenubar();

	 	$this->assign('offices', 	$model->getList());
		$this->assign('filter',     $model->getFilters());
		$this->assign('pagination', $model->getPagination());

		//Create the toolbar
		KFactory::get('admin::com.beer.toolbar.offices')
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