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
    /**
     * The password
     *
     * @param UsersEntityPassword
     */
    private $__password;

    protected function _beforeInsert(Library\DatabaseContext $context)
    {
        $user = $context->data;

        // Generate a random password
        if(!$user->password) {
            $user->password = $this->getObject('com:users.model.entity.password')->createPassword();
        }
    }

    protected function _beforeUpdate(Library\DatabaseContext $context)
    {
        $user = $context->data;

        if($user->password)
        {
            // Fetch the password entity
            $password = $this->getPassword();

            // Update the password
            $password->setProperties(array('password' => $user->password));

            if (!$password->save())
            {
                $user->setStatus(Library\Database::STATUS_FAILED);
                $user->setStatusMessage($password->getStatusMessage());
                return false;
            }
        }
    }

    protected function _afterInsert(Library\DatabaseContext $context)
    {
        $user = $context->data;

        if ($user->getStatus() == Library\Database::STATUS_CREATED)
        {
            // Create a password row for the user.
            $password = $this->getPassword();

            $data = array(
                'id'       => $user->email,
                'password' => $user->password
            );

            $password->isNew() ? $password->create($data) : $password->setProperties($data);
            $password->save();
        }
    }

    public function getPassword()
    {
        if (!$this->isNew() && !isset($this->__password))
        {
            $this->__password = $this->getObject('com:users.model.passwords')
                ->id($this->email)
                ->fetch();
        }

        return $this->__password;
    }

    /**
     * Migrate passwords to BCrypt on the fly
     *
     * @param string $password The plain-text password to verify
     * @return bool Returns TRUE if the plain-text password and users hashed password, or FALSE otherwise.
     */
    public function verifyPassword($password)
    {
        $result = false;
        $entity = $this->getPassword();

        if($hash = $entity->hash)
        {
            // Check for MD5 hashes.
            if (strpos($hash, '$') === false)
            {
                $parts = explode(':', $hash);
                if ($parts[0] === md5($password . @$parts[1]))
                {
                    // Valid password on existing record. Migrate to BCrypt.
                    $entity->hash = $entity->createHash($password);
                    $result = $entity->save();
                }
            }
            else $result = password_verify($password, $hash);
        }

        return $result;
    }

    public function resetPassword()
    {
        if (!$this->isNew())
        {
            $entity = $this->getPassword();

            $password = $entity->createPassword(32);
            $token    = $entity->createHash($password);

            $entity->token = $token;
            $entity->save();
        }

        return true;
    }

    protected function verifyToken($token)
    {
        $result = false;

        $hash = $this->getPassword()->token;
        if ($hash && (password_verify($token, $hash))) {
            $result = true;
        }

        return $result;
    }
}