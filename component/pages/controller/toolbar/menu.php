<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Menu Controller Toolbar
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class ControllerToolbarMenu extends Library\ControllerToolbarActionbar
{
    protected function _commandNew(Library\ControllerToolbarCommand $command)
    {
        $application = $this->getController()->getModel()->getState()->application;
        $command->href = 'component=pages&view=menu&application='.$application;
    }
}
