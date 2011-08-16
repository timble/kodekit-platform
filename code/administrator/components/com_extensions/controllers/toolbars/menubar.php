<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Menubar Toolbar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 */
class ComExtensionsControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    /**
     * Get the list of commands
     *
     * Prepending a special command not found in the manifest.xml
     *
     * @return  array
     */
    public function getCommands()
    {
        $option  = $this->getController()->getRequest()->option;
        //@TODO figure out why option=com_installer&view=components sets $request->option to NULL
        $active  = !$option || $option == 'com_installer';
        $view    = $active ? 'components' : KInflector::pluralize($this->getController()->getIdentifier()->name);

        $this->addCommand('Install/Uninstall', array(
            'href'   => JRoute::_('index.php?option=com_installer&view='.$view),
            'active' => $active
        ));

        $commands = parent::getCommands();

        //If the com_installer command is active, then following commands cannot be active
        if($commands['Install/Uninstall']->active)
        {
            foreach($commands as $key => $command)
            {
                if($key != 'Install/Uninstall') $command->active = false;
            }
        }

        return $commands;
    }
}