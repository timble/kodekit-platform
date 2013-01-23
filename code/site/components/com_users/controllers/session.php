<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Session Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerSession extends ComDefaultControllerModel
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Only authenticate POST requests
        $this->registerCallback('before.add' , array($this, 'authenticate'));

        //Authorize the user before adding
        $this->registerCallback('before.add' , array($this, 'authorize'));
        $this->registerCallback('after.add'  , array($this, 'redirect'));
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com://admin/activities.controller.behavior.loggable' => array('title_column' => 'name')
            )
        ));

        parent::_initialize($config);
    }

    public function authenticate(KCommandContext $context)
    {
        $email = $context->request->data->get('email', 'email');

        if(isset($email))
        {
            $user = $this->getService('com://admin/users.model.users')->email($email)->getRow();

            //Authenticate the user
            if($user->id)
            {
                $password = $user->getPassword();

                if(!$password->verify($context->request->data->get('password', 'string'))) {
                    throw new KControllerExceptionUnauthorized('Wrong password');
                }
            }

            //Start the session (if not started already)
            $context->user->session->start();

            //Set user data in context
            $data = array(
                'id'         => $user->id,
                'email'      => $user->email,
                'name'       => $user->name,
                'role'       => $user->role_id,
                'groups'     => $user->getGroups(),
                'password'   => $user->getPassword()->password,
                'salt'       => $user->getPassword()->salt,
                'authentic'  => true,
                'enabled'    => $user->enabled,
                'attributes' => $user->params->toArray(),
                'session'    => true,
            );

            $context->user->fromArray($data);
        }
        else throw new KControllerExceptionUnauthorized('Wrong email');

        return true;
    }

    public function authorize(KCommandContext $context)
    {
        //If the user is blocked, redirect with an error
        if (!$context->user->isEnabled()) {
            throw new KControllerExceptionForbidden('Account disabled');
        }

        return true;
    }

    public function redirect(KCommandContext $context)
    {
        if ($context->result !== false)
        {
            $user = $context->user;
            if ($user->getPassword()->expired())
            {
                $url = 'index.php?option=com_users&view=user&layout=password&id=' . $user->id;
                $url = $this->getService('koowa:http.url', array('url' => $url));

                $this->getService('application')->getRouter()->build($url);
                $context->response->setRedirect($url);
            }
            else $context->response->setRedirect(KRequest::referrer());
        }
    }

    protected function _actionAdd(KCommandContext $context)
    {
        $session = $context->user->session;

        //Insert the session into the database
        if(!$session->isActive()) {
            throw new KControllerExceptionActionFailed('Session could not be stored. No active session');
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
            'application' => $this->getIdentifier()->application,
        );

        $context->request->data->add($data);

        //Store the session
        $entity = parent::_actionAdd($context);

        //Set the session data
        $session->site = $this->getService('application')->getSite();

        //Redirect to caller
        $context->response->setRedirect($context->request->getReferrer());

        return $entity;
    }

    protected function _actionDelete(KCommandContext $context)
    {
        //Force logout from site only
        $context->request->query->application = array($this->getIdentifier()->application);

        //Remove the session from the session store
        $entity = parent::_actionDelete($context);

        if(!$context->hasError())
        {
            // Destroy the php session for this user if we are logging out ourselves
            if($context->user->getEmail() == $entity->email) {
                $context->user->session->destroy();
            }
        }

        return $entity;
    }
}