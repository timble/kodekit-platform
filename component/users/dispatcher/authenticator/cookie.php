<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Token Dispatcher Authenticator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherAuthenticatorCookie extends Library\DispatcherAuthenticatorAbstract
{
    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config Configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('before.dispatch', 'authenticateRequest');

    }

    /**
     * Authenticate using the cookie session id
     *
     * If a session cookie is found and the session session is not active it will be auto-started.
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     * @return  boolean Returns FALSE if the check failed. Otherwise TRUE.
     */
    public function authenticateRequest(Library\DispatcherContextInterface $context)
    {
        $session = $context->getUser()->getSession();
        $request = $context->getRequest();

        //Auto-start the session if a cookie is found
        if(!$session->isActive())
        {
            //Set Session Name
            $session->setName(md5($request->getBasePath()));

            //Set Session Options
            $session->setOptions(array(
                'cookie_path'   => (string) $request->getBaseUrl()->getPath() ?: '/',
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