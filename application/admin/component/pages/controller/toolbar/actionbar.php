<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * Toolbar Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class PagesControllerToolbarActionbar extends Users\ControllerToolbarSession
{
    public function getCommands()
    {
        $this->addCommand('profile');
        $this->addCommand('logout');

        return parent::getCommands();
    }
}