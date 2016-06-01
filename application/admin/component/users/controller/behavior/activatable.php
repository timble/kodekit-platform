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
use Kodekit\Component\Users;

/**
 * Resettable Controller Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Users
 */
class ControllerBehaviorActivatable extends Users\ControllerBehaviorActivatable
{
    protected function _afterAdd(Library\ControllerContextModel $context)
    {
        $user = $context->result;

        if ($user instanceof Users\ModelEntityUser && $user->getStatus() == $user::STATUS_CREATED && $user->activation)
        {
            $translator = $this->getObject('translator');

            if (($url = $this->_getActivationUrl()))
            {
                $url = $context->request->getUrl()
                        ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

                $subject = $translator('User Account Activation');
                $message = $translator('User account activation E-mail', array('name' => $user->name, 'url' => $url));

                if ($user->isNotifable())
                {
                    if (!$user->notify(array('subject' => $subject, 'message' => $message))) {
                        $context->reponse->addMessage($translator('Failed to send activation E-mail'), 'error');
                    }
                }
            }
            else $context->response->addMessage($translator('Unable to get a user account activation URL'), 'error');
        }
    }

    protected function _getActivationUrl()
    {
        $url = null;

        $user = $this->getModel()->fetch();
        $page = $this->getObject('pages')->find(array(
            'component' => 'users',
            'access'    => 0,
            'link'      => array(array('view' => 'user'))));

        if ($page)
        {
            $url                      = $page->getLink();
            $url->query['activation'] = $user->activation;
            $url->query['uuid']       = $user->uuid;

            // TODO: This is a frontend URL and we can't get a frontend router. To be solved.
            $this->getObject('application')->getRouter()->build($url);
        }

        return $url;
    }
}
