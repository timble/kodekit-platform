<?php
/**
 * @version     $Id: paths.php 2071 2011-06-28 13:01:36Z ercanozkaya $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Containers Database Table Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesDatabaseTableContainers extends KDatabaseTableAbstract
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'filters' => array(
				'files_container_id' => 'identifier',
				'path'               => 'com.files.filter.path',
				'parameters'         => 'json'
			),
			'identity_column' => 'files_container_id'
		));

		parent::_initialize($config);
	}
}
