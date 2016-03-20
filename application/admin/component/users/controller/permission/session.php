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
 * Session Controller Permission
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Users
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
        //Allow logging out ourselves
        if($this->getModel()->getState()->id == $this->getUser()->getSession()->getId()) {
            return true;
        }

        // Only administrator can logout other users
        if($this->getUser()->hasRole('administrator')) {
            return true;
        }

        return false;
    }
}