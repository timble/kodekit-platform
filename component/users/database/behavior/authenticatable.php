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
            'row_mixin' => true
        ));

        parent::_initialize($config);
    }

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
            $data->getPassword()
                  ->setProperties(array('id' => $data->email, 'password' => $data->password))
                  ->save();
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