<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Dispatcher
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Pages
 */
class PagesDispatcher extends Library\Dispatcher
{
    public function canDispatch()
    {
        return true;
    }

    protected function _actionDispatch(Library\DispatcherContextInterface $context)
    {
        $view = $context->request->query->get('view', 'cmd', $this->_controller);

        if($view == 'pages' && !$context->request->query->has('menu'))
        {
            $page = $this->getObject('pages')->getDefault();

            $url = clone($context->request->getUrl());
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
