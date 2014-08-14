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
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Users
 */
class UsersControllerUser extends Users\ControllerUser
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
        $context->request->data->role_id = '18';

        $user = parent::_actionAdd($context);

        if ($user->getStatus() == $user::STATUS_CREATED)
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, 'User account successfully created');
        }

        return $user;
    }
}