<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Table Controller Toolbar
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class ControllerToolbarTable extends Library\ControllerToolbarModel
{
    public function onAfterControllerBrowse(Library\Event $event)
    {
        parent::onAfterControllerBrowse($event);
        
        $this->reset();
    }
}