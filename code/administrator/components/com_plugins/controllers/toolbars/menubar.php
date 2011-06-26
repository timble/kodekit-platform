<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Plugins Menubar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins    
 */
class ComPluginsControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $this->addCommand('Modules', array(
        	'href' => JRoute::_('index.php?option=com_modules&view=modules'), 
        ));
        
        $this->addCommand('Plugins', array(
        	'href'   => JRoute::_('index.php?option=com_plugins&view=plugins'),
            'active' => true 
        ));
        
        $this->addCommand('Templates', array(
        	'href' => JRoute::_('index.php?option=com_templates&view=templates'),
        ));
        
        $this->addCommand('Languages', array(
        	'href' => JRoute::_('index.php?option=com_languages&view=languages'),
        ));
         
        return parent::getCommands();
    }
}