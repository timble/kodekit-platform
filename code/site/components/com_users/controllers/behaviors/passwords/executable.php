<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Executable Controller Password Behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerBehaviorPasswordExecutable extends ComDefaultControllerBehaviorExecutable
{
    public function canEdit() {
        $result = parent::canEdit();

        if (!$result) {
            $password = $this->getModel()->getItem();
            $user     = JFactory::getUser();
            if (($password->email == $user->email)) {
                // Password owners are allowed to change their passwords.
                $result = true;
            }
        }
        return $result;
    }
}
