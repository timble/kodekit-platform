<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;

/**
 * Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Users
 */
class Dispatcher extends Library\Dispatcher
{
    public function canDispatch()
    {
        return true;
    }

    protected function _actionDispatch(Library\DispatcherContext $context)
	{
        if($context->user->isAuthentic())
        {
            //Redirect if user is already logged in
            if($context->request->query->get('view', 'alpha') == 'session')
            {
                $menu = $this->getObject('pages')->getDefault();
                //@TODO : Fix the redirect
                //$this->getObject('application')->redirect('?Itemid='.$menu->id, 'You are already logged in!');
            }
        }

        if(!$context->user->isAuthentic())
        {
            //Redirect if user is already logged in
            if($context->request->query->get('view', 'alpha') == 'session')
            {
                $menu = $this->getObject('pages')->getDefault();
                //@TODO : Fix the redirect
                //$this->getObject('application')->redirect('?Itemid='.$menu->id, 'You are already logged out!');
            }
        }

        return parent::_actionDispatch($context);
	}
}