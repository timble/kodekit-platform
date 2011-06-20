<?php

class ComFilesControllerImage extends ComDefaultControllerResource
{
	protected function _initalize(KConfig $config)
	{
		$config->append(array(
			'persistent' => false
		));
		parent::_initialize($config);
	}

	public function getView()
	{
		$view = parent::getView();

		if ($view) {
			$view->assign('editor', $this->_request->e_name);
		}

		return $view;
	}
}