<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Application Menubar
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationControllerToolbarMenubar extends KControllerToolbarAbstract
{
    public function onBeforeControllerRender(KEvent $event)
    {   
        $event->getTarget()->getView()->menubar = $this;
    }

    public function getCommand($name, $config = array())
    {
        $command = parent::getCommand($name, $config);

        if($this->getService('component')->getController()->getView()->getLayout() == 'form') {
            $command->disabled = true;
        }

        return $command;
    }


}

