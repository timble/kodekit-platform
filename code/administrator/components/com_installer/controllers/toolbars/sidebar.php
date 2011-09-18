<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sidebar Menubar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerControllerToolbarSidebar extends KControllerToolbarDefault
{
    /**
     * Get the sidebar links
     *
     * @return  array
     */
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;

        $this->addCommand('Components', array(
            'href' => JRoute::_('index.php?option=com_installer&view=components'),
            'active' => ($name == 'component')
        ));

        $this->addCommand('Modules', array(
            'href' => JRoute::_('index.php?option=com_installer&view=modules'),
            'active' => ($name == 'module')
        ));

        $this->addCommand('Plugins', array(
            'href' => JRoute::_('index.php?option=com_installer&view=plugins'),
            'active' => ($name == 'plugin')
        ));

        $this->addCommand('Languages', array(
            'href' => JRoute::_('index.php?option=com_installer&view=languages'),
            'active' => ($name == 'language')
        ));

        $this->addCommand('Templates', array(
            'href' => JRoute::_('index.php?option=com_installer&view=templates'),
            'active' => ($name == 'template')
        ));
 
        return parent::getCommands();
    }
}