<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
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
        $title = $this->getObject('translator')->translate('Login');
        $this->getObject('com:pages.pathway')->addItem($title);

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

        $parameters->def('registration', true);

        return $parameters;
    }
}