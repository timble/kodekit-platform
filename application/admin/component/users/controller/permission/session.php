<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Session Controller Permission Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class UsersControllerPermissionSession extends ApplicationControllerPermissionDefault
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