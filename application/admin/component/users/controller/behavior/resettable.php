<?php
/**
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Users
 * @copyright      Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library, Nooku\Component\Users;

class UsersControllerBehaviorResettable extends Users\ControllerBehaviorResettable
{
    protected function _beforeControllerAdd(Library\CommandContext $context)
    {
        if (!$context->request->data->get('password', 'string'))
        {
            // Force a password reset.
            $context->request->data->password_reset = true;
        }
    }

    protected function _afterControllerAdd(Library\CommandContext $context)
    {
        $user = $context->result;
        if ($context->request->data->get('password_reset', 'boolean') && $user->getStatus() !== Library\Database::STATUS_FAILED)
        {
            if (!$this->token($context)) {
                $context->response->addMessage('Failed to deliver the password reset token', 'error');
            }
        }
    }

    protected function _afterControllerEdit(Library\CommandContext $context)
    {
        // Same as add.
        return $this->_afterControllerAdd($context);
    }
}