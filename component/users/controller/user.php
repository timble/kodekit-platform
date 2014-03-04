<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * User Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
            $user = $this->getObject('user.provider')->load($entity->id, true);

            // Logged in user edited. Updated in memory/session user object.
            if($context->user->equals($user))
            {
                //Set user data in context
                $data = $user->toArray();
                $data['authentic'] = true;

                $context->user->setData($data);
            }
        }

        return $entity;
    }
}