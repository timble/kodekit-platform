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
 * Csrf Dispatcher Authenticator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherAuthenticatorCsrf extends DispatcherAuthenticatorAbstract
{
    /**
     * The CSRF token
     *
     * @var string
     */
    private $__token;

    /**
     * Constructor
     *
     * @param ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('before.post', 'authenticateRequest');
        $this->addCommandCallback('after.get'  , 'signResponse');
    }

    /**
     * Return the CSRF request token
     *
     * @return  string  The CSRF token or NULL if no token could be found
     */
    public function getCsrfToken()
    {
        if(!isset($this->__token))
        {
            $token   = false;
            $request = $this->getObject('request');

            if($request->headers->has('X-XSRF-Token')) {
                $token = $request->headers->get('X-XSRF-Token');
            }

            if($request->headers->has('X-CSRF-Token')) {
                $token = $request->headers->get('X-CSRF-Token');
            }

            if($request->data->has('csrf_token')) {
                $token = $request->data->get('csrf_token', 'sha1');
            }

            $this->__token = $token;
        }

        return $this->__token;
    }

    /**
     * Verify the request to prevent CSRF exploits
     *
     * Method will always perform a referrer check and a cookie token check if the user is not authentic and
     * additionally a session token check if the user is authentic.
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @throws ControllerExceptionRequestInvalid      If the request referrer is not valid
     * @throws ControllerExceptionRequestForbidden    If the cookie token is not valid
     * @throws ControllerExceptionRequestNotAuthenticated If the session token is not valid
     * @return boolean Returns FALSE if the check failed. Otherwise TRUE.
     */
    public function authenticateRequest(DispatcherContextInterface $context)
    {
        $request = $context->request;
        $user    = $context->user;

        //Check referrer
        if(!$request->getReferrer()) {
            throw new ControllerExceptionRequestInvalid('Request Referrer Not Found');
        }

        //Check csrf token
        if(!$this->getCsrfToken()) {
            throw new ControllerExceptionRequestNotAuthenticated('Token Not Found');
        }

        //Check cookie token
        if($this->getCsrfToken() !== $request->cookies->get('csrf_token', 'sha1')) {
            throw new ControllerExceptionRequestNotAuthenticated('Invalid Cookie Token');
        }

        if($user->isAuthentic())
        {
            //Check session token
            if( $this->getCsrfToken() !== $user->getSession()->getToken()) {
                throw new ControllerExceptionRequestForbidden('Invalid Session Token');
            }
        }

        return true;
    }

    /**
     * Sign the response with a session token
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     */
    public function signResponse(DispatcherContextInterface $context)
    {
        if(!$context->response->isError())
        {
            $token = $context->user->getSession()->getToken();

            $context->response->headers->addCookie($this->getObject('lib:http.cookie', array(
                'name'   => 'csrf_token',
                'value'  => $token,
                'path'   => $context->request->getBaseUrl()->getPath(),
            )));

            $context->response->headers->set('X-CSRF-Token', $token);
        }
    }
}