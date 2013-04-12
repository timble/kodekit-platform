<?php
/**
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * User Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class UsersControllerUser extends ApplicationControllerDefault
{ 
    public function __construct(Library\Config $config)
    {
        parent::__construct($config);

        $this->registerCallback('after.add' , array($this, 'notify'));
        $this->registerCallback('after.edit', array($this, 'reset'));
    }
    
    protected function _initialize(Library\Config $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name'),
            )
        ));

        parent::_initialize($config);
    }

    protected function _actionDelete(Library\CommandContext $context)
    {
        $entity = parent::_actionDelete($context);

        $this->getService('com:users.model.sessions')
            ->email($entity->email)
            ->getRowset()
            ->delete();

        return $entity;
    }

    public function reset(Library\CommandContext $context)
    {
        if ($context->response->getStatusCode() == self::STATUS_RESET)
        {
            $user = $context->result;
            JFactory::getUser($user->id)->setData($user->getData());
        }
    }

    public function notify(Library\CommandContext $context)
    {
        $user = $context->result;
        $reset = $user->reset;

        if(($user->getStatus() != Library\Database::STATUS_FAILED) && $reset)
        {
            $component = $this->getService('application.components')->getComponent('users');
            $page     = $this->getService('application.pages')->find(array(
                'extensions_component_id' => $component->id,
                'link'                   => array(array('view' => 'user'))));

            $link = clone $page->getLink();
            $link->query['layout'] = 'password';
            $link->query['uuid'] = $user->uuid;
            $link->query['token'] = $user->reset;

            // TODO Route URL after backend routing of frontent URLs is made possible.
            //$this->getService('application')->getRouter()->build($link);

            $subject = JText::_('NEW_USER_MESSAGE_SUBJECT');
            // TODO Fix following lines after language package is re-factored.
            //$message = JText::sprintf('NEW_USER_MESSAGE', $context->result->name, $application->getCfg('sitename'), $link);
            $message = $link;

           $user->notify(array('subject' => $subject, 'message' => $message));
        }
    }
}