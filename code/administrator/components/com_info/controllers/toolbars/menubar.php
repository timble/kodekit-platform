<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Controller Menubar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */
class ComInfoControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('System Information', array(
        	'href'   => JRoute::_('index.php?option=com_info&view=system'),
        	'active' => ($name == 'system')
        ));
        
        $this->addCommand('Configuration File' , array(
        	'href' => JRoute::_('index.php?option=com_info&view=configuration'), 
            'active' => ($name == 'configuration')
        ));
        
        $this->addCommand('Directory Permissions', array(
        	'href' => JRoute::_('index.php?option=com_info&view=directories'),
            'active' => ($name == 'directory')
        ));
        
        $this->addCommand('PHP Information', array(
        	'href' => JRoute::_('index.php?option=com_info&view=phpinformation'),
            'active' => ($name == 'phpinformation')
        ));
        
        $this->addCommand('PHP Settings', array(
        	'href' => JRoute::_('index.php?option=com_info&view=phpsettings'),
            'active' => ($name == 'phpsetting')
        ));
          
        return parent::getCommands();
    }
}