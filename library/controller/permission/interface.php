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
 * Controller Permission Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
interface ControllerPermissionInterface
{
    /**
     * Permission handler for render actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canRender();

    /**
     * Permission handler for read actions
     *
     * Method should return FALSE if the controller does not implements the ControllerModellable interface.
     *
     * @return  boolean Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canRead();

    /**
     * Permission handler for browse actions
     *
     * Method should return FALSE if the controller does not implements the ControllerModellable interface.
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canBrowse();

    /**
     * Permission handler for add actions
     *
     * Method should return FALSE if the controller does not implements the ControllerModellable interface.
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canAdd();

    /**
     * Permission handler for edit actions
     *
     * Method should return FALSE if the controller does not implements the ControllerModellable interface.
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canEdit();

    /**
     * Permission handler for delete actions
     *
     * Method should return FALSE if the controller does not implements the ControllerModellable interface.
     *
     * @return  boolean  Returns TRUE if action is permitted. FALSE otherwise.
     */
    public function canDelete();
}