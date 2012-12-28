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

        // Force a password change on next login.
        if ($data->password_change) {
            $data->getPassword()->expire();
        }

        // Set the user password for reset and keep a copy of the token on the context
        if ($context->password_reset) {
            $data->reset = $data->getPassword()->setReset();
        }

        // Reset the password object.
        if ($data->getStatus() == KDatabase::STATUS_UPDATED) {
            $this->_password = null;
        }
    }

    protected function _beforeTableInsert(KCommandContext $context)
    {
        $data = $context->data;

        if(!$data->password)
        {
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

        if($data->password)
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
                  ->setData(array('id' => $data->email, 'password' => $data->password))
                  ->save();

            // Same as update.
            $this->_afterTableUpdate($context);
        }
    }

    public function getPassword()
    {
        $password = null;

        if(!$this->isNew())
        {
            $password = $this->getService('com://admin/users.database.table.passwords')
                              ->select(array('id' => $this->email), KDatabase::FETCH_ROW);
        }

        return $password;
    }
}