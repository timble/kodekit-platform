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
 * Image Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesControllerImage extends ComDefaultControllerResource
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'persistent' => false,
			'request' => array(
				'identifier' => 'files.files'
			)
		));

		parent::_initialize($config);
	}

	public function setRequest(array $request)
	{
		$config = KFactory::get('admin::com.files.database.row.config');
		$row = KFactory::tmp('admin::com.files.model.paths')->identifier($request['identifier'])->getItem();
		$config->setData(json_decode($row->parameters, true));

		return parent::setRequest($request);
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