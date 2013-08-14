<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Containers Database Table
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class DatabaseTableContainers extends Library\DatabaseTableAbstract
{
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'filters' => array(
				'slug' 	     => 'cmd',
				'path'       => 'com:files.filter.path',
				'parameters' => 'json'
			),
			'behaviors' => array(
			    'sluggable' => array('columns' => array('id', 'title'))
			)
		));

		parent::_initialize($config);
	}
}
