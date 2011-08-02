<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Users Menubar Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    {
        $this->addCommand('Users', array(
        	'href'   => JRoute::_('index.php?option=com_users&view=users'),
        	'active' => true
        ));

        $this->addCommand('Groups' , array(
        	'href' => JRoute::_('index.php?option=com_groups&view=groups'), 
            'active' => false
        ));

        return parent::getCommands();
    }
}