<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * User Controller
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
 */
class UsersControllerUser extends Users\ControllerUser
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('before.add'  , '_resetPassword');
        $this->addCommandCallback('after.edit'  , '_expirePassword');
    }

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

    /**
     * Reset password callback.
     *
     * @param Library\ControllerContextInterface $context
     */
    protected function _resetPassword(Library\ControllerContextInterface $context)
    {
        if (!$context->request->data->get('password', 'string'))
        {
            $user = $context->result;

            if ($user->getStatus() !== $user::STATUS_FAILED && $this->isResettable())
            {
                if (!$this->token($context)) {
                    $context->response->addMessage('Failed to deliver the password reset token', 'error');
                }
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
        if ($context->request->data->get('password_reset', 'boolean'))
        {
            $user = $context->result;

            // Expire the user's password if a password reset was requested.
            if ($user->getStatus() !== $user::STATUS_FAILED && $user->isExpirable()){
                $user->getPassword()->expire();
            }
        }
    }
}
