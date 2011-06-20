<?php

class ComFilesViewImagesHtml extends ComFilesViewHtml
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
			->reset();

		$folders = KFactory::tmp('admin::com.files.controller.folder')->tree(true)->browse();
		$this->assign('folders', $folders);

		$config = KFactory::get('admin::com.files.database.row.config');
		$this->assign('path', $config->image_path);
		$this->assign('maxsize', $config->upload_maxsize);

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

		return parent::display();
	}
}
