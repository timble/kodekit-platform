<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Pages;

use Kodekit\Library;

/**
 * Dispatcher
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Platform\Pages
 */
class Dispatcher extends Library\Dispatcher
{
    public function canDispatch()
    {
        return true;
    }

    protected function _actionDispatch(Library\DispatcherContext $context)
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
