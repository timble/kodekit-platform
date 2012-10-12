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
        if ($this->password_change) {
            // Force a password change on next login.
            $this->getPassword()->expire();
        }

        if ($this->password_reset) {
            // Set the user password for reset and keep a copy of the token on the context
            // data, a.k.a. resulting user row.
            $context->data->token = $this->getPassword()->setReset();
        }
    }

    protected function _beforeTableInsert(KCommandContext $context) {
        if (!$this->password) {
            // Generate a random password
            $params         = $this->getService('application.components')->users->params;
            $password       = $this->getService('com://admin/users.database.row.password');
            $this->password = $password->getRandom($params->get('password_length', 6));
            // Set the password row for reset
            $this->password_reset = true;
        }
    }

    protected function _beforeTableUpdate(KCommandContext $context)
    {
        if ($this->password)
        {
            // Update password record.
            $password = $this->getPassword();
            // TODO Need to keep a copy (workaround) of the current mixer. Otherwise it gets replaced on
            // password save when performing user validation http://cl.ly/0Q1a0H3D2l38.
            $mixer = clone $this->getMixer();
            if (!$password->setData(array('password' => $this->password))->save())
            {
                $this->setStatus(KDatabase::STATUS_FAILED);
                $this->setStatusMessage($password->getStatusMessage());
                return false;
            }
            // TODO Set mixer (see TODO statement above)
            $this->setMixer($mixer);
        }
    }

    protected function _afterTableInsert(KCommandContext $context)
    {
        $data = $context->data;

        if ($data->getStatus() == KDatabase::STATUS_CREATED)
        {
            // TODO Need to keep a copy (workaround) of the current mixer. Otherwise it gets replaced on
            // password save when performing user validation http://cl.ly/0Q1a0H3D2l38.
            $mixer = clone $this->getMixer();
            // Create a password row for the user.
            $this->getPassword()
                  ->setData(array('email' => $this->email, 'password' => $this->password))
                  ->save();

            // TODO Set mixer (see TODO statement above)
            $this->setMixer($mixer);

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