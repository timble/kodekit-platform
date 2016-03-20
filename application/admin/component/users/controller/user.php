<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;
use Kodekit\Component\Users;

/**
 * User Controller
 *
 * @author  Arunas Mazeika <http://github.com/arunasmazeika>
 * @package Kodekit\Platform\Users
 */
class ControllerUser extends Users\ControllerUser
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

        $this->getObject('com:users.model.sessions')->email($entity->email)->fetch()->delete();

        return $entity;
    }

    protected function _beforeAdd(Library\ControllerContextInterface $context)
    {
        // Expire password
        if (!$context->request->data->get('password', 'string')) {
            $this->addCommandCallback('after.add', '_resetPassword');
        }
    }

    protected function _beforeEdit(Library\ControllerContextInterface $context)
    {
        $data = $context->request->data;

        if ($data->get('password_reset', 'boolean')) {
            $this->addCommandCallback('after.edit', '_expirePassword');
        }

        $user = $this->getModel()->fetch();

        // Only administrators can change roles.
        if ($user->role_id != $data->role_id && !$this->getUser()->hasRole('administrator')) {
            $data->remove('role_id');
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
            if (!$this->token($this->getContext())) {
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
        if ($user->getStatus() !== Library\Database::STATUS_FAILED && $user->isExpirable()) {
            $user->getPassword()->expire();
        }
    }
}
