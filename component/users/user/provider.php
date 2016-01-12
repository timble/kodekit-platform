<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * User Provider
 *
 * The user provider will load users by their email address or user id from a data store. Once a user object is
 * loaded it is cached in memory.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Users
 */
class UserProvider extends Library\UserProvider
{
    /**
     * The list of user emails
     *
     * @var array
     */
    protected $_emails = array();

    /**
     * Get the user for the given username or identifier, fetching it from data store if it doesn't exist yet.
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @param bool  $refresh     If TRUE and the user has already been loaded it will be re-loaded.
     * @return Library\UserInterface Returns a UserInterface object.
     */
    public function getUser($identifier, $refresh = false)
    {
        $user = $this->getObject('user');

        // Find session user identifier
        if (!is_numeric($identifier)) {
            $current = $user->getEmail();
        } else {
            $current = $user->getId();
        }

        // Fetch the user if not exists
        if ($current != $identifier)
        {
            if(!$user = parent::getUser($identifier, $refresh))
            {
                if (!is_numeric($identifier)) {
                    $field = 'email';
                } else {
                    $field = 'id';
                }

                $user = $this->create(array(
                    $field => $identifier,
                    'name' => $this->getObject('translator')->translate('Anonymous')
                ));
            }
        }

        return $user;
    }

    /**
     * Set a user in the provider
     *
     * @param Library\UserInterface $user
     * @return UserProvider
     */
    public function setUser(Library\UserInterface $user)
    {
        parent::setUser($user);

        //Store the user by email
        if($email = $user->getEmail()) {
            $this->_emails[$email] = $user->getId();
        }

        return $this;
    }

    /**
     * Fetch the user for the given user identifier from the data store
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @param bool  $refresh     If TRUE and the user has already been fetched it will be re-fetched.
     * @return boolean
     */
    public function fetch($identifier, $refresh = false)
    {
        $identifiers = (array) $identifier;

        //Only fetch identifiers that haven't been loaded yet.
        if(!$refresh)
        {
            foreach($identifiers as $key => $value)
            {
                if($this->isLoaded($value)) {
                    unset($identifiers[$key]);
                }
            }
        }

        if(!empty($identifier))
        {
            if (!is_numeric($identifier[0])) {
                $users = $this->getObject('com:users.model.users')->email($identifiers)->fetch();
            } else {
                $users = $this->getObject('com:users.model.users')->id($identifiers)->fetch();
            }

            foreach($users as $user)
            {
                $groups = array();
                foreach ($user->getGroups() as $group) {
                    $groups[] = $group->id;
                }

                //Load the user
                if($user->id)
                {
                    $data = array(
                        'id'         => $user->id,
                        'email'      => $user->email,
                        'name'       => $user->name,
                        'roles'      => array($user->getRole()->name),
                        'groups'     => $groups,
                        'password'   => $user->getPassword()->password,
                        'salt'       => $user->getPassword()->salt,
                        'enabled'    => $user->enabled,
                        'attributes' => $user->getParameters()->toArray(),
                        'expired'    => (bool) $user->activation,
                        'authentic'  => false
                    );

                    $this->setUser($this->create($data));
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Check if a user has already been loaded for a given user identifier
     *
     * @param $identifier
     * @return boolean TRUE if a user has already been loaded. FALSE otherwise
     */
    public function isLoaded($identifier)
    {
        $user = $this->getObject('user');

        // Find session user identifier
        if (!is_numeric($identifier)) {
            $current = $user->getEmail();
        } else {
            $current = $user->getId();
        }

        // Fetch the user if not exists
        if ($current != $identifier)
        {
            if (is_numeric($identifier)) {
                $result = isset($this->_users[$identifier]);
            } else {
                $result = isset($this->_emails[$identifier]);
            }
        }
        else $result = true;

        return $result;
    }
}