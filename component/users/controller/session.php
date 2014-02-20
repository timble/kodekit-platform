<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Session Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class ControllerSession extends Library\ControllerModel
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('before.add' , '_authenticateUser');
    }

    protected function _authenticateUser(Library\ControllerContextInterface $context)
    {
        //Load the user
        $user = $this->getObject('com:users.model.users')
            ->email($context->request->data->get('email', 'email'))
            ->getRow();

        if($user->id)
        {
            //Check user password
            $password = $user->getPassword();

            if(!$password->verify($context->request->data->get('password', 'string'))) {
                throw new Library\ControllerExceptionRequestNotAuthenticated('Wrong password');
            }

            //Check user enabled
            if (!$user->enabled) {
                throw new Library\ControllerExceptionRequestNotAuthenticated('Account disabled');
            }

            //Start the session (if not started already)
            $context->user->getSession()->start();

            //Set user data in context
            $data = $this->getObject('user.provider')->load($user->id)->toArray();
            $data['authentic'] = true;

            $context->user->setData($data);
        }
        else throw new Library\ControllerExceptionRequestNotAuthenticated('Wrong email');

        return true;
    }

    protected function _actionDelete(Library\ControllerContextInterface $context)
    {
        //Remove the session from the session store
        $entity = parent::_actionDelete($context);

        if(!$context->response->isError())
        {
            // Destroy the php session for this user if we are logging out ourselves
            if($context->user->getEmail() == $entity->email) {
                $context->user->getSession()->destroy();
            }
        }

        return $entity;
    }
}