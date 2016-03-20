<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

namespace Kodekit\Component\Users;

use Kodekit\Library;

/**
 * Session Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Users
 */
class ControllerSession extends Library\ControllerModel
{
    protected function _actionAdd(Library\ControllerContextInterface $context)
    {
        //Start the session (if not started already)
        $session = $context->user->getSession();

        //Insert the session into the database
        if(!$session->isActive()) {
            throw new Library\ControllerExceptionActionFailed('Session could not be stored. No active session');
        }

        //Fork the session to prevent session fixation issues
        $session->fork();

        //Prepare the data
        $data = array(
            'id'         => $session->getId(),
            'authentic'  => $context->user->isAuthentic(),
            'email'      => $context->user->getEmail(),
            'data'       => '',
            'time'       => time(),
            'domain'     => (string) $context->request->getBaseUrl()->getHost(),
            'path'       => (string) $context->request->getBaseUrl()->getPath(),
            'name'       => $context->user->getName()
        );

        $context->request->data->add($data);

        //Store the session
        return  parent::_actionAdd($context);
    }

    protected function _actionDelete(Library\ControllerContextInterface $context)
    {
        //Remove the session from the session store
        $entity = parent::_actionDelete($context);

        if(!$context->response->isError())
        {
            // Destroy the php session for this user if we are logging out ourselves
            if($context->user->getEmail() == $entity->email) {
                $context->user->getSession()->destroy();
            }
        }

        return $entity;
    }
}