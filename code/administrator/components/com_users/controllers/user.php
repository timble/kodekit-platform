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

        $this->registerCallback('after.add' , array($this, 'notify'));
        $this->registerCallback('after.edit', array($this, 'reset'));
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com://admin/activities.controller.behavior.loggable' => array('title_column' => 'name'),
            )
        ));

        parent::_initialize($config);
    }

    protected function _actionDelete(KCommandContext $context)
    {
        $entity = parent::_actionDelete($context);

        $this->getService('com://admin/users.model.sessions')
            ->email($entity->email)
            ->getRowset()
            ->delete();

        return $entity;
    }

    public function reset(KCommandContext $context)
    {
        if ($context->getSubject()->getStatus() == self::STATUS_RESET)
        {
            $user = $context->result;
            JFactory::getUser($user->id)->setData($user->getData());
        }
    }

    public function notify(KCommandContext $context)
    {
        $user = $context->result;
        $reset = $user->reset;

        if(($user->getStatus() != KDatabase::STATUS_FAILED) && $reset)
        {
            $application = $this->getService('application');

            /*
            $url        = $this->getService('koowa:http.url',
                array('url' => "index.php?option=com_users&view=password&layout=form&id={$password->id}&token={$token}"));
            $this->getService('com://site/application.router')->build($url);
            */
            // TODO Hardcoding URL since AFAIK currently there's no other way to build a frontend route from here.
            // Due to namespacing problems the backend router will always be returned.
            $url = "/component/users/user?layout=password&uuid={$user->uuid}&reset={$reset}";
            $url = $context->request->getUrl()->getUrl(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT) . $url;

            $subject = JText::_('NEW_USER_MESSAGE_SUBJECT');
            $message = JText::sprintf('NEW_USER_MESSAGE', $context->result->name, $application->getCfg('sitename'), $url);

           $user->notify(array('subject' => $subject, 'message' => $message));
        }
    }
}