<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Users Groups Model
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
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