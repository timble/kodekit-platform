<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Application;

use Kodekit\Library;

/**
 * Abstract Controller Permission
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Activities
 */
abstract class ControllerPermissionAbstract extends Library\ControllerPermissionAbstract
{
    /**
     * Authorize handler for add actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canAdd()
    {
        $roles = array('author', 'editor', 'publisher', 'manager', 'administrator');

        if (parent::canAdd() && $this->getUser()->hasRole($roles)) {
            return true;
        }

        return false;
    }

    /**
     * Authorize handler for edit actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canEdit()
    {
        $roles = array('editor', 'publisher', 'manager', 'administrator');

        if (parent::canEdit() && $this->getUser()->hasRole($roles)) {
            return true;
        }

        return false;
    }

    /**
     * Authorize handler for delete actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canDelete()
    {
        $roles = array('publisher', 'manager', 'administrator');

        if (parent::canDelete() && $this->getUser()->hasRole($roles)) {
            return true;
        }

        return false;
    }
}