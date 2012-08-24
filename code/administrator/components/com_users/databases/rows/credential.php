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
 * Credential database row class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersDatabaseRowCredential extends KDatabaseRowDefault
{
    public function save() {

        $user = $this->getService('com://admin/users.model.users')->set('id', $this->id)
            ->getItem();

        if ($user->isNew()) {
            $this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage(JText::sprintf('USER_NOT_FOUND', $this->id));
        }

        if ($this->password) {

            $user->setData(array(
                'password'        => $this->password,
                'password_verify' => $this->password_verify));

            if (!$user->save()) {
                // Set error statuses and messages.
                $this->setStatus($user->getStatus());
                $this->setStatusMessage($user->getStatusMessage());
                return false;
            }

            // Remove change state.
            $this->change = 0;
        }

        return parent::save();
    }
}