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
 * Authenticatable Database Behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersDatabaseBehaviorAuthenticatable extends KDatabaseBehaviorAbstract
{
    protected $_password;

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_mixin' => true
        ));

        parent::_initialize($config);
    }

    protected function _afterTableUpdate(KCommandContext $context)
    {
        $data = $context->data;

        if ($data->password_change) {
            // Force a password change on next login.
            $data->getPassword()->expire();
        }

        if ($context->password_reset) {
            // Set the user password for reset and keep a copy of the token on the context
            // data, a.k.a. resulting user row.
            $data->token = $data->getPassword()->setReset();
        }

        if ($data->getStatus() == KDatabase::STATUS_UPDATED) {
            // Reset the password object.
            $this->_password = null;
        }
    }

    protected function _beforeTableInsert(KCommandContext $context) {
        $data = $context->data;

        if (!$data->password) {
            // Generate a random password
            $params         = $this->getService('application.components')->users->params;
            $password       = $this->getService('com://admin/users.database.row.password');
            $data->password = $password->getRandom($params->get('password_length', 6));
            // Set the password row for reset
            $context->password_reset = true;
        }
    }

    protected function _beforeTableUpdate(KCommandContext $context)
    {
        $data = $context->data;

        if ($data->password)
        {
            // Update password record.
            $password = $data->getPassword();

            if (!$password->setData(array('password' => $data->password))->save())
            {
                $this->setStatus(KDatabase::STATUS_FAILED);
                $this->setStatusMessage($password->getStatusMessage());
                return false;
            }
        }
    }

    protected function _afterTableInsert(KCommandContext $context)
    {
        $data = $context->data;

        if ($data->getStatus() == KDatabase::STATUS_CREATED)
        {
            // Create a password row for the user.
            $data->getPassword()
                  ->setData(array('email' => $data->email, 'password' => $data->password))
                  ->save();

            // Same as update.
            $this->_afterTableUpdate($context);
        }
    }

    protected function _beforeTableDelete(KCommandContext $context)
    {
        $context->data->getPassword()->delete();
    }

    public function getPassword() {
        if (!$this->_password) {
            $password = null;
            $user     = $this->getMixer();

            if (!$user->isNew()) {
                $password = $this->getService('com://admin/users.model.password')->set('email', $this->email)
                    ->getItem();
            }
            $this->_password = $password;
        }
        return $this->_password;
    }
}