<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * User Controller Permission
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class UsersControllerPermissionUser extends ApplicationControllerPermissionAbstract
{
    public function canAdd()
    {
        $user    = $this->getUser();
        $context = $this->getMixer()->getContext();
        $role_id = $context->request->data->get('role_id', 'int');

        // New user role must be less or equal than logged user role
        if($role_id && ($user->getRole() < $role_id)) {
            return false;
        }

        return parent::canAdd();
    }

    public function canEdit()
    {
        $user   = $this->getUser();
        $entity = $this->getModel()->fetch();

        // Don't allow a user to edit another user that has a higher role
        if($user->getRole() < $entity->role_id) {
            return false;
        }

        return parent::canEdit();
    }

    public function canDelete()
    {
        $user   = $this->getUser();
        $entity = $this->getModel()->fetch();

        // Users cannot delete themselves
        if($user->getId() == $entity->id) {
            return false;
        }

        // Don't allow a user to delete another user that has a higher role
        if($user->getRole() < $entity->role_id) {
            return false;
        }

        return parent::canDelete();
    }
}