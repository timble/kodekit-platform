<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Modules Menubar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */
class ComModulesControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('Modules', array(
        	'href' => JRoute::_('index.php?option=com_modules&view=modules'), 
            'active' => ($name == 'module')
        ));
        
        $this->addCommand('Plugins', array(
        	'href' => JRoute::_('index.php?option=com_plugins&view=plugins'),
        	'active' => ($name == 'plugin')
        ));
        
        $this->addCommand('Templates', array(
        	'href' => JRoute::_('index.php?option=com_templates&view=templates'),
            'active' => ($name == 'template')
        ));
        
        $this->addCommand('Languages', array(
        	'href' => JRoute::_('index.php?option=com_languages&view=languages'),
            'active' => ($name == 'language')
        ));
         
        return parent::getCommands();
    }
}