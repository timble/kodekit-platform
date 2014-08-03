<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Session Html View
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
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

    protected function _fetchData(Library\ViewContext $context)
    {
        $context->data->user       = $this->getObject('user');;
        $context->data->parameters = $this->getParameters();

        parent::_fetchData($context);
    }
    
    public function getParameters()
    {
        $page       = $this->getObject('application.pages')->getActive();
        $parameters = $page->getParams('page');

        $parameters->def('description_login_text', 'LOGIN_DESCRIPTION');
        $parameters->def('registration', true);

        return $parameters;
    }
}