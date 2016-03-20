<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

namespace Kodekit\Component\Users;

use Kodekit\Library;

/**
 * Users Database Table
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Users
 */
class DatabaseTableUsers extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
	{
        $config->append(array(
            'name'       => 'users',
            'behaviors'  => array(
                'modifiable',
                'creatable',
                'lockable',
                'identifiable',
                'authenticatable',
                'groupable',
                'parameterizable',
                'notifiable',
            ),
            'filters'    => array(
                'parameters' => 'json'
            ),
            'column_map' => array(
                'role_id'    => 'users_role_id',
                'group_id'   => 'users_group_id',
                'parameters' => 'params',
            )
        ));

		parent::_initialize($config);
	}
}
