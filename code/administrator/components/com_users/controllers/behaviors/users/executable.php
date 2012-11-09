<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * User Executable Controller Behavior Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerBehaviorUserExecutable extends ComDefaultControllerBehaviorExecutable
{
    public function canAdd()
    {
        $user = JFactory::getUser();
        $context = $this->getMixer()->getCommandContext();
        $data = $context->data;

        // New user role must be less or equal than logged user role
        if($data && ($role_id = $data->role_id) && ($user->role_id < $role_id))
        {
            return false;
        }

        return parent::canAdd();
    }

    public function canEdit()
    {
        $user = JFactory::getUser();
        $item = $this->getModel()->getItem();

        // Don't allow users below super administrator to edit a super administrator
        if(($item->group_id == 25) && ($user->role_id < 25))
        {
            return false;
        }

        return parent::canEdit();
    }

    public function canDelete() {
        $user = JFactory::getUser();
        $item = $this->getModel()->getItem();

        if($user->id == $item->id)
        {
            // Users cannot delete themselves
            return false;
        }

        if ($user->role_id < 25 && ($item->role_id >= $user->role_id)) {
            // Administrators and below are only allowed to delete user accounts with lower role levels
            // than their own.
            return false;
        }

        return parent::canDelete();
    }
}