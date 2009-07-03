<?php
class BeerViewPeople extends KViewHtml 
{
	public function display()
	{
		$model = KFactory::get('admin::com.beer.model.people');

		// Mixin a menubar object
		$this->mixin( new BeerMixinMenu(array('mixer' => $this)));
		$this->displayMenutitle();
		$this->displayMenubar();
		
		$attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'submitform();');

	 	$this->assign('people', 	$model->getList());
		$this->assign('filter',     $model->getFilters());
		$this->assign('pagination', $model->getPagination());
		$this->assign('attribs', 	$attribs);

		//Create the toolbar
		KFactory::get('admin::com.beer.toolbar.people')
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