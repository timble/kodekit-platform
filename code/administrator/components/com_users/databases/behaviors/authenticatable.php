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

    protected function _beforeTableInsert(KCommandContext $context)
    {
        if (!$this->password)
        {
            // Generate a random password
            $params         = $this->getService('application.components')->users->params;
            $this->password = $this->getService('com://admin/users.helper.password')
                ->getRandom($params->get('password_length'));
        }
        elseif (!$this->_passwordsMatch()) {
            return false;
        }
    }

    protected function _beforeTableUpdate(KCommandContext $context)
    {
        if ($this->password)
        {
            if (!$this->_passwordsMatch()) {
                return false;
            }

            // Update password record.
            $password = $this->getPassword();
            // Reset expiration date.
            $password->resetExpiration(false);
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

    protected function _passwordsMatch()
    {
        // Check if passwords match.
        if ($this->password != $this->password_verify)
        {
            $this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage(JText::_('Passwords don\'t match'));
            return false;
        }
        return true;
    }

    public function getPassword($cached = true) {
        if (!$this->_password || !$cached) {
            $password = null;
            $user     = $this->getMixer();

            if (!$user->isNew()) {
                $password = $this->getService('com://admin/users.model.password')->set('id', $this->email)
                    ->getItem();
            }
            $this->_password = $password;
        }
        return $this->_password;
    }
}