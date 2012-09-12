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

        $this->registerCallback('after.add'   , array($this, 'notify'));
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
        
        if ($context->status == KHttpResponse::RESET_CONTENT) {
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

    public function redirect(KCommandContext $context) {

        $result = $context->result;

        if ($result && $result->getStatus() == KDatabase::STATUS_FAILED) {
            $this->setRedirect(KRequest::referrer(), JText::_($result->getStatusMessage()), 'error');
        }
    }

    public function notify(KCommandContext $context)
    {
        if($context->result->getStatus() == KDatabase::STATUS_CREATED)
        {
            // Send e-mail to the user.
            $mail_from_email    = $this->getService('application')->getCfg('mailfrom');
            $mail_from_name     = $this->getService('application')->getCfg('fromname');
            $mail_site_name     = $this->getService('application')->getCfg('sitename');

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