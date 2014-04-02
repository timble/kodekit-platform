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
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Users
 */
class UsersControllerPermissionUser extends ApplicationControllerPermissionAbstract
{
    public function canRead()
    {
        $layout = $this->getView()->getLayout();

        if (in_array($layout, array('reset', 'password', 'register'))) {
            $result = true;
        } else {
            $result = $this->canEdit();
        }

        return $result;
    }
    
    public function canBrowse()
    {
        return false;
    }

    public function canEdit()
    {
        $result = false;

        $entity  = $this->getModel()->fetch();
        $user    = $this->getUser();

        if (($user->isAuthentic() && ($entity->id == $user->getId())) || $this->canDelete()) {
            $result = true;
        }

        return $result;
    }

    public function canAdd()
    {
        return true;
    }
}