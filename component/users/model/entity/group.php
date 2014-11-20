<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Group Model Entity
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Nooku\Component\Users
 */
class ModelEntityGroup extends Library\ModelEntityRow
{   
    public function save()
    {
    	$result = parent::save();

        if ($this->users)
        {
            // Add new users to group
            foreach ($this->users as $user)
            {
                $properties = array(
                    'group_id' => $this->id,
                    'user_id'  => $user
                );

                $group_user = $this->getObject('com:users.model.groups_users')
                    ->setState($properties)
                    ->fetch();

                if ($group_user->isNew())
                {
                    $group_user->setProperties($properties);
                    $group_user->save();
                }
            }

            // Remove users no longer attached to group
            foreach ($this->getObject('com:users.model.groups_users')->group_id($this->id)->fetch() as $group_user)
            {
                // Remove all users that are no longer selected
                if (!in_array($group_user->user_id, $this->users))
                {
                    $entity = $this->getObject('com:users.model.groups_users')
                        ->group_id($this->id)
                        ->user_id($group_user->user_id)
                        ->fetch();

                    $entity->delete();
                }
            }
        } 
        else 
        {
            $group_users = $this->getObject('com:users.model.groups_users')
                ->group_id($this->id)
                ->fetch();

            if (count($group_users) && !$group_users->delete()) {
                throw new \RuntimeException('Failed to delete users from group');
            }
        }
       
        return $result;
    }
}