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
class ComUsersControllerSession extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Only authenticate POST requests
        $this->registerCallback('before.post' , array($this, 'authenticate'));

        //Authorize the user before adding
        $this->registerCallback('before.add' , array($this, 'authorize'));

        //Lock the referrer to prevent it from being overridden for read requests
        if ($this->isDispatched() && KRequest::type() == 'HTTP')
        {
            if($this->isEditable()) {
                $this->registerCallback('after.delete' , array($this, 'lockReferrer'));
            }
        }

        //Set the default redirect.
        $this->setRedirect(KRequest::referrer());
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com://admin/activities.controller.behavior.loggable' => array('title_column' => 'name')
            ),
        ));

        parent::_initialize($config);
    }

    protected function _actionGet(KCommandContext $context)
    {
        //Force the application template
        KRequest::set('get.tmpl', 'login');

        //Set the status
        $context->status = KHttpResponse::UNAUTHORIZED;

        return parent::_actionGet($context);
    }

    protected function _actionAdd(KCommandContext $context)
    {
        //Start the session (if not started already)
        $session = $this->getService('application.session')->start();

        //Insert the session into the database
        if($session->isActive())
        {
            //Prepare the data
            $context->data->id    = $session->getId();
            $context->data->name  = $context->user->name;
            $context->data->guest = $context->user->guest;
            $context->data->email = $context->user->email;
            $context->data->data  = '';
            $context->data->time  = time();
            $context->data->application = $this->getIdentifier()->application;
        }

        //Add the session to the session store
        $data = parent::_actionAdd($context);

        if(!$context->hasError())
        {
            //Set the session data
            $session->user = $context->user;
            $session->site = $this->getService('application')->getSite();
        }

        return $data;
    }

    protected function _actionDelete(KCommandContext $context)
    {
        //Force logout from site and administrator
        $this->application = array_keys($this->getIdentifier()->getApplications());

        //Remove the session from the session store
        $data = parent::_actionDelete($context);

        if(!$context->hasError())
        {
            // Destroy the php session for this user if we are logging out ourselves
            if(JFactory::getUser()->email == $data->email) {
                $this->getService('application.session')->destroy();
            }
        }

        return $data;
    }

    protected function _actionFork(KCommandContext $context)
    {
        //Fork the session to prevent session fixation issues
        $session = $this->getService('application.session')->fork();

        //Re-Load the user
        $user = $this->getService('com://admin/users.database.row.user')
                      ->set('email', $session->user->email)
                      ->load();

        //Store the user in the context
        $context->user = $user;

        //Re-authorize the user and add a session entity
        $result = $this->execute('add', $context);

        return $result;
    }

    protected function _actionAuthenticate(KCommandContext $context)
    {
        //Load the user
        $user = $this->getService('com://admin/users.database.row.user')
                     ->set('email', $context->data->email)
                     ->load();

        //Store the user in the context
        $context->user = $user;

        if($user->id)
        {
            $password = $user->getPassword();

            if(!$password->verify($context->data->password))
            {
                JError::raiseWarning('SOME_ERROR_CODE', JText::_('Wrong password!'));
                return false;
            }
        }
        else
        {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('Wrong email!'));
            return false;
        }

        return true;
    }

    protected function _actionAuthorize(KCommandContext $context)
    {
        //Make sure we have a valid user object
        if($context->user instanceof ComUsersDatabaseRowUser)
        {
            $options = array();
            $options['group'] = 'Public Backend';

            //If the user is blocked, redirect with an error
            if (!$context->user->enabled) {
                JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_NOLOGIN_BLOCKED'));
                return false;
            }

            //Check if the users group has access
            $acl   = JFactory::getACL();
            $group = $acl->getAroGroup($context->user->id);

            if(!$acl->is_group_child_of( $group->name, $options['group'])) {
                JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_NOLOGIN_ACCESS'));
                return false;
            }

            //Mark the user as logged in
            $context->user->guest = 0;
            $context->user->aid   = 1;

            // Fudge Authors, Editors, Publishers and Super Administrators into the special access group
            if ($acl->is_group_child_of($group->name, 'Registered')      ||
                $acl->is_group_child_of($group->name, 'Public Backend')) {
                $context->user->aid = 2;
            }

            //Set the group based on the ACL group name
            $context->user->group_name = $group->name;

            return true;
        }

        return false;
    }
}