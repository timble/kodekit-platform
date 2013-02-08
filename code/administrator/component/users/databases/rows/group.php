<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Group Database Row Class
 *
 * @author      Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersDatabaseRowGroup extends KDatabaseRowTable
{   
    public function save()
    {
    	$result = parent::save();

        if ($this->users)
        {
            // Add new users to group
            foreach ($this->users as $user)
            {
                $group_user = $this->getService('com://admin/users.database.row.groups_users');

                $group_user->group_id = $this->id;
                $group_user->user_id  = $user;

                if (!$group_user->load()) {
                    $group_user->save();
                }
            }

            // Remove users no longer attached to group
            foreach ($this->getService('com://admin/users.model.groups_users')->group_id($this->id)->getRowset() as $group_user)
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