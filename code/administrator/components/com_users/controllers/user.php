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

        $this->registerCallback(array('after.add', 'after.edit')  , array($this, 'notify'));
        $this->registerCallback(array('after.save', 'after.apply'), array($this, 'redirect'));
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

    protected function _actionEdit(KCommandContext $context)
    {
        $data = parent::_actionEdit($context);
        
        if ($context->response->getStatusCode() == KHttpResponse::RESET_CONTENT) {
            JFactory::getUser($data->id)->setData($data->getData());
        }
        
        return $data;
    }

    protected function _actionDelete(KCommandContext $context)
    {
        $data = parent::_actionDelete($context);
        
        $this->getService('com://admin/users.model.sessions')
            ->email($data->email)
            ->getList()
            ->delete();

        return $data;
    }

    public function redirect(KCommandContext $context)
    {
        $result = $context->result;

        if ($result && $result->getStatus() == KDatabase::STATUS_FAILED)
        {
            $context->response->setRedirect(KRequest::referrer());
            //@TODO : Set message in session
            //$this->setRedirect(KRequest::referrer(), JText::_($result->getStatusMessage()), 'error');
        }
    }

    public function notify(KCommandContext $context)
    {
        $user = $context->result;
        $token = $user->token;

        if(($user->getStatus() != KDatabase::STATUS_FAILED) && $token)
        {
            $password = $user->getPassword();
            $application = $this->getService('application');

            /*
            $url        = $this->getService('koowa:http.url',
                array('url' => "index.php?option=com_users&view=password&layout=form&id={$password->id}&token={$token}"));
            $this->getService('com://site/application.router')->build($url);
            */
            // TODO Hardcoding URL since AFAIK currently there's  no other way to build a frontend route from here.
            // Due to namespacing problems the backend router will always be returned. This will get fixed
            // when introducing PHP 5.3 namespacing.
            $url = "/component/users/password?layout=form&id={$password->id}&token={$token}";
            $url = KRequest::url()->getUrl(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT) . $url;

            $subject = JText::_('NEW_USER_MESSAGE_SUBJECT');
            $message = JText::sprintf('NEW_USER_MESSAGE', $context->result->name, $application->getCfg('sitename'), $url);

           $user->notify(array('subject' => $subject, 'message' => $message));
        }
    }
}