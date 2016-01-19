<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Resettable Controller Behavior
 *
 * @author     Arunas Mazeika <http://github.com/amazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ControllerBehaviorResettable extends Library\ControllerBehaviorAbstract
{
    protected function _beforeReset(Library\ControllerContextInterface $context)
    {
        $result = false;

        $user   = $this->getModel()->fetch();
        $token  = $context->request->data->get('token', 'alnum');

        if (!$user->isNew() && $user->verifyToken($token)) {
            $result = true;
        }

        return $result;
    }

    protected function _actionReset(Library\ControllerContextInterface $context)
    {
        $result = true;

        $user = $this->getModel()->fetch();
        $user->password = $context->request->data->get('password', 'string');
        $user->save();

        if ($user->getStatus() == $user::STATUS_FAILED)
        {
            $context->error = $user->getStatusMessage();
            $result = false;
        }

        return $result;
    }

    protected function _beforeToken(Library\ControllerContextInterface $context)
    {
        $result = false;
        $email  = $context->request->data->get('email', 'email');
        $user   = $this->getObject('user.provider')->getUser($email);

        if ($user->getId())
        {
            $context->entity = $user;
            $result          = true;
        }

        return $result;
    }

    protected function _actionToken(Library\ControllerContextInterface $context)
    {
        return $this->getModel()->fetch()->resetPassword();
    }
}
