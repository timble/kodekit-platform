<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Session Controller
 *
 * @author   Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class UsersControllerSession extends Library\ControllerModel
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Only authenticate POST requests
        $this->registerCallback('before.add' , array($this, 'authenticate'));

        //Authorize the user before adding
        $this->registerCallback('before.add' , array($this, 'authorize'));

        //Lock the referrer to prevent it from being overridden for read requests
        if ($this->isDispatched() && !$this->getRequest()->isAjax())
        {
            if($this->isEditable()) {
                $this->registerCallback('after.delete' , array($this, 'lockReferrer'));
            }
        }
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name'),
            )
        ));

        parent::_initialize($config);
    }

    public function authenticate(Library\CommandContext $context)
    {
        //Load the user
        $user = $this->getObject('com:users.model.users')->email($context->request->data->get('email', 'email'))->getRow();

        if(!$user->isNew())
        {
            //Authenticate the user
            if($user->id)
            {
                $password = $user->getPassword();

                if(!$password->verify($context->request->data->get('password', 'string'))) {
                    throw new Library\ControllerExceptionUnauthorized('Wrong password');
                }
            }
            else throw new Library\ControllerExceptionUnauthorized('Wrong email');

            //Start the session (if not started already)
            $context->user->session->start();

            //Set user data in context
            $context->user->values($user->getSessionData(true));
        }
        else throw new Library\ControllerExceptionUnauthorized('Wrong email');

        return true;
    }

    public function authorize(Library\CommandContext $context)
    {
        //If the user is blocked, redirect with an error
        if (!$context->user->isEnabled()) {
            throw new Library\ControllerExceptionForbidden('Account disabled');
        }

        return true;
    }

    protected function _actionAdd(Library\CommandContext $context)
    {
        //Start the session (if not started already)
        $session = $context->user->session;

        //Insert the session into the database
        if(!$session->isActive()) {
            throw new Library\ControllerExceptionActionFailed('Session could not be stored. No active session');
        }

        //Fork the session to prevent session fixation issues
        $session->fork();

        //Prepare the data
        $data = array(
            'id'          => $session->getId(),
            'guest'       => !$context->user->isAuthentic(),
            'email'       => $context->user->getEmail(),
            'data'        => '',
            'time'        => time(),
            'application' => 'admin',
            'name'        => $context->user->getName()
        );

        $context->request->data->add($data);

        //Store the session
        $entity = parent::_actionAdd($context);

        //Set the session data
        $session->site = $this->getObject('application')->getSite();

        //Redirect to caller
        $context->response->setRedirect($context->request->getReferrer());

        return $entity;
    }

    protected function _actionDelete(Library\CommandContext $context)
    {
        //Force logout from site and administrator
        $context->request->query->application = array('site', 'admin');

        //Remove the session from the session store
        $entity = parent::_actionDelete($context);

        if(!$context->response->isError())
        {
            // Destroy the php session for this user if we are logging out ourselves
            if($context->user->getEmail() == $entity->email) {
                $context->user->session->destroy();
            }
        }

        //Redirect to caller
        $context->response->setRedirect($context->request->getReferrer());

        return $entity;
    }
}