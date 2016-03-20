<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

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
 * @package Kodekit\Library\User
 */
interface UserProviderInterface
{
    /**
     * Load the user for the given username or identifier
     *
     * If the user could not be loaded this method should return an anonymous user with a user 'id' off 0.
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @return UserInterface Returns a UserInterface object.
     */
    public function getUser($identifier);

    /**
     * Set a user in the provider
     *
     * @param UserInterface $user
     * @return UserProviderInterface
     */
    public function setUser(UserInterface $user);

    /**
     * Find a user for the given identifier
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @return UserInterface|null Returns a UserInterface object or NULL if the user hasn't been loaded yet
     */
    public function findUser($identifier);

    /**
     * Fetch the user for the given user identifier from the data store
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @param bool   $lazyload  Lazyload the $identifier(s) on the following call to getUser()
     * @return boolean
     */
    public function fetch($identifier, $lazyload= false);

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