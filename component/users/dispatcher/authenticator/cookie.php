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
class DispatcherAuthenticatorCookie extends Library\DispatcherAuthenticatorCsrf
{
    /**
     * Constructor.
     *
     * @param  Library\ObjectConfig $config Configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Setup the session cookie
        $session = $this->getObject('user')->getSession();

        //Set session cookie name
        $session->setName($this->getCookieName());

        //Set session cookie path and domain
        $session->setOptions(array(
            'cookie_path'   => (string) $this->getObject('request')->getBaseUrl()->getPath() ?: '/',
            'cookie_domain' => (string) $this->getObject('request')->getBaseUrl()->getHost()
        ));
    }

    /**
     * Return the cookie name
     *
     * @return string
     */
    public function getCookieName()
    {
        $request = $this->getObject('request');

        return md5($request->getBasePath());
    }

    /**
     * Authenticate using the cookie session id
     *
     * If a session cookie is found and the session session is not active it will be auto-started.
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     * @return  boolean Returns TRUE if the authentication explicitly succeeded.
     */
    public function authenticateRequest(Library\DispatcherContextInterface $context)
    {
        $session = $context->getUser()->getSession();
        $request = $context->getRequest();

        if(!$session->isActive())
        {
            if ($request->getCookies()->has($this->getCookieName()))
            {
                //Logging the user by auto-start the session
                $this->loginUser();

                //Perform CSRF authentication
                parent::authenticateRequest($context);

                return true;
            }
        }
    }

    /**
     * Log the user in
     *
     * @param mixed  $user A user key or name, an array of user data or a UserInterface object. Default NULL
     * @param array  $data Optional user data
     * @return bool
     */
    public function loginUser($user = null, $data = array())
    {
        if($this->_login_user)
        {
            $session  = $this->getObject('user')->getSession();
            $response = $this->getObject('response');

            //Start the session
            $session->start();

            //Set the messsages into the response
            $messages = $session->getContainer('message')->all();
            $response->setMessages($messages);

            if($user) {
                $result = parent::loginUser($user, $data);
            } else {
                $result = $this->getObject('user')->isAuthentic();
            }

            return $result;
        }

        return false;
    }
}