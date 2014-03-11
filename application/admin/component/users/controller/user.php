<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * User Controller
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Users
 */
class UsersControllerUser extends Users\ControllerUser
{ 
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'activatable'                                 => array('force' => false),
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name')
            )
        ));

        parent::_initialize($config);
    }

    protected function _actionDelete(Library\ControllerContextInterface $context)
    {
        $entity = parent::_actionDelete($context);

        $this->getObject('com:users.model.sessions')
            ->email($entity->email)
            ->fetch()
            ->delete();

        return $entity;
    }

    protected function _beforeAdd(Library\ControllerContextInterface $context)
    {
        // Expire password
        if (!$context->request->data->get('password', 'string'))
        {
            $this->addCommandCallback('after.add', '_resetPassword');
        }
    }

    protected function _beforeEdit(Library\ControllerContextInterface $context)
    {
        if ($context->request->data->get('password_reset', 'boolean'))
        {
            $this->addCommandCallback('after.edit', '_expirePassword');
        }
    }

    /**
     * Reset password callback.
     *
     * @param Library\ControllerContextInterface $context
     */
    protected function _resetPassword(Library\ControllerContextInterface $context)
    {
        $user = $context->result;

        if ($user->getStatus() !== Library\Database::STATUS_FAILED && $this->isResettable())
        {
            if (!$this->token($context))
            {
                $context->response->addMessage('Failed to deliver the password reset token', 'error');
            }
        }
    }

    /**
     * Expire password callback.
     *
     * @param Library\ControllerContextInterface $context
     */
    protected function _expirePassword(Library\ControllerContextInterface $context)
    {
        $user = $context->result;

        // Expire the user's password if a password reset was requested.
        if ($user->getStatus() !== Library\Database::STATUS_FAILED && $user->isExpirable())
        {
            $user->getPassword()->expire();
        }
    }
}
