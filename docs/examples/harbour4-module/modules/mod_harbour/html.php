<?php
class ModHarbourHtml extends ModDefaultHtml
{
	public function display()
	{
		// Module parameters
		$direction = $this->params->get('direction', 'ASC');

		// Get ordered list of enabled boats from model
		$model	= KFactory::get('admin::com.harbour.model.boats');
		$boats 	= $model->order('name')->direction($direction)->getList();

		// Assign vars and render view
		$this->assign('boats', $boats);
		
		parent::display();
	}
}