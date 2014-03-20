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
 * Groupable Database Behavior
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorGroupable extends Library\DatabaseBehaviorAbstract
{
    protected function _afterUpdate(Library\DatabaseContextInterface $context)
    {
        // Same as insert.
        return $this->_afterInsert($context);
    }

    protected function _afterInsert(Library\DatabaseContextInterface $context)
    {
        $user = $context->data;

        if (isset($user->groups) && $user->getStatus() != Library\Database::STATUS_FAILED)
        {
            if ($groups = $user->groups)
            {
                $this->_insertGroups($groups, $context);
            }

            $this->_cleanup($groups, $context);

            // Unset data for avoiding un-necessary queries on subsequent saves.
            unset($user->groups);
        }
    }

    protected function _insertGroups($groups, Library\DatabaseContextInterface $context)
    {
        $user   = $context->data;
        $groups = (array) $groups;

        $query = $this->getObject('lib:database.query.insert');
        $query->table('users_groups_users');
        $query->columns(array('users_user_id', 'users_group_id'));

        foreach ($groups as $group)
        {
            $query->values(array($user->id, $group));
        }

        // Just ignore duplicate entries.
        $query = str_replace('INSERT', 'INSERT IGNORE', (string) $query);

        $context->subject->getAdapter()->execute($query);
    }

    protected function _cleanup($groups, Library\DatabaseContextInterface $context)
    {
        $groups = (array) $groups;

        $user    = $context->data;
        $query   = $this->getObject('lib:database.query.select')->table('users_groups_users')
                        ->columns(array('users_group_id'))->where('users_user_id = :user')->bind(array('user' => $user->id));
        $current = $context->subject->select($query, Library\Database::FETCH_FIELD_LIST);

        $remove = array_diff($current, $groups);

        if (count($remove))
        {
            $query = $this->getObject('lib:database.query.delete')->table('users_groups_users')
                          ->where('users_group_id IN :groups')->where('users_user_id = :user')
                          ->bind(array('groups' => $remove, 'user' => $user->id));

            $context->subject->getAdapter()->execute((string) $query);
        }
    }
}