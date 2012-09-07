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
 * Password database row class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersDatabaseRowPassword extends KDatabaseRowDefault
{
    public function save()
    {
        $user = $this->getService('com://admin/users.model.users')
            ->set('email', $this->id)
            ->getItem();

        // Check if referenced user actually exists.
        if ($user->isNew())
        {
            $this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage(JText::sprintf('USER NOT FOUND', $this->id));
            return false;
        }

        if ($password = $this->password)
        {
            // Check the password length.
            $params        = $this->getService('application.components')->users->params;
            $length = $params->get('password_length');
            if (strlen($password) < $length)
            {
                $this->setStatus(KDatabase::STATUS_FAILED);
                $this->setStatusMessage(JText::sprintf('PASSWORD TOO SHORT', $length));
                return false;
            }

            $helper = $this->getService('com://admin/users.helper.password');

            if (!$this->isNew())
            {
                // Check if new and current hashes are the same.
                if ($helper->encrypt($password, $helper->getSalt($this->hash)) == $this->hash)
                {
                    $this->setStatus(KDatabase::STATUS_FAILED);
                    $this->setStatusMessage(JText::_('New and old passwords are the same'));
                    return false;
                }
            }

            // Create hash.
            $this->hash = $helper->encrypt($password);
        }

        return parent::save();
    }
}