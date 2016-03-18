<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Users;

use Nooku\Library;
use Nooku\Platform\Application;

/**
 * User Controller Permission
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Users
 */
class ControllerPermissionUser extends Application\ControllerPermissionAbstract
{
    public function canRead()
    {
        $layout = $this->getView() instanceof Library\ViewTemplate ? $this->getView()->getLayout() : null;

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