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
 * Session Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class ControllerSession extends Library\ControllerModel
{
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