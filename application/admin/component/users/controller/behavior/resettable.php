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
class ControllerBehaviorResettable extends Users\ControllerBehaviorResettable
{
    protected function _beforeToken(Library\ControllerContextInterface $context)
    {
        $result = true;

        if (!parent::_beforeToken($context))
        {
            $url        = $context->request->getReferrer();
            $translator = $this->getObject('translator');

            $context->response->setRedirect($url, $translator('Invalid request'), 'error');
            $result = false;
        }

        return $result;
    }

    protected function _afterToken(Library\ControllerContextInterface $context)
    {
        if ($context->result)
        {
            $page = $this->getObject('pages')->find(array(
                'component' => 'users',
                'access'    => 0,
                'link'      => array(array('view' => 'user'))));

            $translator = $this->getObject('translator');

            if ($page)
            {
                $token = $context->token;
                $entity   = $context->entity;

                $url                  = $page->getLink();
                $url->query['layout'] = 'password';
                $url->query['token']  = $token;
                $url->query['uuid']   = $entity->uuid;

                // TODO: This is a frontend URL and we can't get a frontend router. To be solved.
                $this->getObject('application')->getRouter()->build($url);

                $url = $context->request->getUrl()
                                        ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

                $subject = $translator('Reset your password');
                $message = $translator('Password reset instructions E-mail',
                    array('name' => $entity->name, 'url' => $url));

                if ($entity->isNotifable())
                {
                    if (!$entity->notify(array('subject' => $subject, 'message' => $message))) {
                        $context->getResponse()->addMessage($translator('Unable to send password reset E-mail'), 'notice');
                    }
                }
            }
            else $context->response->addMessage($translator('Unable to get a password reset URL'), 'error');
        }
    }
}