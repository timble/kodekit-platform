<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Session Executable Controller Behavior Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerBehaviorSessionExecutable extends ComDefaultControllerBehaviorExecutable {

    public function canAdd() {
        return true;
    }

    public function canDelete() {
        //Allow logging out ourselves
        if($this->getModel()->getState()->id == JFactory::getSession()->getId()) {
            return true;
        }

        // Only administrator can logout other users
        if(JFactory::getUser()->gid > 24) {
            return true;
        }

        return false;
    }

    public function canEdit() {
        return false;
    }

}