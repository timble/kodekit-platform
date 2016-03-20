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
 * User Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Users
 */
class ControllerUser extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('editable', 'resettable', 'activatable')
        ));

        parent::_initialize($config);
    }

    protected function _actionEdit(Library\ControllerContextInterface $context)
    {
        $entity = parent::_actionEdit($context);

        if ($context->response->getStatusCode() == Library\HttpResponse::RESET_CONTENT)
        {
            $provider = $this->getObject('user.provider');

            $user = $provider->getUser($entity->id, true);

            // Logged in user edited. Updated in memory/session user object.
            if($context->user->equals($user))
            {
                //Set user data in context
                $data = $provider->fetch($user->getId())->toArray();
                $data['authentic'] = true;

                $context->user->setData($data);
            }
        }

        return $entity;
    }
}