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
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Users
 */
class ControllerPermissionUser extends Application\ControllerPermissionAbstract
{
    public function canAdd()
    {
        // Only administrators can add users.
        return $this->getUser()->hasRole('administrator');
    }

    public function canDelete()
    {
        $result = false;

        $user     = $this->getUser();
        $entities = $this->getModel()->fetch();

        // Only administrators can delete users and they cannot delete themselves.
        if ($user->hasRole('administrator') && $entities->find(array('id', $user->getId()))) {
            $result = true;
        }

        return $result;
    }
}