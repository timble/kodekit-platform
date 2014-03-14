<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Recoverable Controller Behavior.
 *
 * Provides a mechanism for persisting data on failed POST requests.
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Library\Controller
 */
class ControllerBehaviorRecoverable extends  ControllerBehaviorPersistableAbstract
{
    public function isSupported()
    {
        $mixer   = $this->getMixer();
        $request = $mixer->getRequest();

        if ($mixer instanceof ControllerModellable && $mixer->isDispatched() && !$request->isAjax())
        {
            $result = true;
        }

        return $result;
    }

    $this

    protected function _beforeAdd(ControllerContextInterface $context)
    {
        $data = $context->getRequest()->getData()->toArray();
        $this->_setData($data, $context);
    }

    protected function _afterAdd(ControllerContextInterface $context)
    {
        // Clear data if action didn't fail.
        if ($context->result)
        {
            $this->_unsetData($context);
        }
    }

    protected function _afterRead(ControllerContextInterface $context)
    {
        if ($data = $this->_getData($context))
        {
            $result = $context->result;

            if ($result instanceof DatabaseRowInterface)
            {
                // Push data to row object.
                $result->setData($data, false);
            }
            else
            {
                // Push data to the view.
                $context->subject->getView()->persisted_data = $data;
            }

            $this->_unsetData($context);
        }
    }
}