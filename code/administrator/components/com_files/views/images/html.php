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
 * Images Html View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesViewImagesHtml extends ComDefaultViewHtml
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

		$folders = KFactory::tmp('admin::com.files.controller.folder')
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
		$this->assign('path'              , $config->image_path);
		$this->assign('maxsize'           , $config->upload_maxsize);

		return parent::display();
	}
}
