<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Languages Toolbar Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 */
class ComLanguagesControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $this->addCommand('Modules', array(
        	'href' => JRoute::_('index.php?option=com_modules&view=modules'), 
        ));
        
        $this->addCommand('Plugins', array(
        	'href' => JRoute::_('index.php?option=com_plugins&view=plugins'),
        ));
        
        $this->addCommand('Templates', array(
        	'href' => JRoute::_('index.php?option=com_templates&view=templates'),
        ));
        
        $this->addCommand('Languages', array(
        	'href' => JRoute::_('index.php?option=com_languages&view=languages'),
        	'active' => true  
        ));
         
        return parent::getCommands();
    }
}