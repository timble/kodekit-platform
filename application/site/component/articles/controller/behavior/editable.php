<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Editable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Articles
 */
class ControllerBehaviorEditable extends Library\ControllerBehaviorEditable
{
    /**
     * Check if the entity is lockable
     *
     * @return bool Returns TRUE if the entity is can be locked, FALSE otherwise.
     */
    public function isLockable()
    {
        $result = false;

        if($this->getView()->getLayout() == 'form') {
            $result = parent::isLockable();
        }

        return $result;
    }
}