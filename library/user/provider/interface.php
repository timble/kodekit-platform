<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * User Provider Interface
 *
 * A user provider is capable of loading and instantiation KUserInterface objects from a backend.
 *
 * In a typical authentication configuration, a username (i.e. some unique user identifier) credential enters the system
 * (via form login, or any method). The user provider that is configured with that authentication method is asked to fetch
 * the KUserInterface object for the given username or identifier.
 *
 * Internally, a user provider can load users from any source (databases, configuration, web service). This is totally
 * independent of how the authentication information is submitted or what the KUserInterface object looks like.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\User
 */
interface UserProviderInterface
{
    /**
     * Loads the user for the given username or identifier
     *
     * @param string $username The username
     * @return UserInterface|null Returns a KUserInterface object or NULL if the user could not be found.
     */
    public function load($username);

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be totally reloaded (e.g. from the database), or
     * if the KUserInterface object can just be merged into some internal array of users / identity map.
     *
     * @param UserInterface $user
     * @return UserInterface
     */
    public function refresh(UserInterface $user);
}