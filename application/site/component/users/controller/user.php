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
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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

        // Set request so that actions are made against logged user if none was given.
        if (!$request->query->get('id','int') && ($id = $this->getUser()->getId())) {
            $request->query->id = $id;
        }

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

        if ($user->getStatus() == Library\Database::STATUS_CREATED)
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, 'User account successfully created');
        }

        return $user;
    }
}