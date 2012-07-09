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
 * Login Controller Class
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

        $this->registerCallback('before.add', array($this, 'validate'));

        //Set the default redirect.
        $this->setRedirect(KRequest::referrer());
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

    protected function _actionGet(KCommandContext $context)
    {
        //Set the status
        $context->status = KHttpResponse::UNAUTHORIZED;

        return parent::_actionGet($context);
    }

    protected function _actionValidate(KCommandContext $context)
    {
        $user = $this->getService('com://admin/users.database.row.user');
        $user->username = $context->data->username;
        $user->load();

        if($user->id)
        {
            list($password, $salt) = explode(':', $user->password);

            $crypted = $this->getService('com://admin/users.helper.password')
                ->getCrypted($context->data->password, $salt);

            if($crypted == $password)
            {
                $context->data->append(array(
                    'email'     => $user->email,
                    'username'  => $user->username,
                    'name'      => $user->name
                ));
            }
            else
            {
                JError::raiseWarning('SOME_ERROR_CODE', JText::_('Wrong password!'));
                return false;
            }
        }
        else
        {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('Wrong username!'));
            return false;
        }

        return true;
    }

    protected function _actionAdd(KCommandContext $context)
    {
        $options = array();
        $options['group'] = 'USERS';

        //Fetch the user object
        $user = JFactory::getUser($context->data->username);

        //If the user is blocked, redirect with an error
        if ($user->get('block') == 1) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_NOLOGIN_BLOCKED'));
            return false;
        }

        //Check if the users group has access
        $acl   = JFactory::getACL();
        $group = $acl->getAroGroup($user->get('id'));

        if(!$acl->is_group_child_of( $group->name, $options['group'])) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_NOLOGIN_ACCESS'));
            return false;
        }

        //Mark the user as logged in
        $user->set('guest', 0);
        $user->set('aid'  , 1);

        // Fudge Authors, Editors, Publishers and Super Administrators into the special access group
        if ($acl->is_group_child_of($group->name, 'Registered')      ||
            $acl->is_group_child_of($group->name, 'Public Backend'))    {
            $user->set('aid', 2);
        }

        //Set the usertype based on the ACL group name
        $user->set('usertype', $group->name);

        //Start the session
        $session = $this->getService('session')->start();

        $session->user = $user;
        $session->site = JFactory::getApplication()->getSite();

        //Prepare the data
        $context->data->id      = $session->getId();
        $context->data->name    = $user->get('name');
        $context->data->guest   = $user->get('guest');
        $context->data->user_id = intval($user->get('id'));
        $context->data->data    = '';
        $context->data->time    = time();
        $context->data->client_id = 0;

        $data = parent::_actionAdd($context);

        return $data;
    }

    protected function _actionDelete(KCommandContext $context)
    {
        //Force logout from site only
        $this->client= array(0);

        //Remove the session from the session store
        $data = parent::_actionDelete($context);

        if(!$context->hasError())
        {
            // Destroy the php session for this user if we are logging out ourselves
            if(JFactory::getUser()->get('id') == $data->userid) {
                $this->getService('session')->destroy();
            }
        }

        return $data;
    }
}