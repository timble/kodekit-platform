<?php
/**
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Extensions;

/**
 * User Editable Controller Behavior
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Users
 */
class UsersControllerBehaviorEditable extends Extensions\ControllerBehaviorEditable
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