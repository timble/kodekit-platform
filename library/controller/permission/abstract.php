<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Controller Permission
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerPermissionAbstract extends ObjectMixinAbstract implements ControllerPermissionInterface
{
    /**
     * Permission handler for render actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canRender()
    {
        if($this->getMixer() instanceof ControllerViewable) {
            return true;
        }

        return false;
    }

    /**
     * Permission handler for read actions
     *
     * Method returns TRUE iff the controller implements the ControllerModellable interface.
     *
     * @return  boolean Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canRead()
    {
        if($this->getMixer() instanceof ControllerModellable) {
            return true;
        }

        return false;
    }

    /**
     * Permission handler for browse actions
     *
     * Method returns TRUE iff the controller implements the ControllerModellable interface.
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canBrowse()
    {
        if($this->getMixer() instanceof ControllerModellable) {
            return true;
        }

        return false;
    }

    /**
     * Permission handler for add actions
     *
     * Method returns TRUE iff the controller implements the ControllerModellable interface and the user is authentic
     * and the account is enabled.
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canAdd()
    {
        if($this->getMixer() instanceof ControllerModellable)
        {
            $user = $this->getUser();
            if ($user->isAuthentic() && $user->isEnabled()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Permission handler for edit actions
     *
     * Method returns TRUE iff the controller implements the ControllerModellable interface and the user is authentic
     * and the account is enabled.
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canEdit()
    {
        if($this->getMixer() instanceof ControllerModellable)
        {
            $user = $this->getUser();
            if ($user->isAuthentic() && $user->isEnabled()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Permission handler for delete actions
     *
     * Method returns true of the controller implements ControllerModellable interface and the user is authentic.
     *
     * @return  boolean  Returns TRUE if action is permitted. FALSE otherwise.
     */
    public function canDelete()
    {
        if($this->getMixer() instanceof ControllerModellable)
        {
            $user = $this->getUser();
            if ($user->isAuthentic() && $user->isEnabled()) {
                return true;
            }
        }

        return false;
    }
}