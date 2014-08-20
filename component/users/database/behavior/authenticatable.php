<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Authenticatable Database Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorAuthenticatable extends Library\DatabaseBehaviorAbstract
{
    protected function _beforeInsert(Library\DatabaseContext $context)
    {
        $data = $context->data;

        if(!$data->password)
        {
            // Generate a random password
            $password       = $this->getObject('com:users.model.entity.password');
            $data->password = $password->createPassword();
        }
    }

    protected function _beforeUpdate(Library\DatabaseContext $context)
    {
        $data = $context->data;

        if($data->password)
        {
            // Update password record.
            $password = $data->getPassword();

            if (!$password->setProperties(array('password' => $data->password))->save())
            {
                $data->setStatus(Library\Database::STATUS_FAILED);
                $data->setStatusMessage($password->getStatusMessage());
                return false;
            }
        }
    }

    protected function _afterInsert(Library\DatabaseContext $context)
    {
        $data = $context->data;

        if ($data->getStatus() == Library\Database::STATUS_CREATED)
        {
            // Create a password row for the user.
            $password = $this->getPassword();

            $data = array('id' => $data->email, 'password' => $data->password);

            $password->isNew() ? $password->create($data) : $password->setProperties($data);

            $password->save();
        }
    }

    public function getPassword()
    {
        $password = null;

        if (!$this->isNew())
        {
            $password = $this->getObject('com:users.model.passwords')
                ->id($this->email)
                ->fetch();
        }

        return $password;
    }
}