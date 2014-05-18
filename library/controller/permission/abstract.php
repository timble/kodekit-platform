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
 * Abstract Controller Permission
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
     * Method returns TRUE iff the controller implements the ControllerModellable interface and the user is authentic.
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canAdd()
    {
        if($this->getMixer() instanceof ControllerModellable && $this->getUser()->isAuthentic()) {
            return true;
        }

        return false;
    }

    /**
     * Permission handler for edit actions
     *
     * Method returns TRUE iff the controller implements the ControllerModellable interface and the user is authentic.
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canEdit()
    {
        if($this->getMixer() instanceof ControllerModellable && $this->getUser()->isAuthentic()) {
            return true;
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
        if($this->getMixer() instanceof ControllerModellable && $this->getUser()->isAuthentic()) {
            return true;
        }

        return false;
    }
}