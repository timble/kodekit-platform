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
class DispatcherAuthenticatorForm extends Library\DispatcherAuthenticatorAbstract
{
    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config Configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('before.post', 'authenticateRequest');

    }

    /**
     * Authenticate using email and password credentials
     *
     * @param Library\DispatcherContextInterface $context A dispatcher context object
     * @return  boolean Returns FALSE if the check failed. Otherwise TRUE.
     */
    public function authenticateRequest(Library\DispatcherContextInterface $context)
    {
        if ($context->subject->getController()->getIdentifier()->name == 'session' && !$context->user->isAuthentic())
        {
            $password = $context->request->data->get('password', 'string');
            $email    = $context->request->data->get('email', 'email');

            $user = $this->getObject('com:users.model.users')->email($email)->fetch();

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

                //Start the session (if not started already)
                $context->user->getSession()->start();

                //Set user data in context
                $data  = $this->getObject('user.provider')->load($user->id)->toArray();
                $data['authentic'] = true;

                $context->user->setData($data);
            }
            else throw new Library\ControllerExceptionRequestNotAuthenticated('Wrong email');
        }
    }
}