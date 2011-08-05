<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Files Html View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesViewFilesHtml extends ComDefaultViewHtml
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
		$state = $this->getModel()->getState();

		$folders = KFactory::tmp('admin::com.files.controller.folder')
			->identifier($state->identifier)
			->tree(true)
			->browse();

		$this->assign('folders', $folders);

		$config = KFactory::get('admin::com.files.database.row.config');

		// prepare an extensions array for fancyupload
		$extensions = $config->upload_extensions;
		if(!empty($extensions))
		{
			foreach ($extensions as &$ext) {
				$ext = '*.'.$ext;
			}
			$str = implode('; ', $extensions);
		}
		else $str = '*.*';

		$this->assign('allowed_extensions', $str);
		$this->assign('maxsize'           , $config->upload_maxsize);
		$this->assign('path'              , $state->identifier->relative_path);

		if (!$this->editor) {
			$this->assign('editor', '');
		}

		return parent::display();
	}
}
