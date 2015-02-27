<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Form Dispatcher Authenticator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherAuthenticatorForm extends DispatcherAuthenticatorBasic
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config A ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'action' => 'post'
        ));

        parent::_initialize($config);
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
        if ($context->getSubject()->getController()->getIdentifier()->name == 'session' && !$context->getUser()->isAuthentic())
        {
            $password   = $context->getRequest()->getData()->get('password', 'string');
            $email      = $context->getRequest()->getData()->get('email', 'email');

            //Ensure we have an email set
            if(!$email) throw new Library\ControllerExceptionRequestNotAuthenticated('Email is required');

            //Set the auth request headers
            $context->getRequest()->getHeaders()->set('php-auth-user', $email);
            $context->getRequest()->getHeaders()->set('php-auth-pw', $password);

            //Authenticate the user
            parent::authenticateRequest($context, $email, $password);

            //If the user wasn't authenticated, do not create session
            if(!$context->getUser()->isAuthentic()) return;

            //Start the session (if not started already)
            $context->getUser()->getSession()->start();
        }
    }
}