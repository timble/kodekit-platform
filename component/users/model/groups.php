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
 * Users Groups Model
 *
 * @author  Arunas Mazeika <github.com/arunasmazeika>
 * @package Kodekit\Component\Users
 */
class ModelGroups extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()->insert('user', 'int');
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        if ($user = $state->user) {
            $query->join(array('users' => 'users_groups_users'), 'users.users_group_id = tbl.users_group_id', 'INNER');
        }

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        if ($user = $state->user) {
            $query->where('users.users_user_id = :user')->bind(array('user' => $user));
        }

        parent::_buildQueryWhere($query);
    }
}