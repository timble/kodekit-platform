<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Session Html View
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Users
 */
class UsersViewSessionHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $title = JText::_('Login');
        $this->getObject('application')->getPathway()->addItem($title);

        return parent::_actionRender($context);
    }

    public function fetchData(Library\ViewContext $context)
    {
        $context->data->user       = $this->getObject('user');;
        $context->data->parameters = $this->getParameters();

        return parent::fetchData($context);
    }
    
    public function getParameters()
    {
        $active = $this->getObject('application.pages')->getActive();
        $parameters = new JParameter($active->params);

        $parameters->def('description_login_text', 'LOGIN_DESCRIPTION');
        $parameters->def('registration', $this->getObject('application.extensions')->users->params->get('allowUserRegistration'));

        return $parameters;
    }
}