<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Authenticatable Database Behavior
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorAuthenticatable extends Library\DatabaseBehaviorAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_mixin' => true
        ));

        parent::_initialize($config);
    }

    protected function _beforeTableInsert(Library\CommandContext $context)
    {
        $data = $context->data;

        if(!$data->password)
        {
            // Generate a random password
            $params         = $this->getObject('application.extensions')->users->params;
            $password       = $this->getObject('com:users.database.row.password');
            $data->password = $password->getRandom($params->get('password_length', 6));
        }
    }

    protected function _beforeTableUpdate(Library\CommandContext $context)
    {
        $data = $context->data;

        if($data->password)
        {
            // Update password record.
            $password = $data->getPassword();

            if (!$password->setData(array('password' => $data->password))->save())
            {
                $data->setStatus(Library\Database::STATUS_FAILED);
                $data->setStatusMessage($password->getStatusMessage());
                return false;
            }
        }
    }

    protected function _afterTableInsert(Library\CommandContext $context)
    {
        $data = $context->data;

        if ($data->getStatus() == Library\Database::STATUS_CREATED)
        {
            // Create a password row for the user.
            $data->getPassword()
                  ->setData(array('id' => $data->email, 'password' => $data->password))
                  ->save();
        }
    }

    public function getPassword()
    {
        $password = null;

        if (!$this->isNew())
        {
            $password = $this->getObject('com:users.database.row.password')
                ->set('id', $this->email);
            $password->load();
        }

        return $password;
    }
}