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
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Users
 */
class ControllerUser extends Users\ControllerUser
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name'),
        )));

        parent::_initialize($config);
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        // Unset some variables because of security reasons.
        foreach(array('enabled', 'role_id', 'created_on', 'created_by', 'activation') as $variable) {
            $request->data->remove($variable);
        }

        return $request;
    }

    protected function _actionAdd(Library\ControllerContextInterface $context)
    {
        $role = $this->getObject('com:users.model.roles')->name('registered')->fetch();

        $context->request->data->role_id = $role->id;

        $user = parent::_actionAdd($context);

        if ($user->getStatus() == $user::STATUS_CREATED)
        {
            $url = $this->getObject('pages')->getDefault()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, 'User account successfully created');
        }

        return $user;
    }
}