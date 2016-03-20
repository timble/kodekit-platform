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
 * Groups Database Table
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Component\Users
 */
class DatabaseTableGroups extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'identifiable',
                'groupable' => array(
                    'values'  => 'users',
                    'columns' => array(
                        'collection' => 'users_user_id',
                        'item'       => 'users_group_id'
                    )
                )
            )
        ));

        parent::_initialize($config);
    }
}
