<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Login HTML View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersViewSessionHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $title = JText::_('Login');

        $this->getService('application')->getPathway()->addItem($title);
        JFactory::getDocument()->setTitle($title);
        
        $this->user       = JFactory::getUser();
        $this->parameters = $this->getParameters();

        return parent::display();
    }
    
    public function getParameters()
    {
        $active = $this->getService('application')->getPages()->getActive();
        $parameters = new JParameter($active->params);

        $parameters->def('show_page_title', 1);

        if(!$parameters->get('page_title')) {
            $parameters->set('page_title', JText::_('Login'));
        }

        $parameters->def('description_login', 1);
        $parameters->def('description_login_text', JText::_('LOGIN_DESCRIPTION'));
        $parameters->def('registration', JComponentHelper::getParams('com_users')->get('allowUserRegistration'));

        return $parameters;
    }
}