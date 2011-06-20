<?php

class ComFilesViewFilesHtml extends ComFilesViewHtml
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'auto_assign' => false
		));
		parent::_initialize($config);
	}

	public function display()
	{
		$this->getToolbar()
			->reset()
			->append(KFactory::tmp('admin::com.files.toolbar.button.delete'));

		$root = str_replace('\\', '/', JPATH_ROOT.DS);
		$basepath = str_replace($root, '', $this->getModel()->getState()->basepath);
		$this->assign('path', $basepath);

		$folders = KFactory::tmp('admin::com.files.controller.folder')
			->identifier($this->getModel()->getState()->identifier)
			->tree(true)
			->browse();
		$this->assign('folders', $folders);

		$config = KFactory::get('admin::com.files.database.row.config');

		// prepare an extensions array for fancyupload
		$extensions = $config->upload_extensions;
		if (empty($extensions)) {
			$str = '*.*';
		}
		else {
			foreach ($extensions as &$ext) {
				$ext = '*.'.$ext;
			}
			$str = implode('; ', $extensions);
		}

		$this->assign('allowed_extensions', $str);
		$this->assign('maxsize', $config->upload_maxsize);
		if (!$this->editor) {
			$this->assign('editor', '');
		}

		return parent::display();
	}
}
