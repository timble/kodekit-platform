<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * Session Controller
 *
 * @author   Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package  Component\Users
 */
class UsersControllerSession extends Users\ControllerSession
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

    protected function _beforeAdd(Library\ControllerContextInterface $context)
    {
        $result = true;

        $user = $this->getUser();

        // Check if the user is granted admin access.
        if ($user->getRole() <= 22)
        {
            $result = false;

            // Mark user as non authenticated.
            $data            = $user->getData();
            $data->authentic = false;
            $user->setData($data);

            $context->response->setRedirect($context->request->getReferrer(),
                \JText::_('Access denied'));
        }

        return $result;
    }

    protected function _actionAdd(Library\ControllerContextInterface $context)
    {
        $result = parent::_actionAdd($context);

        //Set the session data
        if ($context->response->isSuccess()) {
            $context->user->getSession()->site = $this->getObject('application')->getSite();
        }

        return $result;
    }
}