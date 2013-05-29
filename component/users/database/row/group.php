<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Group Database Row
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Nooku\Component\Users
 */
class DatabaseRowGroup extends Library\DatabaseRowTable
{   
    public function save()
    {
    	$result = parent::save();

        if ($this->users)
        {
            // Add new users to group
            foreach ($this->users as $user)
            {
                $group_user = $this->getObject('com:users.database.row.groups_users');

                $group_user->group_id = $this->id;
                $group_user->user_id  = $user;

                if (!$group_user->load()) {
                    $group_user->save();
                }
            }

            // Remove users no longer attached to group
            foreach ($this->getObject('com:users.model.groups_users')->group_id($this->id)->getRowset() as $group_user)
            {
                // Remove all users that are no longer selected
                if (!in_array($group_user->user_id, $this->users)) {
                    $group_user->delete();
                }
            }
        }
       
        return $result;
    }
}