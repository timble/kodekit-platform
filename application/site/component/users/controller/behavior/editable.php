<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git
 */
use Nooku\Library, Nooku\Component\Extensions;

/**
 * Users Editable Controller Behavior
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 */
class UsersControllerBehaviorEditable extends \Nooku\Component\Extensions\ControllerBehaviorEditable
{
    protected function _actionSave(Library\CommandContext $context)
    {
        $entity = parent::_actionSave($context);

        if ($entity->getStatus() === Library\Database::STATUS_FAILED) {
            $context->response->setRedirect($context->request->getUrl(), $entity->getStatusMessage(), 'error');
        }

        return $entity;
    }
}