<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Token Dispatcher Authenticator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherAuthenticatorToken extends DispatcherAuthenticatorAbstract
{
    /**
     * Check the request token to prevent CSRF exploits
     *
     * Method will always perform a referrer check and a token cookie token check if the user is not authentic and
     * additionally a session token check if the user is authentic. If any of the checks fail a forbidden exception
     * is thrown.
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     *
     * @throws ControllerExceptionRequestInvalid      If the request referrer is not valid
     * @throws ControllerExceptionRequestForbidden    If the cookie token is not valid
     * @throws ControllerExceptionRequestNotAuthenticated If the session token is not valid
     * @return  boolean Returns FALSE if the check failed. Otherwise TRUE.
     */
    protected function _beforePost(DispatcherContextInterface $context)
    {
        $request = $context->request;
        $user    = $context->user;

        //Check referrer
        if(!$request->getReferrer()) {
            throw new ControllerExceptionRequestInvalid('Invalid Request Referrer');
        }

        //Check cookie token
        if($request->getToken() !== $request->cookies->get('_token', 'sha1')) {
            throw new ControllerExceptionRequestNotAuthenticated('Invalid Cookie Token');
        }

        if($user->isAuthentic())
        {
            //Check session token
            if( $request->getToken() !== $user->getSession()->getToken()) {
                throw new ControllerExceptionRequestForbidden('Invalid Session Token');
            }
        }

        return true;
    }

    /**
     * Sign the response with a token
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _afterGet(DispatcherContextInterface $context)
    {
        if(!$context->response->isError())
        {
            $token = $context->user->getSession()->getToken();

            $context->response->headers->addCookie($this->getObject('lib:http.cookie', array(
                'name'   => '_token',
                'value'  => $token,
                'path'   => $context->request->getBaseUrl()->getPath()
            )));

            $context->response->headers->set('X-Token', $token);
        }
    }
}