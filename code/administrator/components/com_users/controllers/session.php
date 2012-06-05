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

        $this->registerCallback('before.add', array($this, 'validate'));

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
        $options['group'] = 'Public Backend';

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
        $session = $this->getService('koowa:dispatcher.session.default')->start();

        $session->user = $user;
        $session->site = JFactory::getApplication()->getSite();

        //Prepare the data
        $context->data->id      = $session->getId();
        $context->data->name    = $user->get('name');
        $context->data->guest   = $user->get('guest');
        $context->data->user_id = intval($user->get('id'));
        $context->data->data    = '';
        $context->data->time    = time();
        $context->data->client_id = 1;

        $data = parent::_actionAdd($context);

        return $data;
    }

    protected function _actionDelete(KCommandContext $context)
    {
        //Force logout from site and administrator
        $this->client = array(0, 1);

        //Remove the session from the session store
        $data = parent::_actionDelete($context);

        if(!$context->hasError())
        {
           foreach($data as $session)
           {
               // Destroy the php session for this user if we are logging out ourselves
               if(JFactory::getUser()->get('id') == $session->user_id) {
                   $this->getService('koowa:dispatcher.session.default')->destroy();
               }
           }
        }

        return $data;
    }
}