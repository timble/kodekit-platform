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
 * Session Controller Permission
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Users
 */
class ControllerPermissionSession extends Application\ControllerPermissionAbstract
{
    public function canRender()
    {
        return $this->canRead();
    }

    public function canRead()
    {
        if(!$this->getUser()->isAuthentic()) {
            return true;
        }

        return false;
    }

    public function canBrowse()
    {
        return false;
    }

    public function canEdit()
    {
        return false;
    }

    public function canDelete()
    {
        // Allow logging out ourselves
        if($this->getModel()->getState()->id == $this->getUser()->getSession()->getId()) {
            return true;
        }

        // Only administrator can logout other users
        if($this->getUser()->hasRole('administrator')) {
            return true;
        }

        return false;
    }

    public function canAdd()
    {
        return $this->getUser()->isAuthentic();
    }
}