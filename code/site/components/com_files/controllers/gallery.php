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

class ComFilesControllerGallery extends ComDefaultControllerResource
{
	public function getRequest()
	{
		$request = parent::getRequest();

		$config = KFactory::get('com://admin/files.model.configs')
			->set($request)
			->getItem();
		if (!$config->container || $config->container->isNew()) {
			throw new KControllerException('Invalid Container');
		}

		return $request;
	}
}
