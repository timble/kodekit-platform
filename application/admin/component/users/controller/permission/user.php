<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Platform\Users;

use Nooku\Library;
use Nooku\Platform\Application;

/**
 * User Controller Permission
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
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