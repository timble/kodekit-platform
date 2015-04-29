<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Abstract Controller Permission
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
abstract class ApplicationControllerPermissionAbstract extends Library\ControllerPermissionAbstract
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