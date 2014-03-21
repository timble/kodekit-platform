<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Token Dispatcher Authenticator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherAuthenticatorCookie extends Library\DispatcherAuthenticatorAbstract
{
    /**
     * Authenticate using the cookie session id
     *
     * If a session cookie is found and the session session is not active it will be auto-started.
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     * @return  boolean Returns FALSE if the check failed. Otherwise TRUE.
     */
    protected function _beforeDispatch(Library\DispatcherContextInterface $context)
    {
        $session = $context->getUser()->getSession();
        $request = $context->getRequest();

        //Auto-start the session if a cookie is found
        if(!$session->isActive())
        {
            //Set Session Name
            $session->setName(md5($request->getBasePath()));

            $base_path = (string) $request->getBaseUrl()->getPath();

            if (empty($base_path))
            {
                $base_path = '/';
            }

            //Set Session Options
            $session->setOptions(array(
                'cookie_path'   => $base_path,
                'cookie_domain' => (string) $request->getBaseUrl()->getHost()
            ));

            if ($request->getCookies()->has($session->getName()))
            {
                $session->start();

                $messages = $context->user->getSession()->getContainer('message')->all();
                $context->response->setMessages($messages);
            }
        }
    }
}