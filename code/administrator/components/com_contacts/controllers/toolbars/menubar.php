<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Contacts Menubar Class
 *
 * @author      Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts   
 */
class ComContactsControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('Contacts', array(
        	'href'   => JRoute::_('index.php?option=com_contacts&view=contacts'),
        	'active' => ($name == 'contact')
        ));
        
        $this->addCommand('Categories', array(
        	'href' => JRoute::_('index.php?option=com_contacts&view=categories'),
            'active' => ($name == 'category')
        ));
         
        return parent::getCommands();
    }
}