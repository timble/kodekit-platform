<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-activities for the canonical source repository
 */

namespace Kodekit\Component\Activities;

use Kodekit\Library;

/**
 * Executable Controller Behavior.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
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
