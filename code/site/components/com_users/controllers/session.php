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

    protected function _actionGet(KCommandContext $context)
    {
        //Force the application template
        if ($this->getService('application')->getCfg('offline') && JFactory::getUser()->get('guest')) {
            KRequest::set('get.tmpl', 'login');
        }

        //Set the status
        $context->response->setStatus(KHttpResponse::UNAUTHORIZED);

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

        if(!$context->response->isError())
        {
            //Set the session data
            $session->user = $context->user;
            $session->site = $this->getService('application')->getSite();
        }

        return $data;
    }

    protected function _actionDelete(KCommandContext $context)
    {
        //Force logout from site only
        $this->application = array($this->getIdentifier()->application);

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

            //If the user is enabled, redirect with an error
            if (!$context->user->enabled) {
                JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_NOLOGIN_BLOCKED'));
                return false;
            }

            //Mark the user as logged in
            $context->user->guest = 0;

            return true;
       }

       return false;
    }

    public function redirect(KCommandContext $context)
    {
        if ($context->result !== false)
        {
            $password = $context->user->getPassword();
            if ($password->expired())
            {
                $url = 'index.php?option=com_users&view=password&layout=form&id=' . $password->id;
                $url = $this->getService('koowa:http.url', array('url' => $url));

                $this->getService('application')->getRouter()->build($url);
                $context->response->setRedirect($url);
            }
        }
    }
}