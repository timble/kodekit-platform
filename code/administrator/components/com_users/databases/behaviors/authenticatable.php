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
 * Authenticatable database behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersDatabaseBehaviorAuthenticatable extends KDatabaseBehaviorAbstract
{
    protected function _afterTableUpdate(KCommandContext $context) {
        if ($this->password_change) {
            // Force a password change on next login.

            $credential = $this->getCredential();

            if ($credential->isNew()) {
                $credential->id = $this->id;
            }

            $credential->setData(array('change' => 1))->save();
        }
    }

    protected function _afterTableInsert(KCommandContext $context) {
        $data = $context->data;

        if ($data->getStatus() == KDatabase::STATUS_CREATED) {
            // Create a credential row for the user.
            $this->getService('com://admin/users.database.row.credential', array('data' => array('id' => $this->id)))->save();
            // Same as updated.
            $this->_afterTableUpdate($context);
        }
    }

    protected function _afterTableDelete(KCommandContext $context) {

        $this->getCredential()->delete();

    }

    public function getCredential() {

        $credential = null;
        $user = $this->getMixer();

        if (!$user->isNew()) {
            $credential = $this->getService('com://admin/users.model.credentials')->set('id', $this->id)->getItem();
        }

        return $credential;
    }
}