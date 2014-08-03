<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library, Nooku\Component\Users;

/**
 * Resettable Controller Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
 */
class UsersControllerBehaviorResettable extends Users\ControllerBehaviorResettable
{
    protected function _beforeRead(Library\ControllerContextInterface $context)
    {
        $result = true;

        // Push the token to the view.
        if ($token = $context->request->query->get('token', $this->_filter)) {
            $user = $this->getModel()->fetch();

            // Only passwords from enabled users can be reset.
            if (!$user->enabled) {
                $url = $this->getObject('application.pages')->getHome()->getLink();
                $this->getObject('application')->getRouter()->build($url);
                $context->response->setRedirect($url,
                    \JText::_('The user account you are trying to reset the password for is not enabled', 'error'));

                if ($user->activation) {
                    $context->response->addMessage(\JText::_('Please activate your account before resetting your password'),
                        'notice');
                }

                $result = false;
            } else $this->getView()->token = $token;
        }

        return $result;
    }

    protected function _beforeReset(Library\ControllerContextInterface $context)
    {
        $result = true;

        if (!parent::_beforeReset($context)) {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, \JText::_('Invalid request'), 'error');
            $result = false;
        }

        return $result;
    }

    protected function _beforeToken(Library\ControllerContextInterface $context)
    {
        $result = true;

        if (!parent::_beforeToken($context)) {
            $url = $context->request->getReferrer();
            $context->response->setRedirect($url, \JText::_('Invalid request'), 'error');
            $result = false;
        }

        return $result;
    }

    protected function _afterToken(Library\ControllerContextInterface $context)
    {
        if ($context->result) {
            $page = $this->getObject('application.pages')->find(array(
                'component' => 'users',
                'published' => 1,
                'access'    => 0,
                'link'      => array(array('view' => 'user'))));

            if ($page) {
                $token  = $context->token;
                $entity = $context->entity;

                $url                  = $page->getLink();
                $url->query['layout'] = 'password';
                $url->query['token']  = $token;
                $url->query['uuid']   = $entity->uuid;

                // TODO: This URL needs to be routed using the site app router.
                $this->getObject('application')->getRouter()->build($url);

                $url = $context->request->getUrl()
                        ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

                $site_name = \JFactory::getConfig()->getValue('sitename');

                $subject = \JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $site_name);
                // TODO Fix when language package is re-factored.
                //$message    = \JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $site_name, $url);
                $message = $url;

                if ($entity->notify(array('subject' => $subject, 'message' => $message))) {
                    $message = array(
                        'text' => \JText::_('A confirmation E-mail for resetting your password has been sent to the address you have provided'),
                        'type' => 'success');
                } else {
                    $message = array(
                        'text' => \JText::_('The confirmation E-mail for resetting your password could not be sent'),
                        'type' => 'notice');
                }

                $url = $this->getObject('application.pages')->getHome()->getLink();
                $this->getObject('application')->getRouter()->build($url);

                $context->response->setRedirect($url, $message['text'], $message['type']);
            } else {
                $context->response->addMessage('Unable to get a password reset URL', 'error');
            }
        }
    }

    protected function _afterReset(Library\ControllerContextInterface $context)
    {
        if ($context->result) {
            $message = array('text' => \JText::_('Your password has been reset'), 'type' => 'success');
            $url     = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

        } else {
            $message = array('text' => $context->error, 'type' => 'error');
            $url     = $context->request->getReferrer();
        }

        $context->response->setRedirect($url, $message['text'], $message['type']);
    }
}