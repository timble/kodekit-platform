<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Pages;

use Kodekit\Library;
use Kodekit\Component\Users;

/**
 * Toolbar Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Pages
 */
class ControllerToolbarActionbar extends Users\ControllerToolbarSession
{
    public function getCommands()
    {
        $this->addCommand('profile');
        $this->addCommand('logout');

        return parent::getCommands();
    }
}