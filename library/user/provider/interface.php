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
 * A user provider is capable of loading and instantiation UserInterface objects from a backend.
 *
 * In a typical authentication configuration, a username (i.e. some unique user identifier) credential enters the
 * system (via form login, or any method). The user provider that is configured with that authentication method is
 * asked to fetch the UserInterface object for the given identifier.
 *
 * Internally, a user provider can load users from any source (databases, configuration, web service). This is
 * totally independent of how the authentication information is submitted or what the UserInterface object looks
 * like.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\User
 */
interface UserProviderInterface
{
    /**
     * Loads the user for the given username or identifier
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @param bool  $refresh     If TRUE and the user has already been loaded it will be re-loaded.
     * @return UserInterface Returns a UserInterface object.
     */
    public function load($identifier, $refresh = false);

    /**
     * Create a user object
     *
     * @param array $data An associative array of user data
     * @return UserInterface     Returns a UserInterface object
     */
    public function create($data);

    /**
     * Fetch the user for the given user identifier from the backend
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @return UserInterface|null Returns a UserInterface object or NULL if the user could not be found.
     */
    public function fetch($identifier);
}