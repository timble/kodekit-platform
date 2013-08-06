<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Dispatcher Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
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
