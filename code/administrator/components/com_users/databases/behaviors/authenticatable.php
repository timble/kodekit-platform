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
        // Force a password change on next login.
        if ($this->password_change) {
            $this->getPassword()->expire();
        }
    }

    protected function _beforeTableInsert(KCommandContext $context) {
        if (!$this->password) {
            // Generate a random password
            $params         = $this->getService('application.components')->users->params;
            $password       = $this->getService('com://admin/users.database.row.password');
            $this->password = $password->getRandom($params->get('password_length'));
        }
    }

    protected function _beforeTableUpdate(KCommandContext $context)
    {
        if ($this->password)
        {
            // Update password record.
            $password = $this->getPassword();
            if (!$password->setData(array('password' => $this->password))->save())
            {
                $this->setStatus(KDatabase::STATUS_FAILED);
                $this->setStatusMessage($password->getStatusMessage);
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
            $this->getPassword()
                  ->setData(array('id' => $this->email, 'password' => $this->password))
                  ->save();

            // Same as update.
            $this->_afterTableUpdate($context);
        }
    }

    protected function _beforeTableDelete(KCommandContext $context)
    {
        $this->getPassword()->delete();
    }

    public function getPassword($cached = true) {
        if (!$this->_password || !$cached) {
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