<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Form Dispatcher Authenticator
 *
 * If you are running PHP as CGI. Apache does not pass HTTP Basic user/pass to PHP by default.
 * To fix this add these lines to your .htaccess file:
 *
 * RewriteCond %{HTTP:Authorization} ^(.+)$
 * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 */
class DispatcherAuthenticatorBasic extends DispatcherAuthenticatorAbstract
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    /**
     * Authenticate using email and password credentials
     *
     * @param DispatcherContextInterface $context A dispatcher context object
     * @return  boolean Returns TRUE if the authentication explicitly succeeded.
     */
    public function authenticateRequest(DispatcherContextInterface $context)
    {
        $request = $context->request;

        if($request->headers->has('Authorization'))
        {
            $authorization = $request->headers->get('Authorization');

            if(stripos($authorization, 'basic') === 0)
            {
                $exploded = explode(':', base64_decode(substr($authorization , 6)));
                if (count($exploded) == 2) {
                    list($username, $password) = $exploded;
                }

                if($username)
                {
                    $user = $this->getObject('user.provider')->getUser($username);

                    if($user->getId())
                    {
                        //Check user password
                        if (!$user->verifyPassword($password)) {
                            throw new ControllerExceptionRequestNotAuthenticated('Wrong password');
                        }

                        //Check user enabled
                        if (!$user->isEnabled()) {
                            throw new ControllerExceptionRequestNotAuthenticated('Account disabled');
                        }

                        //Login the user
                        $this->loginUser($user->getId());

                        return true;
                    }
                    else throw new ControllerExceptionRequestNotAuthenticated('Wrong username');
                }
                else throw new ControllerExceptionRequestNotAuthenticated('Invalid username');
            }
        }
    }
}