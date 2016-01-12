<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
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
 * Internally, a user provider can load users from any data store (databases, configuration, web service). This is
 * totally independent of how the authentication information is submitted or what the UserInterface object looks
 * like.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
 */
interface UserProviderInterface
{
    /**
     * Get the user for the given username or identifier, fetching it from data store if it doesn't exist yet.
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @param bool  $refresh     If TRUE and the user has already been loaded it will be re-loaded.
     * @return UserInterface Returns a UserInterface object.
     */
    public function getUser($identifier, $refresh = false);

    /**
     * Set a user in the provider
     *
     * @param UserInterface $user
     * @return UserProviderInterface
     */
    public function setUser(UserInterface $user);

    /**
     * Fetch the user for the given user identifier from the data store
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @param bool  $refresh     If TRUE and the user has already been fetched it will be re-fetched.
     * @return boolean
     */
    public function fetch($identifier, $refresh = false);

    /**
     * Create a user object
     *
     * @param array $data An associative array of user data
     * @return UserInterface     Returns a UserInterface object
     */
    public function create($data);

    /**
     * Check if a user has already been loaded for a given user identifier
     *
     * @param $identifier
     * @return boolean TRUE if a user has already been loaded. FALSE otherwise
     */
    public function isLoaded($identifier);
}