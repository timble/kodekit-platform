<?php
/**
 * @version     $Id: node.php 911 2011-09-16 13:28:15Z ercanozkaya $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Node Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesViewGalleryHtml extends ComDefaultViewHtml
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
		// TODO: pagination
		
		$state = $this->getModel()->getState();

		$folders = KFactory::get('com://admin/files.controller.folder')
			->container($state->container)
			->folder($state->folder)
			->browse();
		
		$images = KFactory::get('com://admin/files.controller.file')
			->container($state->container)
			->folder($state->folder)
			->types(array('image'))
			->browse();
			
		$thumbnails = KFactory::get('com://admin/files.controller.thumbnail')
			->container($state->container)
			->folder('/'.$state->folder)
			->browse();

		$thumbs = array();
		foreach ($thumbnails as $thumb) {
			$thumbs[$thumb->filename] = $thumb;
		}
		
		$config = KFactory::get('com://admin/files.model.configs')->getItem();

		$this->assign('container', $config->container);
		$this->assign('path', $config->container->relative_path);
		$this->assign('images', $images);
		$this->assign('folders', $folders);
		$this->assign('thumbnails', $thumbs);

		return parent::display();
	}
}