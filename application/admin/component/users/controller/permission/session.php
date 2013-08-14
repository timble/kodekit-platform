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
 * Session Controller Permission Class
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Users
 */
class SessionControllerPermissionSession extends ApplicationControllerPermissionAbstract
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

    public function canAdd()
    {
        return true;
    }

    public function canEdit()
    {
        return false;
    }

    public function canDelete()
    {
        //Allow logging out ourselves
        if($this->getModel()->getState()->id == $this->getUser()->getSession()->getId()) {
            return true;
        }

        // Only administrator can logout other users
        if($this->getUser()->getRole() > 24) {
            return true;
        }

        return false;
    }
}