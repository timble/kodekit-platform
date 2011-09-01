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
 * Login HTML View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersViewLoginHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $title = JText::_('Login');

        KFactory::get('joomla:application')->getPathway()->addItem($title);
        KFactory::get('joomla:document')->setTitle($title);
        
        $this->parameters = $this->getParameters();

        return parent::display();
    }
    
    public function getParameters()
    {
        $menu   = JSite::getMenu();
        $active = $menu->getActive();
        $parameters = $active ? $menu->getParams($active->id) : $parameters = $menu->getParams(null);

        $parameters->def('show_page_title', 1);

        if(!$parameters->get('page_title')) {
            $parameters->set('page_title', JText::_('Login'));
        }

        if(!$active) {
            $parameters->def('header_login', '');
        }

        $parameters->def('pageclass_sfx', '');
        $parameters->def('login', 'index.php');
        $parameters->def('description_login', 1);
        $parameters->def('description_login_text', JText::_('LOGIN_DESCRIPTION'));
        $parameters->def('image_login', 'key.jpg');
        $parameters->def('image_login_align', 'right');
        $parameters->def('registration', JComponentHelper::getParams('com_users')->get('allowUserRegistration'));

        return $parameters;
    }
}