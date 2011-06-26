<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners Menubar Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('Banners', array(
        	'href'   => JRoute::_('index.php?option=com_banners&view=banners'),
        	'active' => ($name == 'banner')
        ));
        
        $this->addCommand('Categories', array(
        	'href' => JRoute::_('index.php?option=com_banners&view=categories'),
            'active' => ($name == 'category')
        ));
         
        return parent::getCommands();
    }
}