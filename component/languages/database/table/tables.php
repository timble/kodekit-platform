<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Tables Database Table
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Languages
 */
class DatabaseTableTables extends Library\DatabaseTableAbstract
{
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'behaviors' => array(
                'identifiable',
			)
		));

		parent::_initialize($config);
	}
}
