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

		$folders = KFactory::get('com://admin/files.controller.folder')
			->container($state->container)
			->tree(true)
			->browse();

		$this->assign('folders', $folders);

		$config = KFactory::get('com://admin/files.model.configs')->getItem();

		// prepare an extensions array for fancyupload
		$extensions = $config->upload_extensions;

		$this->assign('allowed_extensions', $extensions);
		$this->assign('maxsize'           , $config->upload_maxsize);
		$this->assign('path'              , $state->container->relative_path);
		$this->assign('sitebase'          , ltrim(JURI::root(true), '/'));
		$this->assign('token'             , JUtility::getToken());
		$this->assign('session'           , JFactory::getSession());

		if (!$this->editor) {
			$this->assign('editor', '');
		}

		return parent::display();
	}
}
