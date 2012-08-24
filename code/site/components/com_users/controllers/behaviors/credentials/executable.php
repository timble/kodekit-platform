<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Credential executable controller behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */

class ComUsersControllerBehaviorCredentialExecutable extends ComDefaultControllerBehaviorExecutable {

    public function canAdd() {
        return false;
    }

    public function canDelete() {
        return false;
    }

    public function canEdit() {
        $credential = $this->getModel()->getItem();
        $user = JFactory::getUser();

        if ($credential->id == $user->id) {
            // Crdential owners
            return true;
        }
        return parent::canEdit();
    }

}