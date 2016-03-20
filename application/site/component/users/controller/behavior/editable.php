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

/**
 * Editable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Users
 */
class ControllerBehaviorEditable extends Library\ControllerBehaviorEditable
{
    protected function _actionSave(Library\ControllerContextInterface $context)
    {
        $entity = parent::_actionSave($context);

        if ($entity->getStatus() === $entity::STATUS_FAILED) {
            $context->response->setRedirect($context->request->getUrl(), $entity->getStatusMessage(), 'error');
        }

        return $entity;
    }

    protected function _actionCancel(Library\ControllerContextInterface $context)
    {
        $context->response->setRedirect($this->getReferrer($context));
    }

    protected function _actionApply(Library\ControllerContextInterface $context)
    {
        $entity = $context->getSubject()->execute('edit', $context);

        $context->response->setRedirect($context->request->getUrl());
        return $entity;
    }

    public function canSave()
    {
        return $this->canEdit();
    }
}