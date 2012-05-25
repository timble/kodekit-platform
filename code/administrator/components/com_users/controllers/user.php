<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * User Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersControllerUser extends ComDefaultControllerDefault
{ 
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('after.add'   , array($this, 'notify'))
             ->registerCallback('before.login', array($this, 'authenticate'));
        
        //Lock the referrer to prevent it from being overridden for read requests
        if ($this->isDispatched() && KRequest::type() == 'HTTP') 
        {
		    if($this->isEditable()) {
		        $this->registerCallback('after.logout' , array($this, 'lockReferrer'));
		    }
        }

        //Set the default redirect.
        $this->setRedirect(KRequest::referrer());
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'behaviors' => array(
        		'com://admin/activities.controller.behavior.loggable' => array(
               		'title_column' => 'name',
               		'actions'      => array('after.login', 'after.logout')        
             )),
        ));
    
        parent::_initialize($config);
    }
    
    protected function _actionEdit(KCommandContext $context)
    {
        $data = parent::_actionEdit($context);
        
        if ($context->status == KHttpResponse::RESET_CONTENT) {
            JFactory::getUser($data->id)->bind($data->toArray());
        }
        
        return $data;
    }

    protected function _actionDelete(KCommandContext $context)
    {
        $data = parent::_actionDelete($context);
        
        $this->getService('com://admin/users.model.sessions')
            ->username($data->username)
            ->getList()
            ->delete();

        return $data;
    }

    protected function _actionAuthenticate(KCommandContext $context)
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

    protected function _actionLogin(KCommandContext $context)
    {
        $result = false;

        $credentials = array(
            'username' => KRequest::get('post.username', 'string'),
            'password' => KRequest::get('post.password', 'raw')
        );

        $options = array();
        $options['group'] = 'Public Backend';
        $options['site']  = JFactory::getApplication()->getSite();

        $session = JFactory::getSession();
        $acl     = JFactory::getACL();

        //Fet the user object
        $user = JFactory::getUser($credentials['username']);

        //Fork the session to prevent session fixation issues
        $session->fork();
        JFactory::getApplication()->_loadSession($session->getId());

        //If the user is blocked, redirect with an error
        if ($user->get('block') == 1) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_NOLOGIN_BLOCKED'));
            return false;
        }

        //Check if the users group has access
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

        // Register the needed session variables
        $session->set('user', $user);
        $session->set('site', $options['site']);

        // Get the session object
        $table =  JTable::getInstance('session');
        $table->load( $session->getId() );

        $table->guest 	  = $user->get('guest');
        $table->username  = $user->get('username');
        $table->userid 	  = intval($user->get('id'));
        $table->usertype  = $user->get('usertype');
        $table->gid 	  = intval($user->get('gid'));

        $table->update();

        // Hit the user last visit field
        $row = KService::get('com://admin/users.database.row.user')
                ->setData(array('id' => $user->get('id')))
                ->load();

        $row->last_visited_on = gmdate('Y-m-d H:i:s');
        $row->save();

        $result = $this->getModel()->id($user->get('id'))->getItem()->setStatus('logged in');
        return $result;
    }

    protected function _actionLogout(KCommandContext $context)
    {
        $rowset = clone $this->getModel()->getList();
        
	    if(count($rowset)) 
	    {
	        foreach($rowset as $user)
	        {
                //Force logout from site and administrator
                $clients = array(0, 1);

                // Force logout all users with that userid
                JTable::getInstance('session')->destroy($user->id, $clients);

                // Destroy the php session for this user if we are logging out ourselves
                if(JFactory::getUser()->get('id') == $user->id) {
                    JFactory::getSession()->destroy();
                }

                $user->setStatus('logged out');
	        }
		} 
		
		$this->_redirect = KRequest::referrer();
        return $rowset;
    }

    public function notify(KCommandContext $context)
    {
        if($context->result->status == KDatabase::STATUS_CREATED)
        {
            $application = JFactory::getApplication();

            // Send e-mail to the user.
            $mail_from_email    = $application->getCfg('mailfrom');
            $mail_from_name     = $application->getCfg('fromname');
            $mail_site_name     = $application->getCfg('sitename');

            if($mail_from_email == '' || $mail_from_name == '')
            {
                $user = JFactory::getUser();

                $mail_from_email    = $user->email;
                $mail_from_name     = $user->name;
            }

            $subject = JText::_('NEW_USER_MESSAGE_SUBJECT');
            $message = JText::sprintf('NEW_USER_MESSAGE', $context->result->name, $mail_site_name, KRequest::root(),
                $context->result->username, $context->result->password);

            JUtility::sendMail($mail_from_email, $mail_from_name, $context->result->email, $subject, $message);
        }
    }
}