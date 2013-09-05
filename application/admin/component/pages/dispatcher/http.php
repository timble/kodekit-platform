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
 * Http  Dispatcher
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Pages
 */
class PagesDispatcherHttp extends Library\DispatcherHttp
{
    protected function _actionDispatch(Library\CommandContext $context)
    {
        $view = $context->request->query->get('view', 'cmd', $this->_controller);

        if($view == 'pages' && !$context->request->query->has('menu'))
        {
            $page = $this->getObject('com:pages.database.table.pages')
                          ->select(array('home' => 1), Library\Database::FETCH_ROW);

            $url = clone($context->request->getUrl());
            $url->query['view'] = $view;
            $url->query['menu'] = $page->pages_menu_id;

            return $this->redirect($url);
        }

        if($view == 'modules' && !$context->request->query->has('application'))
        {
            $url = clone($context->request->getUrl());
            $url->query['application']  = 'site';

            return $this->redirect($url);
        }

        if($view == 'menus' && !$context->request->query->has('application'))
        {
            $url = clone($context->request->getUrl());
            $url->query['application']  = 'site';

            return $this->redirect($url);
        }

        return parent::_actionDispatch($context);
    }
}
