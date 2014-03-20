<?php
/**
 * Nooku Framework - http://www.nooku.org
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
        // Only administrators can add users.
        return $this->getUser()->hasRole('administrator');
    }

    public function canDelete()
    {
        $user   = $this->getUser();
        $entity = $this->getModel()->getRow();

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