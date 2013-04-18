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

        $this->registerCallback(array('after.add','after.edit'), array($this, 'expire'));
        $this->registerCallback('after.edit', array($this, 'reset'));
    }
    
    protected function _initialize(Library\Config $config)
    {
        $config->append(array(
            'behaviors' => array(
                'resettable',
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

    public function expire(Library\CommandContext $context)
    {
        $user = $context->result;
        // Expire the user's password if a password change was requested.
        if ($user->getStatus() !== Library\Database::STATUS_FAILED && $context->request->data->get('password_change',
            'bool')
        ) {
            $user->getPassword()->expire();
        }
    }
}