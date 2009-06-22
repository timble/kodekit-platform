<?php
class HarbourViewBoats extends KViewHtml
{
	public function display($tpl = null)
	{
		$model 	= KFactory::get('admin::com.harbour.model.boats');
		$this->assign('boats', 		$model->getList());
		$this->assign('filter',  	$model->getFilters());
		$this->assign('pagination', $model->getPagination());
	
		$this->displayToolbar();
		
		parent::display($tpl);
	}
	
    public function displayToolbar()
    {
		KFactory::get('admin::com.harbour.toolbar.boats')
    		->append('enable')
			->append('disable')
			->append('delete')
			->append('edit')
			->append('new');
		// alternatively, move all of this inside HarbourToolbarBoats			
    }
}