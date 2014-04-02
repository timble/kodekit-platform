<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * Resettable Controller Behavior
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Users
 */
class UsersControllerBehaviorResettable extends Users\ControllerBehaviorResettable
{
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
            $page = $this->getObject('application.pages')->find(array(
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

                $site = $this->getObject('application')->getCfg('sitename');

                $subject = $this->getObject('translator')->translate('Reset your password');
                $message = $translator->translate('Password reset instructions E-mail',
                    array('name' => $row->name, 'site' => $site, 'url' => $url));

                if (!$entity->notify(array('subject' => $subject, 'message' => $message)))
                {
                    $context->getResponse()->addMessage($translator->translate('Unable to send password reset E-mail'), 'notice');
                }
            }
            else
            {
                $context->response->addMessage($translator->translate('Unable to get a password reset URL'), 'error');
            }
        }
    }
}