<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Modules Database Table
 *
 * @author  Stian Didriksen <http://github.com/stipsan>
 * @package Kodekit\Component\Pages
 */
class DatabaseTableModules extends Library\DatabaseTableAbstract
{
    public function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable', 'parameterizable', 'identifiable', 'accessible',
                'com:pages.database.behavior.orderable' => array('strategy' => 'flat')
            ),
            'filters' => array(
                'parameters'  => 'json'
            ),
            'column_map' => array(
                'parameters' => 'params',
            )
        ));

        parent::_initialize($config);
    }

	/**
	 * Get default values for all columns
	 *
	 * This method is specialized in order to set the default module position
	 * and published state
	 *
	 * @return  array
	 */
	public function getDefaults()
	{
		$defaults = parent::getDefaults();

		$defaults['position']    = 'left';
		$defaults['published']	 = 1;

		return $defaults;
	}
}