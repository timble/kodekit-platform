<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * User Provider
 *
 * The user provider will load users by their email address.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Users
 */
class UserProvider extends Library\UserProvider
{
    /**
     * Loads the user for the given identifier (email address or user id)
     *
     * @param string $identifier A unique user identifier, (i.e a user id or email address)
     * @param bool  $refresh     If TRUE and the user has already been loaded it will be re-loaded.
     * @return Library\UserInterface|null Returns a UserInterface object or NULL if the user could not be found.
     */
    public function load($identifier, $refresh = false)
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
            $user = parent::load($identifier, $refresh);

            if (empty($user))
            {
                $user = $this->create(array(
                    'id'   => $identifier,
                    'name' => $this->getObject('translator')->translate('Anonymous')
                ));
            }
        }

        return $user;
    }

    /**
     * Fetch the user for the given user identifier from the backend
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @return Library\UserInterface|null Returns a UserInterface object or NULL if the user could not be found.
     */
    public function fetch($identifier)
    {
        // Find session user identifier
        if (!is_numeric($identifier)) {
            $user = $this->getObject('com:users.model.users')->email($identifier)->fetch();
        } else {
            $user = $this->getObject('com:users.model.users')->id($identifier)->fetch();
        }

        //Load the user
        if($user->id)
        {
            $data = array(
                'id'         => $user->id,
                'email'      => $user->email,
                'name'       => $user->name,
                'role'       => $user->role_id,
                'groups'     => $user->getGroups(),
                'password'   => $user->getPassword()->password,
                'salt'       => $user->getPassword()->salt,
                'enabled'    => $user->enabled,
                'attributes' => $user->getParameters()->toArray(),
                'authentic'  => false
            );

            $user = $this->create($data);
        }
        else $user = null;

        return $user;
    }
}