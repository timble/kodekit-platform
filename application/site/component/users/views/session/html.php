<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

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
    public function render()
    {
        $title = JText::_('Login');

        $this->getService('application')->getPathway()->addItem($title);
        JFactory::getDocument()->setTitle($title);
        
        $this->user       = $this->getService('user');;
        $this->parameters = $this->getParameters();

        return parent::render();
    }
    
    public function getParameters()
    {
        $active = $this->getService('application.pages')->getActive();
        $parameters = new JParameter($active->params);

        if(!$parameters->get('page_title')) {
            $parameters->set('page_title', JText::_('Login'));
        }

        $parameters->def('description_login_text', JText::_('LOGIN_DESCRIPTION'));
        $parameters->def('registration', $this->getService('application.components')->users->params->get('allowUserRegistration'));

        return $parameters;
    }
}