<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Application Menubar
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationControllerToolbarMenubar extends Library\ControllerToolbarAbstract
{
    public function onBeforeControllerRender(Library\Event $event)
    {   
        $event->getTarget()->getView()->menubar = $this;
    }
}

