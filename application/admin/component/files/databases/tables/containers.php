<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Containers Database Table Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesDatabaseTableContainers extends KDatabaseTableDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'filters' => array(
				'slug' 				 => 'cmd',
				'path'               => 'com://admin/files.filter.path',
				'parameters'         => 'json'
			),
			'behaviors' => array(
			    'lib://nooku/database.behavior.sluggable' => array('columns' => array('id', 'title'))
			)
		));

		parent::_initialize($config);
	}
}
