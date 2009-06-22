<?php
class HarbourViewBoat extends KViewHtml
{
	public function display($tpl = null)
	{
		$model = KFactory::get('admin::com.harbour.model.boats');
		$this->assign('boat', $model->getItem());
		
		$this->displayToolbar();

		parent::display($tpl);
	}
	
    public function displayToolbar()
    {
		KFactory::get('admin::com.harbour.toolbar.boat')
    		->append('save')
			->append('apply')
			->append('cancel');
    }
}

