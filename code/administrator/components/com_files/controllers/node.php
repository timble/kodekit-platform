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
 * Node Controller Class 
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesControllerNode extends ComDefaultControllerDefault
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
}
