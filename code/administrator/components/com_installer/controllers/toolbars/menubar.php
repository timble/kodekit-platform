<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Installer Menubar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    /**
     * Get the menubar links
     * 
     * @return  array
     */
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('Install/Uninstall', array(
            'href' => JRoute::_('index.php?option=com_installer&view=components'),
            'active' => true
        ));

        $this->addCommand('Modules', array(
            'href' => JRoute::_('index.php?option=com_extensions&view=modules'),
        ));

        $this->addCommand('Plugins', array(
            'href' => JRoute::_('index.php?option=com_extensions&view=plugins'),
        ));
        
        $this->addCommand('Templates', array(
            'href' => JRoute::_('index.php?option=com_extensions&view=templates'),
        ));

        $this->addCommand('Languages', array(
            'href' => JRoute::_('index.php?option=com_extensions&view=languages'),
        ));
        
        return parent::getCommands();
    }
}