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
 * If you are running PHP as CGI. Apache does not pass HTTP Basic user/pass to PHP by default.
 * To fix this add these lines to your .htaccess file:
 *
 * RewriteCond %{HTTP:Authorization} ^(.+)$
 * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherAuthenticatorBasic extends Library\DispatcherAuthenticatorAbstract
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_HIGHEST,
        ));

        parent::_initialize($config);
    }

    /**
     * Authenticate using email and password credentials
     *
     * @param Library\DispatcherContextInterface $context A dispatcher context object
     * @return  boolean Returns TRUE if the authentication explicitly succeeded.
     */
    public function authenticateRequest(Library\DispatcherContextInterface $context)
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
                    $user = $this->getObject('com:users.model.users')->email($username)->fetch();

                    if ($user->id)
                    {
                        //Check user password
                        if (!$user->getPassword()->verifyPassword($password)) {
                            throw new Library\ControllerExceptionRequestNotAuthenticated('Wrong password');
                        }

                        //Check user enabled
                        if (!$user->enabled) {
                            throw new Library\ControllerExceptionRequestNotAuthenticated('Account disabled');
                        }

                        //Login the user
                        $this->loginUser($user->id);

                        return true;
                    }
                    else throw new Library\ControllerExceptionRequestNotAuthenticated('Wrong username');
                }
                else throw new Library\ControllerExceptionRequestNotAuthenticated('Invalid username');
            }
        }
    }
}