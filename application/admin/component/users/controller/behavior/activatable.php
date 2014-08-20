<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * Resettable Controller Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
 */
class UsersControllerBehaviorActivatable extends Users\ControllerBehaviorActivatable
{
    protected function _afterAdd(Library\ControllerContextInterface $context)
    {
        $user = $context->result;

        if ($user instanceof Users\ModelEntityUser && $user->getStatus() == $user::STATUS_CREATED && $user->activation)
        {
            $translator = $this->getObject('translator');

            if (($url = $this->_getActivationUrl()))
            {
                $url = $context->request->getUrl()
                        ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

                $site = $this->getObject('application')->getTitle();

                $subject = $translator('User Account Activation');
                $message = $translator('User account activation E-mail',
                    array('name' => $user->name, 'site' => $site, 'url' => $url));

                if (!$user->notify(array('subject' => $subject, 'message' => $message))) {
                    $context->reponse->addMessage($translator('Failed to send activation E-mail'), 'error');
                }
            }
            else $context->response->addMessage($translator('Unable to get a user account activation URL'), 'error');
        }
    }

    protected function _getActivationUrl()
    {
        $url = null;

        $user = $this->getModel()->fetch();
        $page = $this->getObject('application.pages')->find(array(
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
