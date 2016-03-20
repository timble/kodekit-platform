<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
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
    protected function _beforeRead(Library\ControllerContextInterface $context)
    {
        $result = true;

        // Push the token to the view.
        if ($token = $context->request->query->get('token', $this->_filter))
        {
            $user = $this->getModel()->fetch();

            // Only passwords from enabled users can be reset.
            if (!$user->enabled)
            {
                $url = $this->getObject('pages')->getDefault()->getLink();
                $this->getObject('application')->getRouter()->build($url);

                $translator = $this->getObject('translator');

                $message = $translator('The user account you are trying to reset the password for is not enabled');
                $context->response->setRedirect($url, $message, 'error');

                if ($user->activation)
                {
                    $message = $translator('Please activate your account before resetting your password');
                    $context->response->addMessage($message, 'notice');
                }

                $result = false;
            }
            else $this->getView()->token = $token;
        }

        return $result;
    }

    protected function _beforeReset(Library\ControllerContextInterface $context)
    {
        $result = true;

        if (!parent::_beforeReset($context))
        {
            $url = $this->getObject('pages')->getDefault()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, $this->getObject('translator')->translate('Invalid request'), 'error');
            $result = false;
        }

        return $result;
    }

    protected function _beforeToken(Library\ControllerContextInterface $context)
    {
        $result = true;

        if (!parent::_beforeToken($context))
        {
            $url = $context->request->getReferrer();
            $context->response->setRedirect($url, $this->getObject('translator')->translate('Invalid request'), 'error');
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
                'published' => 1,
                'access'    => 0,
                'link'      => array(array('view' => 'user'))));

            $translator = $this->getObject('translator');

            if ($page)
            {
                $token  = $context->token;
                $entity = $context->entity;

                $url                  = $page->getLink();
                $url->query['layout'] = 'password';
                $url->query['token']  = $token;
                $url->query['uuid']   = $entity->uuid;

                $this->getObject('application')->getRouter()->build($url);

                $url = $context->request->getUrl()
                        ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

                $subject = $translator('Reset your password');
                $message = $translator('Password reset instructions E-mail',
                    array('name' => $entity->name, 'url' => $url));

                if ($entity->isNotifable() && $entity->notify(array('subject' => $subject, 'message' => $message)))
                {
                    $message = array(
                        'text' => $translator('A confirmation E-mail for resetting your password has been sent to the address you have provided'),
                        'type' => 'success');
                }
                else
                {
                    $message = array(
                        'text' => $translator('The confirmation E-mail for resetting your password could not be sent'),
                        'type' => 'notice');
                }

                $url = $this->getObject('pages')->getDefault()->getLink();
                $this->getObject('application')->getRouter()->build($url);

                $context->response->setRedirect($url, $message['text'], $message['type']);
            }
            else $context->response->addMessage($translator('Unable to get a password reset URL'), 'error');
        }
    }

    protected function _afterReset(Library\ControllerContextInterface $context)
    {
        if ($context->result)
        {
            $message = array('text' => $this->getObject('translator')->translate('Your password has been reset'), 'type' => 'success');
            $url     = $this->getObject('pages')->getDefault()->getLink();
            $this->getObject('application')->getRouter()->build($url);

        }
        else
        {
            $message = array('text' => $context->error, 'type' => 'error');
            $url     = $context->request->getReferrer();
        }

        $context->response->setRedirect($url, $message['text'], $message['type']);
    }
}
