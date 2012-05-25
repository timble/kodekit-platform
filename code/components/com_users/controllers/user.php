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
 * User Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerUser extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('before.edit', array($this, 'sanitizeData'))
             ->registerCallback('before.add' , array($this, 'sanitizeData'))
             ->registerCallback('after.add'  , array($this, 'notify'))
             ->registerCallback('after.save' , array($this, 'redirect'))
             ->registerCallback('after.read' , array($this, 'activate'));
	}
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'behaviors' => array(
        		'com://admin/activities.controller.behavior.loggable' => array(
               		'title_column' => 'name',
               		'actions'      => array('after.login', 'after.logout')),
        		'com://site/users.controller.behavior.user.spammable' )));
    
        parent::_initialize($config);
    }

    public function activate(KCommandContext $context)
    {
    	$row = $context->result;
    	$activation = $context->caller->getModel()->get('activation');
    	
    	if (!empty($activation)) 
    	{
    		if ($row->id && $row->activation === $activation) 
    		{
	    		$row->activation = '';
	    		$row->enabled = 1;

	    		if ($row->save()) {
	    			return JFactory::getApplication()->redirect(JURI::root(), JText::_('REG_ACTIVATE_COMPLETE'), 'message');
	    		}
    		}

    		return JFactory::getApplication()->redirect(JURI::root(), JText::_('REG_ACTIVATE_NOT_FOUND'), 'error');
    	}
    }
    
    public function getRequest()
    {
        $request = parent::getRequest();

        if($request->layout == 'form') {
            $request->id = JFactory::getUser()->id;
        }

        return $request;
    }

    public function _actionGet(KCommandContext $context)
    {
        $user = JFactory::getUser();

        if($this->_request->layout == 'register' && !$user->guest)
        {
            $url =  'index.php?Itemid='.JSite::getMenu()->getDefault()->id;
            $msg =  JText::_('You are already registered.');

            $this->setRedirect($url, $msg);
            return false;
        }

        return parent::_actionGet($context);
    }

    protected function _actionAdd(KCommandContext $context)
    {
    	$parameters = JComponentHelper::getParams('com_users');

        if(!($group_name = $parameters->get('new_usertype'))) {
            $group_name = 'Registered';
        }

        $context->data->id             = 0;
        $context->data->group_name     = $group_name;
        $context->data->users_group_id = JFactory::getAcl()->get_group_id('', $group_name, 'ARO');
        
        $context->data->registered_on  = gmdate('Y-m-d H:i:s');

        if($parameters->get('useractivation') == '1')
        {
            $password = $this->getService('com://site/users.helper.password');

            $context->data->activation = $password->getHash($password->getRandom(32));
            $context->data->enabled = 0;

            $message = JText::_('REG_COMPLETE_ACTIVATE');
        }
        else $message = JText::_('REG_COMPLETE');

        return parent::_actionAdd($context);
    }
    
    protected function _actionEdit(KCommandContext $context)
    {
        $data = parent::_actionEdit($context);
        
        if ($context->status == KHttpResponse::RESET_CONTENT) {
            JFactory::getUser()->bind($data->toArray());
        }
        
        return $data;
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

        jimport( 'joomla.user.authentication');
        $response = JAuthentication::getInstance()->authenticate($credentials, $options);

        if ($response->status === JAUTHENTICATE_STATUS_SUCCESS)
        {
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
        }

        JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_LOGIN_AUTHENTICATE'));

        $this->_redirect = KRequest::referrer();
        return $result;
    }

    protected function _actionLogout(KCommandContext $context)
    {
        $rowset = clone $this->getModel()->getList();

        if(count($rowset))
        {
            foreach($rowset as $user)
            {
                //Force logout from site only
                $clients = array(0);

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

    public function redirect(KCommandContext $context)
    {
        $item = $context->caller->getModel()->getItem();

        if ($item->getStatus() != KDatabase::STATUS_FAILED) {
            $this->setRedirect(KRequest::referrer(), JText::_('Modifications have been saved.'), 'message');
        } else {
            $this->setRedirect(KRequest::referrer(), $item->getStatusMessage(), 'error');
        }
    }

    public function notify(KCommandContext $context)
    {
        $config = JFactory::getConfig();

        $subject = sprintf(JText::_('Account details for'), $context->data->name, $config->getValue('sitename'));
        $subject = html_entity_decode($subject, ENT_QUOTES);

        $parameters     = JComponentHelper::getParams('com_users');
        $site_name      = $config->getValue('sitename');
        $site_url       = KRequest::url()->get(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT);
        $activation_url = $site_url.JRoute::_('index.php?option=com_users&view=user&activation='.$context->data->activation);
        $password       = preg_replace('/[\x00-\x1F\x7F]/', '', $context->data->password);

        if($parameters->get('useractivation') == 1 ) {
            $message = sprintf(JText::_('SEND_MSG_ACTIVATE'), $context->data->name, $site_name, $activation_url, $site_url, $context->data->username, $password);
        } else {
            $message = sprintf(JText::_('SEND_MSG'), $context->data->name, $site_name, $site_url);
        }

        $message = html_entity_decode($message, ENT_QUOTES);

        $super_administrators = $this->getService('com://site/users.model.users')
            ->set('group', 25)
            ->set('limit', 0)
            ->getList();

        $from_email = $config->getValue('mailfrom');
        $from_name  = $config->getValue('fromname');

        if(!$from_email || !$from_name )
        {
            $from_email = $super_administrators[0]->email;
            $from_name  = $super_administrators[0]->name;
        }

        JUtility::sendMail($from_email, $from_name, $context->data->email, $subject, $message);

        //Send email to super administrators
        foreach($super_administrators as $super_administrator)
        {
            if($super_administrator->send_mail)
            {
                $message = sprintf(JText::_('SEND_MSG_ADMIN'), $context->data->name, $site_name, $context->data->name, $context->data->email, $context->data->username);
                $message = html_entity_decode($message, ENT_QUOTES);

                JUtility::sendMail($from_email, $from_name, $super_administrator->email, $subject, $message);
            }
        }
    }

    public function sanitizeData(KCommandContext $context)
    {
        // Unset some variables because of security reasons.
        foreach(array('enabled', 'group_id', 'group_name', 'registered_on', 'activation') as $variable) {
            unset($context->data->{$variable});
        }

        // @TODO: Remove this foreach when we drop legacy.
        foreach(array('gid', 'block', 'usertype', 'registerDate') as $variable) {
            unset($context->data->{$variable});
        }
    }
}