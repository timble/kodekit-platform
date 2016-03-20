<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Menu Controller Toolbar
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Pages
 */
class ControllerToolbarMenu extends Library\ControllerToolbarActionbar
{
    protected function _commandNew(Library\ControllerToolbarCommand $command)
    {
        $application = $this->getController()->getModel()->getState()->application;
        $command->href = 'component=pages&view=menu&application='.$application;
    }
}
