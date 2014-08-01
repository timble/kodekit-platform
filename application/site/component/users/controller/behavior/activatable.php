<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library, Nooku\Component\Users;

/**
 * Activatable Controller Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
 */
class UsersControllerBehaviorActivatable extends Users\ControllerBehaviorActivatable
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'enable' => '1'
        ));

        parent::_initialize($config);
    }

    protected function _beforeRender(Library\ControllerContextInterface $context)
    {
        $entity = $this->getModel()->fetch();

        if (($activation = $context->request->query->get('activation', $this->_filter)))
        {
            if (!$entity->activation)
            {
                $url = $this->getObject('application.pages')->getHome()->getLink();
                $url = $this->getObject('lib:dispatcher.router.route', array('url' => $url));

                $context->response->setRedirect($url, 'Invalid request', 'error');
            }
            else $this->activate(array('activation' => $activation));

            return false;
        }
    }

    protected function _beforeActivate(Library\ControllerContextInterface $context)
    {
        $result = true;

        if (!parent::_beforeActivate($context))
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, 'Wrong activation token', 'error');
            $result = false;
        }

        return $result;
    }

    protected function _afterAdd(Library\ControllerContextInterface $context)
    {
        $user = $context->result;

        if ($user instanceof Users\DatabaseRowUser && $user->getStatus() == $user::STATUS_CREATED && $user->activation)
        {
            if (($url = $this->_getActivationUrl()))
            {
                $url = $context->request->getUrl()
                        ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

                // TODO Uncomment and fix after Langauge support is re-factored.
                //$subject = JText::_('User Account Activation');
                //$message = sprintf(JText::_('SEND_MSG_ACTIVATE'), $user->name,
                //    $this->getObject('application')->getCfg('sitename'), $url, $site_url);
                $subject = 'User Account Activation';
                $message = $url;

                if ($user->notify(array('subject' => $subject, 'message' => $message))) {
                    $context->response->addMessage('An E-mail for activating your account has been sent to the address you have provided.');
                } else {
                    $context->reponse->addMessage('Failed to send activation E-mail', 'error');
                }
            }
            else $context->reponse->addMessage('Unable to get an activation URL', 'error');
        }
    }

    protected function _getActivationUrl()
    {
        $url = null;

        $user = $this->getModel()->fetch();
        $page = $this->getObject('application.pages')->find(array(
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

    protected function _afterActivate(Library\ControllerContextInterface $context)
    {
        $page = $this->getObject('application.pages')->find(array(
            'component' => 'users',
            'published' => 1,
            'access'    => 0,
            'link'      => array(array('view' => 'session'))));

        if ($page) {
            $url = $page->getLink();
        } else {
            $url = $this->getObject('application.pages')->getHome()->getLink();
        }

        $this->getObject('application')->getRouter()->build($url);

        if ($context->result === true) {
            $context->response->addMessage('Activation successfully completed');
        } else {
            $context->response->addMessage('Activation failed', 'error');
        }

        $context->response->setRedirect($url);
    }
}