<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;
use Kodekit\Component\Users;

/**
 * Session Controller
 *
 * @author   Johan Janssens <http://github.com/johanjanssens>
 * @package  Kodekit\Platform\Users
 */
class ControllerSession extends Users\ControllerSession
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name'),
            )
        ));

        parent::_initialize($config);
    }

    protected function _beforeAdd(Library\ControllerContextModel $context)
    {
        $result = true;

        $user = $this->getUser();

        // Check if the user is granted admin access.
        if (!$user->hasRole(array('manager', 'administrator')))
        {
            $result = false;

            // Mark user as non authenticated.
            $data            = $user->getData();
            $data->authentic = false;
            $user->setData($data);

            $context->response->setRedirect($context->request->getReferrer(),
                $this->getObject('translator')->translate('Access denied'));
        }

        return $result;
    }

    protected function _actionAdd(Library\ControllerContextModel $context)
    {
        $result = parent::_actionAdd($context);

        //Set the session data
        if ($context->response->isSuccess()) {
            $context->user->getSession()->site = $this->getObject('application')->getSite();
        }

        return $result;
    }
}