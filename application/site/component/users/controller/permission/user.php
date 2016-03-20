<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;
use Kodekit\Platform\Application;

/**
 * User Controller Permission
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Platform\Users
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