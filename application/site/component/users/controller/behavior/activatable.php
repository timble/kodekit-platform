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
 * Activatable Controller Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Users
 */
class ControllerBehaviorActivatable extends Users\ControllerBehaviorActivatable
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'enable' => '1'
        ));

        parent::_initialize($config);
    }

    protected function _beforeRender(Library\ControllerContextModel $context)
    {
        $entity = $this->getModel()->fetch();

        if (($activation = $context->request->query->get('activation', $this->_filter)))
        {
            if (!$entity->activation)
            {
                $url = $this->getObject('pages')->getDefault()->getLink();
                $url = $this->getObject('lib:dispatcher.router.route', array('url' => $url));

                $context->response->setRedirect($url, 'Invalid request', 'error');
            }
            else $this->activate(array('activation' => $activation));

            return false;
        }
    }

    protected function _beforeActivate(Library\ControllerContextModel $context)
    {
        $result = true;

        if (!parent::_beforeActivate($context))
        {
            $url = $this->getObject('pages')->getDefault()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, 'Wrong activation token', 'error');
            $result = false;
        }

        return $result;
    }

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
                $message = $translator('User account activation E-mail',
                    array('name' => $user->name, 'url' => $url));

                if ($user->notify(array('subject' => $subject, 'message' => $message)))
                {
                    $context->response->addMessage($translator(
                        'An E-mail for activating your account has been sent to the address you have provided'
                    ));
                }
                else $context->reponse->addMessage($translator('Failed to send activation E-mail'), 'error');
            }
            else $context->reponse->addMessage($translator('Unable to get an activation URL'), 'error');
        }
    }

    protected function _getActivationUrl()
    {
        $url = null;

        $user = $this->getModel()->fetch();
        $page = $this->getObject('pages')->find(array(
            'component' => 'users',
            'access'    => 0,
            'published' => 1,
            'link'      => array(array('view' => 'user'))));

        if ($page)
        {
            $url                      = $page->getLink();
            $url->query['activation'] = $user->activation;
            $url->query['uuid']       = $user->uuid;

            // TODO: This URL needs to be routed using the site app router.
            $this->getObject('application')->getRouter()->build($url);
        }

        return $url;
    }

    protected function _afterActivate(Library\ControllerContextModel $context)
    {
        $page = $this->getObject('pages')->find(array(
            'component' => 'users',
            'published' => 1,
            'access'    => 0,
            'link'      => array(array('view' => 'session'))));

        if ($page) {
            $url = $page->getLink();
        } else {
            $url = $this->getObject('pages')->getDefault()->getLink();
        }

        $this->getObject('application')->getRouter()->build($url);

        $translator = $this->getObject('translator');

        if ($context->result === true) {
            $context->response->addMessage($translator('User account successfully activated'));
        } else {
            $context->response->addMessage($context->error, 'error');
        }

        $context->response->setRedirect($url);
    }
}
