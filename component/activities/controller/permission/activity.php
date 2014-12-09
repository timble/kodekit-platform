<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Executable Controller Behavior.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ControllerPermissionActivity extends Library\ControllerPermissionAbstract
{
    public function canAdd()
    {
        return !$this->isDispatched(); // Do not allow activities to be added if the controller is not dispatched.
    }

    public function canEdit()
    {
        return false; // Do not allow activities to be edited.
    }

    public function canPurge()
    {
       return !$this->isDispatched() || $this->canDelete();
    }
}
